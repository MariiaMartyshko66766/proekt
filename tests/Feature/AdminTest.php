<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_with_tickets_cannot_be_deleted(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $category = $this->makeCategory('Sprzęt');
        $this->makeTicket($this->makeUser(), ['category_id' => $category->id]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHasErrors('category');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_empty_category_can_be_deleted(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $category = $this->makeCategory('Telefony');

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_name_must_be_unique(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $this->makeCategory('Sprzęt');

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), ['name' => 'Sprzęt'])
            ->assertSessionHasErrors('name');

        $this->assertSame(1, Category::count());
    }

    public function test_non_admin_cannot_add_category(): void
    {
        $agent = $this->makeUser(User::ROLE_AGENT);

        $this->actingAs($agent)
            ->post(route('admin.categories.store'), ['name' => 'Nowa'])
            ->assertForbidden();

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_admin_can_change_role_and_block_user(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);
        $user = $this->makeUser();

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $user), ['role' => 'agent', 'is_active' => 0])
            ->assertSessionHasNoErrors();

        $user->refresh();

        $this->assertSame(User::ROLE_AGENT, $user->role);
        $this->assertFalse($user->is_active);
    }

    public function test_admin_cannot_change_own_role_or_block_self(): void
    {
        $admin = $this->makeUser(User::ROLE_ADMIN);

        $this->actingAs($admin)
            ->patch(route('admin.users.update', $admin), ['role' => 'user', 'is_active' => 0])
            ->assertSessionHasErrors('user');

        $admin->refresh();

        $this->assertSame(User::ROLE_ADMIN, $admin->role);
        $this->assertTrue($admin->is_active);
    }

    public function test_user_blocked_during_session_is_logged_out(): void
    {
        $user = $this->makeUser(User::ROLE_USER, ['is_active' => false]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}