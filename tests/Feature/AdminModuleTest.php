<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Game;
use App\Models\Role;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_banned_user_is_blocked_from_protected_dashboard(): void
    {
        $bannedUser = $this->createUserWithRole('Compétiteur', 'banned-player', [
            'is_banned' => true,
        ]);

        $response = $this->actingAs($bannedUser)->get(route('dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_non_admin_cannot_access_admin_users_page(): void
    {
        $competitor = $this->createUserWithRole('Compétiteur', 'simple-player');

        $response = $this->actingAs($competitor)->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_filter_and_search_users(): void
    {
        $admin = $this->createUserWithRole('Admin', 'chief-admin');
        $this->createUserWithRole('Organisateur', 'alpha-organizer');
        $this->createUserWithRole('Compétiteur', 'blocked-player', [
            'is_banned' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.index', [
            'q' => 'alpha',
            'role' => 'Organisateur',
            'status' => 'active',
            'per_page' => 10,
        ]));

        $response->assertOk();
        $response->assertSee('alpha-organizer');
        $response->assertDontSee('blocked-player');
    }

    public function test_admin_can_view_a_user_detail_page(): void
    {
        $admin = $this->createUserWithRole('Admin', 'chief-admin');
        $organizer = $this->createUserWithRole('Organisateur', 'focus-organizer', [
            'last_login' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.show', $organizer));

        $response->assertOk();
        $response->assertSee('focus-organizer');
        $response->assertSee($organizer->email);
    }

    public function test_admin_cannot_ban_their_own_account(): void
    {
        $admin = $this->createUserWithRole('Admin', 'chief-admin');

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle_ban', $admin));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertFalse($admin->fresh()->is_banned);
    }

    public function test_admin_can_ban_and_unban_a_user(): void
    {
        $admin = $this->createUserWithRole('Admin', 'chief-admin');
        $competitor = $this->createUserWithRole('Compétiteur', 'ban-target');

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle_ban', $competitor))
            ->assertRedirect();

        $this->assertTrue($competitor->fresh()->is_banned);

        $this->actingAs($admin)
            ->patch(route('admin.users.toggle_ban', $competitor))
            ->assertRedirect();

        $this->assertFalse($competitor->fresh()->is_banned);
    }

    public function test_admin_can_create_and_update_a_tournament_from_admin_panel(): void
    {
        $admin = $this->createUserWithRole('Admin', 'chief-admin');
        $organizer = $this->createUserWithRole('Organisateur', 'arena-organizer');
        $game = Game::create(['name' => 'Chess']);
        $category = Category::create(['name' => 'Solo']);

        $createResponse = $this->actingAs($admin)->post(route('admin.tournaments.store'), [
            'title' => 'Arena Masters',
            'game_id' => $game->id,
            'category_id' => $category->id,
            'organizer_id' => $organizer->id,
            'status' => 'Ouvert',
            'max_capacity' => 16,
            'event_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
        ]);

        $createResponse->assertRedirect(route('admin.tournaments.index'));

        $tournament = Tournament::firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.tournaments.edit', $tournament))
            ->assertOk()
            ->assertSee('Arena Masters');

        $updateResponse = $this->actingAs($admin)->put(route('admin.tournaments.update', $tournament), [
            'title' => 'Arena Masters Reloaded',
            'game_id' => $game->id,
            'category_id' => $category->id,
            'organizer_id' => $organizer->id,
            'status' => 'Fermé',
            'max_capacity' => 32,
            'event_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
        ]);

        $updateResponse->assertRedirect(route('admin.tournaments.index'));
        $this->assertDatabaseHas('tournaments', [
            'id' => $tournament->id,
            'title' => 'Arena Masters Reloaded',
            'status' => 'Fermé',
            'organizer_id' => $organizer->id,
            'max_capacity' => 32,
        ]);
    }

    private function createUserWithRole(string $roleName, ?string $username = null, array $attributes = []): User
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        $emailPrefix = $username
            ? preg_replace('/[^a-z0-9]+/i', '-', $username)
            : match ($roleName) {
                'Compétiteur' => 'competitor',
                'Organisateur' => 'organizer',
                default => strtolower($roleName),
            };

        $user = User::factory()->create(array_merge([
            'username' => $username ?? strtolower($roleName) . '_user',
            'email' => trim($emailPrefix ?? 'arena-user', '-') . '@arena.test',
        ], $attributes));

        $user->roles()->attach($role->id);

        return $user;
    }
}
