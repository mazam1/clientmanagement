<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientSession;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_search_requires_authentication(): void
    {
        $response = $this->getJson(route('search', ['query' => 'test']));

        $response->assertStatus(401);
    }

    public function test_search_returns_empty_array_for_short_query(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'a']));

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_search_finds_clients_by_name(): void
    {
        $client = Client::factory()->create(['name' => 'Crooks-Kling']);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'Crooks']));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'type' => 'client',
            'title' => 'Crooks-Kling',
        ]);
    }

    public function test_search_finds_clients_by_email(): void
    {
        $client = Client::factory()->create(['email' => 'test@crooks-kling.com']);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'crooks-kling']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'client',
        ]);
    }

    public function test_search_finds_clients_by_phone(): void
    {
        $client = Client::factory()->create(['phone' => '555-1234']);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => '555']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'client',
        ]);
    }

    public function test_search_finds_sessions_by_client_name(): void
    {
        $client = Client::factory()->create(['name' => 'Crooks-Kling']);
        $session = ClientSession::factory()->create(['client_id' => $client->id]);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'Crooks']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'session',
        ]);
        $response->assertJsonFragment([
            'title' => 'Crooks-Kling - Session',
        ]);
    }

    public function test_search_finds_sessions_by_notes(): void
    {
        $client = Client::factory()->create();
        $session = ClientSession::factory()->create([
            'client_id' => $client->id,
            'notes' => 'Discussion about marketing strategy',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'marketing']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'session',
        ]);
    }

    public function test_search_finds_invoices_by_invoice_number(): void
    {
        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create([
            'client_id' => $client->id,
            'invoice_number' => 'INV-2025-001',
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'INV-2025']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'invoice',
            'title' => 'INV-2025-001',
        ]);
    }

    public function test_search_finds_invoices_by_client_name(): void
    {
        $client = Client::factory()->create(['name' => 'Crooks-Kling']);
        $invoice = Invoice::factory()->create(['client_id' => $client->id]);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'Crooks']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'type' => 'invoice',
        ]);
    }

    public function test_search_returns_multiple_result_types(): void
    {
        $client = Client::factory()->create(['name' => 'Crooks-Kling']);
        $session = ClientSession::factory()->create(['client_id' => $client->id]);
        $invoice = Invoice::factory()->create(['client_id' => $client->id]);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'Crooks']));

        $response->assertStatus(200);

        $data = $response->json();

        // Should find at least the client, session, and invoice
        $this->assertGreaterThanOrEqual(3, count($data));

        // Check that we have all three types
        $types = collect($data)->pluck('type')->unique()->toArray();
        $this->assertContains('client', $types);
        $this->assertContains('session', $types);
        $this->assertContains('invoice', $types);
    }

    public function test_search_limits_total_results_to_10(): void
    {
        $client = Client::factory()->create(['name' => 'Test Client']);

        // Create many records
        Client::factory()->count(10)->create(['name' => 'Test Match']);
        ClientSession::factory()->count(10)->create([
            'client_id' => $client->id,
            'notes' => 'Test notes',
        ]);
        Invoice::factory()->count(10)->create(['client_id' => $client->id]);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'Test']));

        $response->assertStatus(200);

        $data = $response->json();
        $this->assertLessThanOrEqual(10, count($data));
    }

    public function test_search_returns_empty_for_no_matches(): void
    {
        Client::factory()->create(['name' => 'John Doe']);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'nonexistent']));

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function test_search_result_structure_is_correct(): void
    {
        $client = Client::factory()->create(['name' => 'Test Client', 'email' => 'test@example.com']);

        $response = $this->actingAs($this->user)
            ->getJson(route('search', ['query' => 'Test']));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'type',
                'title',
                'subtitle',
                'url',
            ],
        ]);
    }
}
