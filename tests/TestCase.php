<?php

namespace Tests;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    protected function makeUser(string $role = User::ROLE_USER, array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => $role,
            'is_active' => true,
        ], $attributes));
    }

    protected function makeCategory(?string $name = null): Category
    {
        return Category::create([
            'name' => $name ?? 'Kategoria ' . Str::random(8),
            'description' => 'Opis kategorii',
        ]);
    }

    protected function makeTicket(User $author, array $attributes = []): Ticket
    {
        return Ticket::create(array_merge([
            'user_id' => $author->id,
            'category_id' => $this->makeCategory()->id,
            'title' => 'Zgłoszenie testowe',
            'description' => 'Opis zgłoszenia testowego.',
            'priority' => Ticket::PRIORITY_MEDIUM,
            'status' => Ticket::STATUS_NEW,
        ], $attributes));
    }
}