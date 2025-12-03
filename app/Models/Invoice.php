<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'invoice_number',
        'session_ids',
        'hourly_rate',
        'tax_rate',
        'subtotal',
        'tax_amount',
        'total_amount',
        'payment_status',
        'payment_date',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'session_ids' => 'array',
            'hourly_rate' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'payment_date' => 'datetime',
            'issued_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the client that owns the invoice.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get sessions included in this invoice.
     */
    public function sessions(): Collection
    {
        if (empty($this->session_ids)) {
            return collect();
        }

        return ClientSession::whereIn('id', $this->session_ids)->get();
    }

    /**
     * Scope to filter unpaid invoices.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    /**
     * Scope to filter paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope to filter partial invoices.
     */
    public function scopePartial($query)
    {
        return $query->where('payment_status', 'partial');
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(): bool
    {
        return $this->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);
    }
}
