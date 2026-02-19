<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Competition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompetitionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test anyone can view all competitions (public)
     */
    public function test_anyone_can_view_all_competitions(): void
    {
        Competition::factory(5)->create();

        $response = $this->getJson('/api/competitions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['*' => ['id', 'title', 'description']],
                'pagination',
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    /**
     * Test anyone can view competition detail (public)
     */
    public function test_anyone_can_view_competition_detail(): void
    {
        $competition = Competition::factory()->create([
            'title' => 'Kompetisi Coding',
            'description' => 'Test competition',
        ]);

        $response = $this->getJson("/api/competitions/{$competition->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Kompetisi Coding')
            ->assertJsonPath('data.description', 'Test competition');
    }

    /**
     * Test view non-existent competition returns 404
     */
    public function test_view_nonexistent_competition_returns_404(): void
    {
        $response = $this->getJson('/api/competitions/999');

        $response->assertStatus(404)
            ->assertJsonPath('success', false);
    }

    /**
     * Test admin can create competition
     */
    public function test_admin_can_create_competition(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/competitions', [
                'title' => 'New Competition',
                'description' => 'Test description',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'New Competition');

        $this->assertDatabaseHas('competitions', [
            'title' => 'New Competition',
        ]);
    }

    /**
     * Test non-admin user cannot create competition
     */
    public function test_peserta_cannot_create_competition(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);

        $response = $this->actingAs($peserta, 'sanctum')
            ->postJson('/api/competitions', [
                'title' => 'New Competition',
                'description' => 'Test description',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test unauthenticated user cannot create competition
     */
    public function test_unauthenticated_user_cannot_create_competition(): void
    {
        $response = $this->postJson('/api/competitions', [
            'title' => 'New Competition',
            'description' => 'Test description',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test admin can update competition
     */
    public function test_admin_can_update_competition(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $competition = Competition::factory()->create([
            'title' => 'Old Title',
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->putJson("/api/competitions/{$competition->id}", [
                'title' => 'Updated Title',
                'description' => 'Updated description',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated Title');

        $this->assertDatabaseHas('competitions', [
            'id' => $competition->id,
            'title' => 'Updated Title',
        ]);
    }

    /**
     * Test peserta cannot update competition
     */
    public function test_peserta_cannot_update_competition(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $competition = Competition::factory()->create();

        $response = $this->actingAs($peserta, 'sanctum')
            ->putJson("/api/competitions/{$competition->id}", [
                'title' => 'Updated Title',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test admin can delete competition
     */
    public function test_admin_can_delete_competition(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $competition = Competition::factory()->create();

        $response = $this->actingAs($admin, 'sanctum')
            ->deleteJson("/api/competitions/{$competition->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('competitions', [
            'id' => $competition->id,
        ]);
    }

    /**
     * Test peserta cannot delete competition
     */
    public function test_peserta_cannot_delete_competition(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);
        $competition = Competition::factory()->create();

        $response = $this->actingAs($peserta, 'sanctum')
            ->deleteJson("/api/competitions/{$competition->id}");

        $response->assertStatus(403);
    }

    /**
     * Test competition creation validation
     */
    public function test_competition_creation_requires_title_and_description(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin, 'sanctum')
            ->postJson('/api/competitions', []);

        $response->assertStatus(422)
            ->assertJsonStructure(['errors']);
    }

    /**
     * Test competitions pagination
     */
    public function test_competitions_pagination(): void
    {
        Competition::factory(30)->create();

        $response = $this->getJson('/api/competitions?per_page=10');

        $response->assertStatus(200)
            ->assertJsonPath('pagination.per_page', 10)
            ->assertJsonPath('pagination.total', 30);

        $this->assertCount(10, $response->json('data'));
    }
}
