<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get all sessions for the client.
     */
    public function clientSessions(): HasMany
    {
        return $this->hasMany(ClientSession::class);
    }

    /**
     * Get all invoices for the client.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Scope to filter active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to filter inactive clients.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope to filter archived clients.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Get total billable hours for client.
     */
    public function getTotalBillableHoursAttribute(): float
    {
        return $this->clientSessions->sum('duration_minutes') / 60;
    }

    /**
     * Get total revenue from paid invoices.
     */
    public function getTotalRevenueAttribute(): float
    {
        return $this->invoices()->where('payment_status', 'paid')->sum('total_amount');
    }
}
