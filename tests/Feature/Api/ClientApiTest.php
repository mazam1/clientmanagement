<?php

namespace Tests\Feature\Api;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_can_list_clients(): void
    {
        Client::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/clients');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_can_filter_clients_by_status(): void
    {
        Client::factory()->count(3)->create(['status' => 'active']);
        Client::factory()->count(2)->create(['status' => 'inactive']);

        $response = $this->getJson('/api/v1/clients?status=active');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_search_clients(): void
    {
        Client::factory()->create(['name' => 'John Doe']);
        Client::factory()->create(['name' => 'Jane Smith']);

        $response = $this->getJson('/api/v1/clients?search=John');

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.name', 'John Doe');
    }

    public function test_can_show_single_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->getJson("/api/v1/clients/{$client->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'status',
                'created_at',
                'updated_at',
            ],
        ]);
        $response->assertJsonPath('data.id', $client->id);
    }

    public function test_can_create_client(): void
    {
        $clientData = [
            'name' => 'New Client',
            'email' => 'newclient@example.com',
            'phone' => '1234567890',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/v1/clients', $clientData);

        $response->assertStatus(201);
        $response->assertJsonPath('data.name', 'New Client');
        $this->assertDatabaseHas('clients', ['email' => 'newclient@example.com']);
    }

    public function test_client_creation_validates_required_fields(): void
    {
        $response = $this->postJson('/api/v1/clients', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'status']);
    }

    public function test_can_update_client(): void
    {
        $client = Client::factory()->create(['name' => 'Old Name']);

        $updateData = [
            'name' => 'Updated Name',
            'email' => $client->email,
            'phone' => $client->phone,
            'status' => $client->status,
        ];

        $response = $this->putJson("/api/v1/clients/{$client->id}", $updateData);

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Updated Name');
        $this->assertDatabaseHas('clients', ['id' => $client->id, 'name' => 'Updated Name']);
    }

    public function test_can_delete_client(): void
    {
        $client = Client::factory()->create();

        $response = $this->deleteJson("/api/v1/clients/{$client->id}");

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Client deleted successfully']);
        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }

    public function test_can_get_client_stats(): void
    {
        Client::factory()->count(3)->create(['status' => 'active']);
        Client::factory()->count(2)->create(['status' => 'inactive']);

        $response = $this->getJson('/api/v1/clients-stats');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total',
            'active',
            'inactive',
            'archived',
        ]);
        $response->assertJsonPath('total', 5);
        $response->assertJsonPath('active', 3);
    }

    public function test_returns_404_for_nonexistent_client(): void
    {
        $response = $this->getJson('/api/v1/clients/999999');

        $response->assertStatus(404);
    }
}
