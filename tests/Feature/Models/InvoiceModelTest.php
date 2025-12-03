<?php

namespace Tests\Feature\Models;

use App\Models\Client;
use App\Models\ClientSession;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_belongs_to_client(): void
    {
        $client = Client::factory()->create();
        $invoice = Invoice::factory()->create(['client_id' => $client->id]);

        $this->assertInstanceOf(Client::class, $invoice->client);
        $this->assertEquals($client->id, $invoice->client->id);
    }

    public function test_invoice_can_retrieve_sessions(): void
    {
        $client = Client::factory()->create();
        $session1 = ClientSession::factory()->create(['client_id' => $client->id]);
        $session2 = ClientSession::factory()->create(['client_id' => $client->id]);

        $invoice = Invoice::factory()->create([
            'client_id' => $client->id,
            'session_ids' => [$session1->id, $session2->id],
        ]);

        $sessions = $invoice->sessions();

        $this->assertCount(2, $sessions);
        $this->assertTrue($sessions->contains('id', $session1->id));
        $this->assertTrue($sessions->contains('id', $session2->id));
    }

    public function test_unpaid_scope_filters_unpaid_invoices(): void
    {
        Invoice::factory()->count(2)->create(['payment_status' => 'unpaid']);
        Invoice::factory()->count(3)->create(['payment_status' => 'paid']);

        $unpaidInvoices = Invoice::unpaid()->get();

        $this->assertCount(2, $unpaidInvoices);
        $this->assertTrue($unpaidInvoices->every(fn ($invoice) => $invoice->payment_status === 'unpaid'));
    }

    public function test_paid_scope_filters_paid_invoices(): void
    {
        Invoice::factory()->count(2)->create(['payment_status' => 'unpaid']);
        Invoice::factory()->count(3)->create(['payment_status' => 'paid']);

        $paidInvoices = Invoice::paid()->get();

        $this->assertCount(3, $paidInvoices);
        $this->assertTrue($paidInvoices->every(fn ($invoice) => $invoice->payment_status === 'paid'));
    }

    public function test_partial_scope_filters_partial_invoices(): void
    {
        Invoice::factory()->count(2)->create(['payment_status' => 'unpaid']);
        Invoice::factory()->count(3)->create(['payment_status' => 'partial']);

        $partialInvoices = Invoice::partial()->get();

        $this->assertCount(3, $partialInvoices);
        $this->assertTrue($partialInvoices->every(fn ($invoice) => $invoice->payment_status === 'partial'));
    }

    public function test_is_paid_method(): void
    {
        $paidInvoice = Invoice::factory()->create(['payment_status' => 'paid']);
        $unpaidInvoice = Invoice::factory()->create(['payment_status' => 'unpaid']);

        $this->assertTrue($paidInvoice->isPaid());
        $this->assertFalse($unpaidInvoice->isPaid());
    }

    public function test_mark_as_paid_method(): void
    {
        $invoice = Invoice::factory()->create(['payment_status' => 'unpaid', 'payment_date' => null]);

        $invoice->markAsPaid();

        $this->assertEquals('paid', $invoice->fresh()->payment_status);
        $this->assertNotNull($invoice->fresh()->payment_date);
    }
}
