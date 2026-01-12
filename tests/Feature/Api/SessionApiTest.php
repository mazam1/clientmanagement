<?php

namespace Tests\Feature\Api;

use App\Models\Client;
use App\Models\ClientSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_can_list_sessions(): void
    {
        $client = Client::factory()->create();
        ClientSession::factory()->count(5)->create(['client_id' => $client->id]);

        $response = $this->getJson('/api/v1/sessions');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'client_id',
                    'session_date',
                    'duration_minutes',
                    'duration_hours',
                    'notes',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_can_filter_sessions_by_client(): void
    {
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        ClientSession::factory()->count(3)->create(['client_id' => $client1->id]);
        ClientSession::factory()->count(2)->create(['client_id' => $client2->id]);

        $response = $this->getJson("/api/v1/sessions?client_id={$client1->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_filter_sessions_by_date_range(): void
    {
        $client = Client::factory()->create();
        ClientSession::factory()->create([
            'client_id' => $client->id,
            'session_date' => '2024-01-15',
        ]);
        ClientSession::factory()->create([
            'client_id' => $client->id,
            'session_date' => '2024-02-15',
        ]);
        ClientSession::factory()->create([
            'client_id' => $client->id,
            'session_date' => '2024-03-15',
        ]);

        $response = $this->getJson('/api/v1/sessions?date_from=2024-02-01&date_to=2024-02-28');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_can_show_single_session(): void
    {
        $client = Client::factory()->create();
        $session = ClientSession::factory()->create(['client_id' => $client->id]);

        $response = $this->getJson("/api/v1/sessions/{$session->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'client_id',
                'session_date',
                'duration_minutes',
                'duration_hours',
                'notes',
                'created_at',
                'updated_at',
            ],
        ]);
        $response->assertJsonPath('data.id', $session->id);
    }

    public function test_can_create_session(): void
    {
        $client = Client::factory()->create();

        $sessionData = [
            'client_id' => $client->id,
            'session_date' => '2024-03-15 10:00:00',
            'duration_minutes' => 120,
            'notes' => 'Test session notes',
        ];

        $response = $this->postJson('/api/v1/sessions', $sessionData);

        $response->assertStatus(201);
        $response->assertJsonPath('data.duration_minutes', 120);
        $this->assertDatabaseHas('client_sessions', [
            'client_id' => $client->id,
            'duration_minutes' => 120,
        ]);
    }

    public function test_session_creation_validates_required_fields(): void
    {
        $response = $this->postJson('/api/v1/sessions', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['client_id', 'session_date', 'duration_minutes']);
    }

    public function test_can_update_session(): void
    {
        $client = Client::factory()->create();
        $session = ClientSession::factory()->create([
            'client_id' => $client->id,
            'duration_minutes' => 60,
        ]);

        $updateData = [
            'client_id' => $client->id,
            'session_date' => $session->session_date->format('Y-m-d H:i:s'),
            'duration_minutes' => 90,
            'notes' => 'Updated notes',
        ];

        $response = $this->putJson("/api/v1/sessions/{$session->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJsonPath('data.duration_minutes', 90);
        $this->assertDatabaseHas('client_sessions', [
            'id' => $session->id,
            'duration_minutes' => 90,
        ]);
    }

    public function test_can_delete_session(): void
    {
        $client = Client::factory()->create();
        $session = ClientSession::factory()->create(['client_id' => $client->id]);

        $response = $this->deleteJson("/api/v1/sessions/{$session->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Session deleted successfully']);
        $this->assertDatabaseMissing('client_sessions', ['id' => $session->id]);
    }

    public function test_can_get_session_stats(): void
    {
        $client = Client::factory()->create();
        ClientSession::factory()->count(3)->create([
            'client_id' => $client->id,
            'session_date' => now()->addDays(1),
            'duration_minutes' => 60,
        ]);

        $response = $this->getJson('/api/v1/sessions-stats');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_sessions',
            'upcoming_sessions',
            'total_hours',
            'upcoming_hours',
        ]);
    }

    public function test_can_get_upcoming_sessions(): void
    {
        $client = Client::factory()->create();

        // Create upcoming sessions
        ClientSession::factory()->count(2)->create([
            'client_id' => $client->id,
            'session_date' => now()->addDays(2),
        ]);

        // Create past sessions
        ClientSession::factory()->count(3)->create([
            'client_id' => $client->id,
            'session_date' => now()->subDays(2),
        ]);

        $response = $this->getJson('/api/v1/sessions-upcoming?days=7');

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function test_returns_404_for_nonexistent_session(): void
    {
        $response = $this->getJson('/api/v1/sessions/999999');

        $response->assertStatus(404);
    }
}
