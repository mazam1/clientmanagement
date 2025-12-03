<?php

namespace App\Services;

use App\Models\ClientSession;
use App\Models\Invoice;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class InvoiceService
{
    /**
     * Get all invoices with pagination and filters.
     */
    public function getAllInvoices(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Invoice::query()->with('client');

        // Apply client filter
        if (! empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        // Apply payment status filter
        if (! empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        // Apply date range filter
        if (! empty($filters['date_from'])) {
            $query->whereDate('issued_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('issued_at', '<=', $filters['date_to']);
        }

        // Apply search filter
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('client', function ($clientQuery) use ($search) {
                        $clientQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'issued_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get invoices for a specific client.
     */
    public function getClientInvoices(int $clientId): Collection
    {
        return Invoice::where('client_id', $clientId)
            ->orderBy('issued_at', 'desc')
            ->get();
    }

    /**
     * Get a single invoice by ID.
     */
    public function getInvoiceById(int $id): ?Invoice
    {
        return Invoice::with('client')->findOrFail($id);
    }

    /**
     * Create a new invoice.
     */
    public function createInvoice(array $data): Invoice
    {
        // Calculate totals from session IDs
        $sessionIds = $data['session_ids'] ?? [];
        $totals = $this->calculateTotalsFromSessions($sessionIds, $data['hourly_rate'] ?? 0, $data['tax_rate'] ?? 0);

        return Invoice::create([
            'client_id' => $data['client_id'],
            'invoice_number' => $this->generateInvoiceNumber(),
            'session_ids' => $sessionIds,
            'hourly_rate' => $data['hourly_rate'] ?? 0,
            'tax_rate' => $data['tax_rate'] ?? 0,
            'subtotal' => $totals['subtotal'],
            'tax_amount' => $totals['tax_amount'],
            'total_amount' => $totals['total_amount'],
            'payment_status' => $data['payment_status'] ?? 'unpaid',
            'payment_date' => $data['payment_date'] ?? null,
            'issued_at' => $data['issued_at'] ?? now(),
        ]);
    }

    /**
     * Update an existing invoice.
     */
    public function updateInvoice(int $id, array $data): Invoice
    {
        $invoice = Invoice::findOrFail($id);

        // Recalculate totals if sessions, rates changed
        if (isset($data['session_ids']) || isset($data['hourly_rate']) || isset($data['tax_rate'])) {
            $totals = $this->calculateTotalsFromSessions(
                $data['session_ids'] ?? $invoice->session_ids,
                $data['hourly_rate'] ?? $invoice->hourly_rate ?? 0,
                $data['tax_rate'] ?? $invoice->tax_rate ?? 0
            );

            $data['subtotal'] = $totals['subtotal'];
            $data['tax_amount'] = $totals['tax_amount'];
            $data['total_amount'] = $totals['total_amount'];
        }

        $invoice->update($data);

        return $invoice->fresh();
    }

    /**
     * Delete an invoice.
     */
    public function deleteInvoice(int $id): bool
    {
        $invoice = Invoice::findOrFail($id);

        return $invoice->delete();
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(int $id): Invoice
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->markAsPaid();

        return $invoice->fresh();
    }

    /**
     * Mark invoice as unpaid.
     */
    public function markAsUnpaid(int $id): Invoice
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->update([
            'payment_status' => 'unpaid',
            'payment_date' => null,
        ]);

        return $invoice->fresh();
    }

    /**
     * Generate unique invoice number.
     * Format: INV-YYYYMMDD-XXXX
     */
    public function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $prefix = "INV-{$date}";

        // Get the last invoice number for today
        $lastInvoice = Invoice::where('invoice_number', 'LIKE', "{$prefix}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastInvoice->invoice_number, -4);
            $sequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First invoice of the day
            $sequence = '0001';
        }

        return "{$prefix}-{$sequence}";
    }

    /**
     * Calculate totals from session IDs.
     */
    public function calculateTotalsFromSessions(array $sessionIds, float $hourlyRate, float $taxRate = 0): array
    {
        $sessions = ClientSession::whereIn('id', $sessionIds)->get();

        // Calculate total hours
        $totalMinutes = $sessions->sum('duration_minutes');
        $totalHours = $totalMinutes / 60;

        // Calculate subtotal
        $subtotal = $totalHours * $hourlyRate;

        // Calculate tax
        $taxAmount = $subtotal * ($taxRate / 100);

        // Calculate total
        $totalAmount = $subtotal + $taxAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'tax_amount' => round($taxAmount, 2),
            'total_amount' => round($totalAmount, 2),
            'total_hours' => round($totalHours, 2),
        ];
    }

    /**
     * Get invoice statistics.
     */
    public function getInvoiceStats(): array
    {
        $totalInvoices = Invoice::count();
        $paidInvoices = Invoice::paid()->count();
        $unpaidInvoices = Invoice::unpaid()->count();
        $partialInvoices = Invoice::partial()->count();

        $totalRevenue = Invoice::paid()->sum('total_amount');
        $unpaidAmount = Invoice::unpaid()->sum('total_amount');
        $partialAmount = Invoice::partial()->sum('total_amount');

        return [
            'total_invoices' => $totalInvoices,
            'paid_invoices' => $paidInvoices,
            'unpaid_invoices' => $unpaidInvoices,
            'partial_invoices' => $partialInvoices,
            'total_revenue' => round($totalRevenue, 2),
            'unpaid_amount' => round($unpaidAmount, 2),
            'partial_amount' => round($partialAmount, 2),
        ];
    }

    /**
     * Get unbilled sessions for a client.
     */
    public function getUnbilledSessions(int $clientId): Collection
    {
        // Get all session IDs that are already in invoices for this client
        $billedSessionIds = Invoice::where('client_id', $clientId)
            ->get()
            ->pluck('session_ids')
            ->flatten()
            ->unique()
            ->toArray();

        // Get sessions that are not in any invoice
        return ClientSession::where('client_id', $clientId)
            ->whereNotIn('id', $billedSessionIds)
            ->orderBy('session_date', 'desc')
            ->get();
    }

    /**
     * Export invoices to CSV.
     */
    public function exportToCSV(array $filters = []): string
    {
        $query = Invoice::query()->with('client');

        // Apply filters
        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }
        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }
        if (!empty($filters['date_from'])) {
            $query->whereDate('issued_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('issued_at', '<=', $filters['date_to']);
        }

        $invoices = $query->orderBy('issued_at', 'desc')->get();

        // Generate CSV
        $csv = "Invoice Number,Client,Issued Date,Payment Status,Subtotal,Tax,Total,Payment Date\n";

        foreach ($invoices as $invoice) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%.2f,%.2f,%.2f,%s\n",
                $invoice->invoice_number,
                '"' . str_replace('"', '""', $invoice->client->name) . '"',
                $invoice->issued_at->format('Y-m-d'),
                ucfirst($invoice->payment_status),
                $invoice->subtotal,
                $invoice->tax_amount,
                $invoice->total_amount,
                $invoice->payment_date ? $invoice->payment_date->format('Y-m-d') : 'N/A'
            );
        }

        return $csv;
    }
}
