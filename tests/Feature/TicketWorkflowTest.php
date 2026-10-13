<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketAssigned;
use App\Notifications\TicketCommentAdded;
use App\Notifications\TicketStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TicketWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function ticketWithAgent(User $author, User $agent, string $status): Ticket
    {
        return $this->makeTicket($author, [
            'agent_id' => $agent->id,
            'status' => $status,
        ]);
    }

    public function test_agent_can_take_new_ticket(): void
    {
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->makeTicket($this->makeUser());

        $this->actingAs($agent)->post(route('tickets.take', $ticket))
            ->assertSessionHasNoErrors();

        $ticket->refresh();

        $this->assertSame($agent->id, $ticket->agent_id);
        $this->assertSame(Ticket::STATUS_IN_PROGRESS, $ticket->status);
    }

    public function test_taking_a_ticket_writes_history(): void
    {
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->makeTicket($this->makeUser());

        $this->actingAs($agent)->post(route('tickets.take', $ticket));

        $this->assertDatabaseHas('ticket_history', [
            'ticket_id' => $ticket->id,
            'user_id' => $agent->id,
            'field' => 'agent',
            'new_value' => $agent->name,
        ]);
        $this->assertDatabaseHas('ticket_history', [
            'ticket_id' => $ticket->id,
            'user_id' => $agent->id,
            'field' => 'status',
            'old_value' => 'Nowe',
            'new_value' => 'W realizacji',
        ]);
    }

    public function test_second_agent_cannot_take_already_taken_ticket(): void
    {
        $first = $this->makeUser(User::ROLE_AGENT);
        $second = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->makeTicket($this->makeUser());

        $this->actingAs($first)->post(route('tickets.take', $ticket));
        $this->actingAs($second)->post(route('tickets.take', $ticket))->assertForbidden();

        $this->assertSame($first->id, $ticket->refresh()->agent_id);
    }

    public function test_admin_can_assign_agent(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->makeTicket($this->makeUser());

        $this->actingAs($admin)
            ->post(route('tickets.assign', $ticket), ['agent_id' => $agent->id])
            ->assertSessionHasNoErrors();

        $ticket->refresh();

        $this->assertSame($agent->id, $ticket->agent_id);
        $this->assertSame(Ticket::STATUS_IN_PROGRESS, $ticket->status);
    }

    public function test_regular_user_cannot_be_assigned_as_agent(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $notAgent = $this->makeUser(User::ROLE_USER);
        $ticket = $this->makeTicket($this->makeUser());

        $this->actingAs($admin)
            ->post(route('tickets.assign', $ticket), ['agent_id' => $notAgent->id])
            ->assertSessionHasErrors('agent_id');

        $this->assertNull($ticket->refresh()->agent_id);
    }

    public function test_invalid_status_transition_is_blocked(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $ticket = $this->makeTicket($this->makeUser());

        // z "Nowe" nie można przejść od razu do "Zamknięte"
        $this->actingAs($admin)
            ->patch(route('tickets.status', $ticket), ['status' => Ticket::STATUS_CLOSED])
            ->assertSessionHasErrors('status');

        $this->assertSame(Ticket::STATUS_NEW, $ticket->refresh()->status);
        $this->assertDatabaseCount('ticket_history', 0);
    }

    public function test_owner_cannot_resolve_own_ticket(): void
    {
        $owner = $this->makeUser();
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->ticketWithAgent($owner, $agent, Ticket::STATUS_IN_PROGRESS);

        $this->actingAs($owner)
            ->patch(route('tickets.status', $ticket), ['status' => Ticket::STATUS_RESOLVED])
            ->assertSessionHasErrors('status');

        $this->assertSame(Ticket::STATUS_IN_PROGRESS, $ticket->refresh()->status);
    }

    public function test_status_change_is_recorded_in_history(): void
    {
        $owner = $this->makeUser();
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->ticketWithAgent($owner, $agent, Ticket::STATUS_IN_PROGRESS);

        $this->actingAs($agent)
            ->patch(route('tickets.status', $ticket), ['status' => Ticket::STATUS_RESOLVED])
            ->assertSessionHasNoErrors();

        $this->assertSame(Ticket::STATUS_RESOLVED, $ticket->refresh()->status);
        $this->assertDatabaseHas('ticket_history', [
            'ticket_id' => $ticket->id,
            'user_id' => $agent->id,
            'field' => 'status',
            'old_value' => 'W realizacji',
            'new_value' => 'Rozwiązane',
        ]);
    }

    public function test_priority_change_is_recorded_and_unchanged_category_is_not(): void
    {
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->ticketWithAgent($this->makeUser(), $agent, Ticket::STATUS_IN_PROGRESS);

        $this->actingAs($agent)
            ->patch(route('tickets.details', $ticket), [
                'priority' => Ticket::PRIORITY_CRITICAL,
                'category_id' => $ticket->category_id,
            ])
            ->assertSessionHasNoErrors();

        $this->assertSame(Ticket::PRIORITY_CRITICAL, $ticket->refresh()->priority);
        $this->assertDatabaseHas('ticket_history', [
            'ticket_id' => $ticket->id,
            'field' => 'priority',
            'old_value' => 'Średni',
            'new_value' => 'Krytyczny',
        ]);
        $this->assertDatabaseMissing('ticket_history', [
            'ticket_id' => $ticket->id,
            'field' => 'category',
        ]);
    }

    public function test_owner_reply_moves_waiting_ticket_back_to_in_progress(): void
    {
        $owner = $this->makeUser();
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->ticketWithAgent($owner, $agent, Ticket::STATUS_WAITING);

        $this->actingAs($owner)
            ->post(route('tickets.comments.store', $ticket), ['body' => 'Oto brakujące informacje.'])
            ->assertSessionHasNoErrors();

        $this->assertSame(Ticket::STATUS_IN_PROGRESS, $ticket->refresh()->status);
        $this->assertDatabaseHas('ticket_comments', [
            'ticket_id' => $ticket->id,
            'user_id' => $owner->id,
        ]);
        $this->assertDatabaseHas('ticket_history', [
            'ticket_id' => $ticket->id,
            'field' => 'status',
            'old_value' => 'Oczekuje na odpowiedź',
            'new_value' => 'W realizacji',
        ]);
    }

    public function test_owner_can_close_resolved_ticket(): void
    {
        $owner = $this->makeUser();
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->ticketWithAgent($owner, $agent, Ticket::STATUS_RESOLVED);

        $this->actingAs($owner)
            ->patch(route('tickets.status', $ticket), ['status' => Ticket::STATUS_CLOSED])
            ->assertSessionHasNoErrors();

        $ticket->refresh();

        $this->assertSame(Ticket::STATUS_CLOSED, $ticket->status);
        $this->assertNotNull($ticket->closed_at);
    }

    public function test_only_admin_can_reopen_closed_ticket(): void
    {
        $owner = $this->makeUser();
        $agent = $this->makeUser(User::ROLE_AGENT);
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $ticket = $this->ticketWithAgent($owner, $agent, Ticket::STATUS_CLOSED);
        $ticket->update(['closed_at' => now()]);

        $this->actingAs($owner)
            ->patch(route('tickets.status', $ticket), ['status' => Ticket::STATUS_IN_PROGRESS])
            ->assertSessionHasErrors('status');
        $this->actingAs($agent)
            ->patch(route('tickets.status', $ticket), ['status' => Ticket::STATUS_IN_PROGRESS])
            ->assertSessionHasErrors('status');
        $this->assertSame(Ticket::STATUS_CLOSED, $ticket->refresh()->status);

        $this->actingAs($admin)
            ->patch(route('tickets.status', $ticket), ['status' => Ticket::STATUS_IN_PROGRESS])
            ->assertSessionHasNoErrors();

        $ticket->refresh();

        $this->assertSame(Ticket::STATUS_IN_PROGRESS, $ticket->status);
        $this->assertNull($ticket->closed_at);
    }

    public function test_comments_on_closed_ticket_are_forbidden(): void
    {
        $owner = $this->makeUser();
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->ticketWithAgent($owner, $agent, Ticket::STATUS_CLOSED);

        $this->actingAs($owner)
            ->post(route('tickets.comments.store', $ticket), ['body' => 'Jeszcze jedno pytanie.'])
            ->assertForbidden();

        $this->assertDatabaseCount('ticket_comments', 0);
    }

    public function test_notifications_are_sent_to_the_right_people(): void
    {
        Notification::fake();

        $author = $this->makeUser();
        $agent = $this->makeUser(User::ROLE_AGENT);
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $ticket = $this->ticketWithAgent($author, $agent, Ticket::STATUS_IN_PROGRESS);

        // agent zmienia status: powiadomienie dla autora
        $this->actingAs($agent)
            ->patch(route('tickets.status', $ticket), ['status' => Ticket::STATUS_RESOLVED]);
        Notification::assertSentTo($author, TicketStatusChanged::class);

        // agent komentuje: powiadomienie dla autora
        $this->actingAs($agent)
            ->post(route('tickets.comments.store', $ticket), ['body' => 'Sprawdź proszę.']);
        Notification::assertSentTo($author, TicketCommentAdded::class);

        // administrator przypisuje innego technika: powiadomienie dla technika
        $newAgent = $this->makeUser(User::ROLE_AGENT);
        $this->actingAs($admin)
            ->post(route('tickets.assign', $ticket), ['agent_id' => $newAgent->id]);
        Notification::assertSentTo($newAgent, TicketAssigned::class);
    }
}