<?php

namespace Tests\Feature\Models;

use App\Models\Client;
use App\Models\ClientSession;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_be_created(): void
    {
        $client = Client::factory()->create([
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'phone' => '123-456-7890',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('clients', [
            'name' => 'Test Company',
            'email' => 'test@example.com',
            'phone' => '123-456-7890',
            'status' => 'active',
        ]);
    }

    public function test_client_has_many_client_sessions(): void
    {
        $client = Client::factory()->create();
        $sessions = ClientSession::factory()->count(3)->create(['client_id' => $client->id]);

        $this->assertCount(3, $client->clientSessions);
        $this->assertInstanceOf(ClientSession::class, $client->clientSessions->first());
    }

    public function test_client_has_many_invoices(): void
    {
        $client = Client::factory()->create();
        $invoices = Invoice::factory()->count(2)->create(['client_id' => $client->id]);

        $this->assertCount(2, $client->invoices);
        $this->assertInstanceOf(Invoice::class, $client->invoices->first());
    }

    public function test_active_scope_filters_active_clients(): void
    {
        Client::factory()->count(3)->create(['status' => 'active']);
        Client::factory()->count(2)->create(['status' => 'inactive']);

        $activeClients = Client::active()->get();

        $this->assertCount(3, $activeClients);
        $this->assertTrue($activeClients->every(fn ($client) => $client->status === 'active'));
    }

    public function test_inactive_scope_filters_inactive_clients(): void
    {
        Client::factory()->count(3)->create(['status' => 'active']);
        Client::factory()->count(2)->create(['status' => 'inactive']);

        $inactiveClients = Client::inactive()->get();

        $this->assertCount(2, $inactiveClients);
        $this->assertTrue($inactiveClients->every(fn ($client) => $client->status === 'inactive'));
    }

    public function test_archived_scope_filters_archived_clients(): void
    {
        Client::factory()->count(3)->create(['status' => 'active']);
        Client::factory()->count(2)->create(['status' => 'archived']);

        $archivedClients = Client::archived()->get();

        $this->assertCount(2, $archivedClients);
        $this->assertTrue($archivedClients->every(fn ($client) => $client->status === 'archived'));
    }

    public function test_total_billable_hours_accessor(): void
    {
        $client = Client::factory()->create();
        ClientSession::factory()->create(['client_id' => $client->id, 'duration_minutes' => 60]);
        ClientSession::factory()->create(['client_id' => $client->id, 'duration_minutes' => 90]);

        $client->refresh();

        $this->assertEquals(2.5, $client->total_billable_hours);
    }

    public function test_total_revenue_accessor(): void
    {
        $client = Client::factory()->create();
        Invoice::factory()->create(['client_id' => $client->id, 'total_amount' => 100, 'payment_status' => 'paid']);
        Invoice::factory()->create(['client_id' => $client->id, 'total_amount' => 200, 'payment_status' => 'paid']);
        Invoice::factory()->create(['client_id' => $client->id, 'total_amount' => 150, 'payment_status' => 'unpaid']);

        $client->refresh();

        $this->assertEquals(300, $client->total_revenue);
    }

    public function test_client_soft_deletes(): void
    {
        $client = Client::factory()->create();
        $clientId = $client->id;

        $client->delete();

        $this->assertSoftDeleted('clients', ['id' => $clientId]);
        $this->assertNotNull($client->fresh()->deleted_at);
    }
}
