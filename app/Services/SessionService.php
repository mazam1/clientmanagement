<?php

namespace App\Services;

use App\Models\ClientSession;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SessionService
{
    /**
     * Get all sessions with pagination and filters.
     */
    public function getAllSessions(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = ClientSession::query()->with('client');

        // Apply client filter
        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        // Apply date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('session_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('session_date', '<=', $filters['date_to']);
        }

        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', function ($clientQuery) use ($search) {
                    $clientQuery->where('name', 'LIKE', "%{$search}%");
                })->orWhere('notes', 'LIKE', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'session_date';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get sessions for a specific client.
     */
    public function getClientSessions(int $clientId): Collection
    {
        return ClientSession::where('client_id', $clientId)
            ->orderBy('session_date', 'desc')
            ->get();
    }

    /**
     * Get a single session by ID.
     */
    public function getSessionById(int $id): ?ClientSession
    {
        return ClientSession::with('client')->findOrFail($id);
    }

    /**
     * Create a new session.
     */
    public function createSession(array $data): ClientSession
    {
        return ClientSession::create([
            'client_id' => $data['client_id'],
            'session_date' => $data['session_date'],
            'duration_minutes' => $data['duration_minutes'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Update an existing session.
     */
    public function updateSession(int $id, array $data): ClientSession
    {
        $session = ClientSession::findOrFail($id);

        $session->update([
            'client_id' => $data['client_id'],
            'session_date' => $data['session_date'],
            'duration_minutes' => $data['duration_minutes'],
            'notes' => $data['notes'] ?? null,
        ]);

        return $session->fresh();
    }

    /**
     * Delete a session.
     */
    public function deleteSession(int $id): bool
    {
        $session = ClientSession::findOrFail($id);

        return $session->delete();
    }

    /**
     * Get session statistics.
     */
    public function getSessionStats(): array
    {
        $totalSessions = ClientSession::count();
        $upcomingSessions = ClientSession::upcoming()->count();
        $totalHours = ClientSession::sum('duration_minutes') / 60;
        $upcomingHours = ClientSession::upcoming()->sum('duration_minutes') / 60;

        return [
            'total_sessions' => $totalSessions,
            'upcoming_sessions' => $upcomingSessions,
            'total_hours' => round($totalHours, 2),
            'upcoming_hours' => round($upcomingHours, 2),
        ];
    }

    /**
     * Get upcoming sessions (next 7 days).
     */
    public function getUpcomingSessions(int $days = 7): Collection
    {
        return ClientSession::with('client')
            ->whereBetween('session_date', [now(), now()->addDays($days)])
            ->orderBy('session_date', 'asc')
            ->get();
    }

    /**
     * Calculate total billable hours for a client.
     */
    public function getClientTotalHours(int $clientId): float
    {
        $totalMinutes = ClientSession::where('client_id', $clientId)
            ->sum('duration_minutes');

        return round($totalMinutes / 60, 2);
    }
}
