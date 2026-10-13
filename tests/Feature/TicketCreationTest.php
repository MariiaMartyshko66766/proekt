<?php

namespace Tests\Feature;

use App\Models\Attachment;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketCreationTest extends TestCase
{
    use RefreshDatabase;

    private function validData(int $categoryId, array $overrides = []): array
    {
        return array_merge([
            'title' => 'Nie działa drukarka',
            'description' => 'Drukarka nie drukuje od rana.',
            'category_id' => $categoryId,
            'priority' => 'Wysoki',
        ], $overrides);
    }

    public function test_user_can_create_ticket_with_status_new(): void
    {
        $user = $this->makeUser();
        $category = $this->makeCategory();

        $response = $this->actingAs($user)
            ->post(route('tickets.store'), $this->validData($category->id));

        $ticket = Ticket::firstOrFail();

        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertSame(Ticket::STATUS_NEW, $ticket->status);
        $this->assertSame('Wysoki', $ticket->priority);
        $this->assertSame($user->id, $ticket->user_id);
        $this->assertNull($ticket->agent_id);
    }

    public function test_required_fields_are_validated(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->post(route('tickets.store'), [])
            ->assertSessionHasErrors(['title', 'description', 'category_id', 'priority']);

        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_title_length_and_priority_value_are_validated(): void
    {
        $user = $this->makeUser();
        $category = $this->makeCategory();

        $this->actingAs($user)
            ->post(route('tickets.store'), $this->validData($category->id, [
                'title' => str_repeat('a', 151),
                'priority' => 'Pilny',
            ]))
            ->assertSessionHasErrors(['title', 'priority']);

        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_valid_attachment_is_saved(): void
    {
        Storage::fake('local');

        $user = $this->makeUser();
        $category = $this->makeCategory();

        $this->actingAs($user)->post(route('tickets.store'), $this->validData($category->id, [
            'attachment' => UploadedFile::fake()->create('instrukcja.pdf', 200, 'application/pdf'),
        ]))->assertSessionHasNoErrors();

        $attachment = Attachment::firstOrFail();

        $this->assertSame('instrukcja.pdf', $attachment->original_name);
        Storage::disk('local')->assertExists($attachment->path);
    }

    public function test_invalid_attachment_type_is_rejected(): void
    {
        Storage::fake('local');

        $user = $this->makeUser();
        $category = $this->makeCategory();

        $this->actingAs($user)->post(route('tickets.store'), $this->validData($category->id, [
            'attachment' => UploadedFile::fake()->create('notatki.txt', 10, 'text/plain'),
        ]))->assertSessionHasErrors('attachment');

        $this->assertDatabaseCount('tickets', 0);
        $this->assertDatabaseCount('attachments', 0);
    }

    public function test_too_large_attachment_is_rejected(): void
    {
        Storage::fake('local');

        $user = $this->makeUser();
        $category = $this->makeCategory();

        $this->actingAs($user)->post(route('tickets.store'), $this->validData($category->id, [
            'attachment' => UploadedFile::fake()->create('duzy.pdf', 6000, 'application/pdf'),
        ]))->assertSessionHasErrors('attachment');

        $this->assertDatabaseCount('tickets', 0);
    }
}