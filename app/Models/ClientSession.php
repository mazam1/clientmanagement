<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'session_date',
        'duration_minutes',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the client that owns the session.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope to get upcoming sessions.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('session_date', '>', now());
    }

    /**
     * Scope to get past sessions.
     */
    public function scopePast($query)
    {
        return $query->where('session_date', '<=', now());
    }

    /**
     * Get duration in hours.
     */
    public function getDurationHoursAttribute(): string
    {
        return number_format($this->duration_minutes / 60, 2);
    }
}
