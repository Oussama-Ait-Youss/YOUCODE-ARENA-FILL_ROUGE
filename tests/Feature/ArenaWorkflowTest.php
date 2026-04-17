<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArenaWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_competitor_cannot_publish_in_the_hub(): void
    {
        $competitor = $this->createUserWithRole('Compétiteur');

        $response = $this->actingAs($competitor)->post(route('competitor.feed.store'), [
            'content' => 'Je veux publier dans le hub.',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_organizer_can_publish_in_the_hub(): void
    {
        $organizer = $this->createUserWithRole('Organisateur');

        $response = $this->actingAs($organizer)->post(route('competitor.feed.store'), [
            'content' => 'Annonce officielle du tournoi.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'author_id' => $organizer->id,
            'content' => 'Annonce officielle du tournoi.',
        ]);
    }

    public function test_admin_can_reassign_a_user_role(): void
    {
        $admin = $this->createUserWithRole('Admin');
        $competitor = $this->createUserWithRole('Compétiteur', 'target-player');
        Role::firstOrCreate(['name' => 'Organisateur']);

        $response = $this->actingAs($admin)->put(route('admin.users.change_role', $competitor), [
            'role' => 'Organisateur',
        ]);

        $response->assertRedirect();

        $this->assertSame('Organisateur', $competitor->fresh()->primaryRoleName());
    }

    private function createUserWithRole(string $roleName, ?string $username = null): User
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        $emailPrefix = $username
            ? preg_replace('/[^a-z0-9]+/i', '-', $username)
            : match ($roleName) {
                'Compétiteur' => 'competitor',
                'Organisateur' => 'organizer',
                default => strtolower($roleName),
            };

        $user = User::factory()->create([
            'username' => $username ?? strtolower($roleName) . '_user',
            'email' => trim($emailPrefix ?? 'arena-user', '-') . '@arena.test',
        ]);

        $user->roles()->attach($role->id);

        return $user;
    }
}
