<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientSession;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);

        // Use the super admin user who has all permissions
        $this->user = User::where('email', 'superadmin@example.com')->first();
    }

    public function test_create_invoice_page_loads_successfully(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('invoices.create'));

        $response->assertStatus(200);
        $response->assertViewIs('invoices.create');
        $response->assertViewHas(['clients', 'selectedClientId', 'unbilledSessions', 'defaultHourlyRate', 'defaultTaxRate']);
    }

    public function test_client_dropdown_is_enabled_without_query_parameter(): void
    {
        Client::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get(route('invoices.create'));

        $response->assertStatus(200);
        $response->assertDontSee('disabled', false);
        $response->assertViewHas('selectedClientId', null);
    }

    public function test_client_dropdown_is_disabled_with_query_parameter(): void
    {
        $client = Client::factory()->create(['name' => 'Test Client']);

        $response = $this->actingAs($this->user)
            ->get(route('invoices.create', ['client_id' => $client->id]));

        $response->assertStatus(200);
        $response->assertSee('disabled', false);
        $response->assertViewHas('selectedClientId', $client->id);
    }

    public function test_unbilled_sessions_loaded_with_client_query_parameter(): void
    {
        $client = Client::factory()->create();

        // Create unbilled sessions (sessions not included in any invoice)
        ClientSession::factory()->count(3)->create([
            'client_id' => $client->id,
        ]);

        // Create an invoice with one session (making it "billed")
        $billedSession = ClientSession::factory()->create([
            'client_id' => $client->id,
        ]);

        Invoice::factory()->create([
            'client_id' => $client->id,
            'session_ids' => [$billedSession->id],
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('invoices.create', ['client_id' => $client->id]));

        $response->assertStatus(200);
        $response->assertViewHas('selectedClientId', $client->id);
        $response->assertViewHas('unbilledSessions');

        $unbilledSessions = $response->viewData('unbilledSessions');
        $this->assertCount(3, $unbilledSessions);
    }

    public function test_hidden_input_included_when_client_is_preselected(): void
    {
        $client = Client::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('invoices.create', ['client_id' => $client->id]));

        $response->assertStatus(200);
        $response->assertSee('type="hidden" name="client_id"', false);
        $response->assertSee('value="'.$client->id.'"', false);
    }

    public function test_can_mark_invoice_as_paid(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create([
            'client_id' => $client->id,
            'payment_status' => 'unpaid',
            'payment_date' => null,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('invoices.mark-paid', $invoice->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Invoice marked as paid!');

        $invoice->refresh();
        $this->assertEquals('paid', $invoice->payment_status);
        $this->assertNotNull($invoice->payment_date);
    }

    public function test_can_mark_invoice_as_unpaid(): void
    {
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);

        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create([
            'client_id' => $client->id,
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('invoices.mark-unpaid', $invoice->id));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Invoice marked as unpaid!');

        $invoice->refresh();
        $this->assertEquals('unpaid', $invoice->payment_status);
    }
}
