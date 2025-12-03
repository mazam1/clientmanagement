<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Client;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoiceService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'client_id' => $request->get('client_id'),
            'payment_status' => $request->get('payment_status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort_by' => $request->get('sort_by', 'issued_at'),
            'sort_direction' => $request->get('sort_direction', 'desc'),
        ];

        $invoices = $this->invoiceService->getAllInvoices(15, $filters);
        $stats = $this->invoiceService->getInvoiceStats();
        $clients = Client::orderBy('name')->get();

        return view('invoices.index', compact('invoices', 'stats', 'clients', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $clients = Client::active()->orderBy('name')->get();
        $selectedClientId = $request->get('client_id');

        $unbilledSessions = null;
        if ($selectedClientId) {
            $unbilledSessions = $this->invoiceService->getUnbilledSessions($selectedClientId);
        }

        $defaultHourlyRate = \App\Models\Setting::get('hourly_rate', 50);
        $defaultTaxRate = \App\Models\Setting::get('tax_rate', 0);

        return view('invoices.create', compact('clients', 'selectedClientId', 'unbilledSessions', 'defaultHourlyRate', 'defaultTaxRate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        try {
            $this->invoiceService->createInvoice($request->validated());

            return redirect()
                ->route('invoices.index')
                ->with('success', 'Invoice created successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create invoice. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $invoice = $this->invoiceService->getInvoiceById($id);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $invoice = $this->invoiceService->getInvoiceById($id);
        $clients = Client::active()->orderBy('name')->get();
        $unbilledSessions = $this->invoiceService->getUnbilledSessions($invoice->client_id);

        // Add already billed sessions from this invoice to the list
        $selectedSessions = $invoice->sessions();

        return view('invoices.edit', compact('invoice', 'clients', 'unbilledSessions', 'selectedSessions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, int $id): RedirectResponse
    {
        try {
            $this->invoiceService->updateInvoice($id, $request->validated());

            return redirect()
                ->route('invoices.show', $id)
                ->with('success', 'Invoice updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update invoice. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->invoiceService->deleteInvoice($id);

            return redirect()
                ->route('invoices.index')
                ->with('success', 'Invoice deleted successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete invoice. Please try again.');
        }
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(int $id): RedirectResponse
    {
        try {
            $this->invoiceService->markAsPaid($id);

            return redirect()
                ->back()
                ->with('success', 'Invoice marked as paid!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to mark invoice as paid. Please try again.');
        }
    }

    /**
     * Mark invoice as unpaid.
     */
    public function markAsUnpaid(int $id): RedirectResponse
    {
        try {
            $this->invoiceService->markAsUnpaid($id);

            return redirect()
                ->back()
                ->with('success', 'Invoice marked as unpaid!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to mark invoice as unpaid. Please try again.');
        }
    }

    /**
     * Display print-friendly view of invoice.
     */
    public function print(int $id): View
    {
        $invoice = $this->invoiceService->getInvoiceById($id);

        return view('invoices.print', compact('invoice'));
    }

    /**
     * Export invoices to CSV.
     */
    public function export(Request $request)
    {
        $filters = [
            'client_id' => $request->get('client_id'),
            'payment_status' => $request->get('payment_status'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ];

        $csv = $this->invoiceService->exportToCSV($filters);
        $filename = 'invoices_' . now()->format('Y-m-d_His') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
