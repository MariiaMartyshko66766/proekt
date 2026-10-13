<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/tickets')->assertRedirect('/login');
    }

    public function test_active_user_can_log_in(): void
    {
        $user = $this->makeUser(User::ROLE_USER, ['password' => 'haslo1234']);

        $this->post('/login', ['email' => $user->email, 'password' => 'haslo1234']);

        $this->assertAuthenticatedAs($user);
    }

    public function test_blocked_user_cannot_log_in(): void
    {
        $user = $this->makeUser(User::ROLE_USER, [
            'password' => 'haslo1234',
            'is_active' => false,
        ]);

        $this->post('/login', ['email' => $user->email, 'password' => 'haslo1234'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_cannot_view_other_users_ticket(): void
    {
        $owner = $this->makeUser();
        $stranger = $this->makeUser();
        $ticket = $this->makeTicket($owner);

        $this->actingAs($stranger)->get(route('tickets.show', $ticket))->assertForbidden();
        $this->actingAs($owner)->get(route('tickets.show', $ticket))->assertOk();
    }

    public function test_user_sees_only_own_tickets_in_list(): void
    {
        $owner = $this->makeUser();
        $other = $this->makeUser();
        $this->makeTicket($owner, ['title' => 'Moje zgłoszenie']);
        $this->makeTicket($other, ['title' => 'Cudze zgłoszenie']);

        $this->actingAs($owner)->get(route('tickets.index'))
            ->assertOk()
            ->assertSee('Moje zgłoszenie')
            ->assertDontSee('Cudze zgłoszenie');
    }

    public function test_agent_can_view_any_ticket(): void
    {
        $agent = $this->makeUser(User::ROLE_AGENT);
        $ticket = $this->makeTicket($this->makeUser());

        $this->actingAs($agent)->get(route('tickets.show', $ticket))->assertOk();
    }

    public function test_agent_cannot_create_ticket(): void
    {
        $agent = $this->makeUser(User::ROLE_AGENT);
        $category = $this->makeCategory();

        $this->actingAs($agent)->get(route('tickets.create'))->assertForbidden();

        $this->actingAs($agent)->post(route('tickets.store'), [
            'title' => 'Próba technika',
            'description' => 'Technik nie może tworzyć zgłoszeń.',
            'category_id' => $category->id,
            'priority' => 'Średni',
        ])->assertForbidden();

        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_non_admin_cannot_access_admin_pages(): void
    {
        foreach ([User::ROLE_USER, User::ROLE_AGENT] as $role) {
            $user = $this->makeUser($role);

            $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
            $this->actingAs($user)->get(route('admin.categories.index'))->assertForbidden();
        }

        $admin = $this->makeUser(User::ROLE_ADMIN);

        $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
        $this->actingAs($admin)->get(route('admin.categories.index'))->assertOk();
    }

    public function test_attachment_download_is_limited_to_people_who_can_see_the_ticket(): void
    {
        Storage::fake('local');

        $owner = $this->makeUser();
        $stranger = $this->makeUser();
        $ticket = $this->makeTicket($owner);

        Storage::disk('local')->put('attachments/test.pdf', 'zawartość pliku');
        $attachment = $ticket->attachments()->create([
            'original_name' => 'test.pdf',
            'path' => 'attachments/test.pdf',
            'mime' => 'application/pdf',
            'size' => 15,
        ]);

        $this->actingAs($stranger)->get(route('attachments.download', $attachment))->assertForbidden();
        $this->actingAs($owner)->get(route('attachments.download', $attachment))->assertOk();
    }

    public function test_dashboard_counts_only_own_tickets_for_regular_user(): void
    {
        $user = $this->makeUser();
        $other = $this->makeUser();
        $admin = $this->makeUser(User::ROLE_ADMIN);

        $this->makeTicket($user);
        $this->makeTicket($user, ['status' => Ticket::STATUS_CLOSED]);
        $this->makeTicket($other);

        $userView = $this->actingAs($user)->get(route('dashboard'));
        $userView->assertOk();
        $this->assertSame(2, $userView->viewData('total'));
        $this->assertSame(1, $userView->viewData('statusCounts')['Nowe']);

        $adminView = $this->actingAs($admin)->get(route('dashboard'));
        $this->assertSame(3, $adminView->viewData('total'));
    }
}