<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Role;
use App\Models\Category;
use App\Models\Game;
use App\Models\Registration;
use App\Models\Team;
use App\Models\Tournament;
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

    public function test_organizer_can_create_open_edit_and_update_own_tournament(): void
    {
        $organizer = $this->createUserWithRole('Organisateur');
        $game = Game::create(['name' => 'Chess']);
        $category = Category::create(['name' => 'Solo']);

        $createResponse = $this->actingAs($organizer)->post(route('organizer.tournaments.store'), [
            'title' => 'Local Arena Cup',
            'game_id' => $game->id,
            'category_id' => $category->id,
            'status' => 'À venir',
            'max_capacity' => 16,
            'event_date' => now()->addWeek()->format('Y-m-d H:i:s'),
        ]);

        $createResponse->assertRedirect(route('organizer.dashboard'));
        $tournament = Tournament::where('title', 'Local Arena Cup')->firstOrFail();

        $this->actingAs($organizer)
            ->get(route('organizer.tournaments.edit', $tournament))
            ->assertOk()
            ->assertSee('Local Arena Cup');

        $updateResponse = $this->actingAs($organizer)->put(route('organizer.tournaments.update', $tournament), [
            'title' => 'Local Arena Cup Updated',
            'game_id' => $game->id,
            'category_id' => $category->id,
            'status' => 'Ouvert',
            'max_capacity' => 20,
            'event_date' => now()->addWeeks(2)->format('Y-m-d H:i:s'),
        ]);

        $updateResponse->assertRedirect(route('organizer.tournaments.index'));
        $this->assertDatabaseHas('tournaments', [
            'id' => $tournament->id,
            'title' => 'Local Arena Cup Updated',
            'status' => 'Ouvert',
            'max_capacity' => 20,
        ]);
    }

    public function test_profile_shows_solo_pending_registration_as_approval_not_invitation(): void
    {
        $competitor = $this->createUserWithRole('Compétiteur');
        $organizer = $this->createUserWithRole('Organisateur', 'solo-organizer');
        $game = Game::create(['name' => 'Chess']);
        $category = Category::create(['name' => 'Solo']);
        $tournament = Tournament::create([
            'organizer_id' => $organizer->id,
            'game_id' => $game->id,
            'category_id' => $category->id,
            'title' => 'Solo Pending Cup',
            'status' => 'Ouvert',
            'max_capacity' => 16,
            'event_date' => now()->addWeek(),
        ]);
        $team = Team::create([
            'tournament_id' => $tournament->id,
            'name' => 'Solo Player Team',
        ]);

        $team->members()->attach($competitor->id, ['joined_at' => now()]);
        Registration::create([
            'user_id' => $competitor->id,
            'tournament_id' => $tournament->id,
            'team_id' => $team->id,
            'status' => 'En attente',
            'registration_date' => now(),
        ]);

        $this->actingAs($competitor)
            ->get(route('competitor.profile'))
            ->assertOk()
            ->assertSee('Inscriptions en attente')
            ->assertSee('Validation organisateur')
            ->assertDontSee('Invitations Reçues')
            ->assertDontSee('Accepter');
    }

    public function test_admin_can_open_tournament_registrations_and_accept_pending_user(): void
    {
        $admin = $this->createUserWithRole('Admin');
        $organizer = $this->createUserWithRole('Organisateur', 'approval-organizer');
        $competitor = $this->createUserWithRole('Compétiteur', 'pending-player');
        $game = Game::create(['name' => 'Chess']);
        $category = Category::create(['name' => 'Solo']);
        $tournament = Tournament::create([
            'organizer_id' => $organizer->id,
            'game_id' => $game->id,
            'category_id' => $category->id,
            'title' => 'Approval Cup',
            'status' => 'Ouvert',
            'max_capacity' => 16,
            'event_date' => now()->addWeek(),
        ]);
        $team = Team::create([
            'tournament_id' => $tournament->id,
            'name' => 'Pending Solo',
        ]);
        $team->members()->attach($competitor->id, ['joined_at' => now()]);
        $registration = Registration::create([
            'user_id' => $competitor->id,
            'tournament_id' => $tournament->id,
            'team_id' => $team->id,
            'status' => 'En attente',
            'registration_date' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('organizer.tournaments.show', $tournament))
            ->assertOk()
            ->assertSee('Demandes à valider')
            ->assertSee('pending-player');

        $this->actingAs($admin)
            ->patch(route('organizer.tournaments.participants.accept', [$tournament, $registration]))
            ->assertRedirect();

        $this->assertSame('Confirmé', $registration->fresh()->status);
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
