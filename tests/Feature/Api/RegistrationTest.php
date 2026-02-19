<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Competition;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can view their registrations
     */
    public function test_user_can_view_their_registrations(): void
    {
        $user = User::factory()->create();
        $competition = Competition::factory()->create();
        
        Registration::factory()->create([
            'user_id' => $user->id,
            'competition_id' => $competition->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/registrations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['*' => ['id', 'competition_id', 'status']],
            ]);

        $this->assertCount(1, $response->json('data'));
    }

    /**
     * Test user can view registration detail
     */
    public function test_user_can_view_registration_detail(): void
    {
        $user = User::factory()->create();
        $competition = Competition::factory()->create();
        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'competition_id' => $competition->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson("/api/registrations/{$registration->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $registration->id)
            ->assertJsonPath('data.status', 'submitted');
    }

    /**
     * Test user cannot view other user's registration
     */
    public function test_user_cannot_view_other_users_registration(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $competition = Competition::factory()->create();
        $registration = Registration::factory()->create([
            'user_id' => $user2->id,
            'competition_id' => $competition->id,
        ]);

        $response = $this->actingAs($user1, 'sanctum')
            ->getJson("/api/registrations/{$registration->id}");

        $response->assertStatus(403);
    }

    /**
     * Test admin can view other user's registration
     */
    public function test_admin_can_view_other_users_registration(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $competition = Competition::factory()->create();
        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'competition_id' => $competition->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/registrations/{$registration->id}");

        $response->assertStatus(200);
    }

    /**
     * Test user can register to competition
     */
    public function test_user_can_register_to_competition(): void
    {
        $user = User::factory()->create();
        $competition = Competition::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/registrations', [
                'competition_id' => $competition->id,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.competition_id', $competition->id)
            ->assertJsonPath('data.status', 'submitted');

        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'competition_id' => $competition->id,
        ]);
    }

    /**
     * Test user cannot register to same competition twice
     */
    public function test_user_cannot_register_to_same_competition_twice(): void
    {
        $user = User::factory()->create();
        $competition = Competition::factory()->create();

        // First registration
        $this->actingAs($user, 'sanctum')
            ->postJson('/api/registrations', [
                'competition_id' => $competition->id,
            ]);

        // Second registration attempt
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/registrations', [
                'competition_id' => $competition->id,
            ]);

        $response->assertStatus(409)
            ->assertJsonPath('error', 'ALREADY_REGISTERED');
    }

    /**
     * Test registration to non-existent competition fails
     */
    public function test_registration_to_nonexistent_competition_fails(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/registrations', [
                'competition_id' => 999,
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test unauthenticated user cannot register
     */
    public function test_unauthenticated_user_cannot_register(): void
    {
        $competition = Competition::factory()->create();

        $response = $this->postJson('/api/registrations', [
            'competition_id' => $competition->id,
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test user can cancel their registration
     */
    public function test_user_can_cancel_registration(): void
    {
        $user = User::factory()->create();
        $competition = Competition::factory()->create();
        $registration = Registration::factory()->create([
            'user_id' => $user->id,
            'competition_id' => $competition->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/registrations/{$registration->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('registrations', [
            'id' => $registration->id,
        ]);
    }

    /**
     * Test user cannot cancel other user's registration
     */
    public function test_user_cannot_cancel_other_users_registration(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $competition = Competition::factory()->create();
        $registration = Registration::factory()->create([
            'user_id' => $user2->id,
            'competition_id' => $competition->id,
        ]);

        $response = $this->actingAs($user1, 'sanctum')
            ->deleteJson("/api/registrations/{$registration->id}");

        $response->assertStatus(403);
    }

    /**
     * Test admin can view all registrations
     */
    public function test_admin_can_view_all_registrations(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $competition = Competition::factory()->create();
        
        Registration::factory(5)->create([
            'competition_id' => $competition->id,
        ]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/registrations');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['*' => ['id', 'user', 'competition', 'status']],
                'pagination',
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    /**
     * Test peserta cannot view all registrations
     */
    public function test_peserta_cannot_view_all_registrations(): void
    {
        $peserta = User::factory()->create(['role' => 'peserta']);

        $response = $this->actingAs($peserta, 'sanctum')
            ->getJson('/api/admin/registrations');

        $response->assertStatus(403);
    }

    /**
     * Test admin can filter registrations by competition
     */
    public function test_admin_can_filter_registrations_by_competition(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $competition1 = Competition::factory()->create();
        $competition2 = Competition::factory()->create();
        
        Registration::factory(3)->create(['competition_id' => $competition1->id]);
        Registration::factory(2)->create(['competition_id' => $competition2->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson("/api/admin/registrations?competition_id={$competition1->id}");

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    /**
     * Test registrations pagination
     */
    public function test_registrations_pagination(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $competition = Competition::factory()->create();
        
        Registration::factory(30)->create(['competition_id' => $competition->id]);

        $response = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/admin/registrations?per_page=10');

        $response->assertStatus(200)
            ->assertJsonPath('pagination.per_page', 10)
            ->assertJsonPath('pagination.total', 30);
    }
}
