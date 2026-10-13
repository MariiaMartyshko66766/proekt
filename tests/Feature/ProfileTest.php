<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get('/profile')->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Nowe Imię',
                'email' => 'nowy@example.com',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Nowe Imię', $user->name);
        $this->assertSame('nowy@example.com', $user->email);
    }
}