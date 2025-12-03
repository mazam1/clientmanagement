<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ClientService
{
    /**
     * Get all clients with pagination and filters.
     */
    public function getAllClients(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Client::query()->with(['clientSessions', 'invoices']);

        // Apply search filter
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Apply status filter
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Apply sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get a single client by ID.
     */
    public function getClientById(int $id): ?Client
    {
        return Client::with(['clientSessions' => function ($query) {
            $query->orderBy('session_date', 'desc');
        }, 'invoices'])->findOrFail($id);
    }

    /**
     * Create a new client.
     */
    public function createClient(array $data): Client
    {
        return Client::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);
    }

    /**
     * Update an existing client.
     */
    public function updateClient(int $id, array $data): Client
    {
        $client = Client::findOrFail($id);

        $client->update([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
        ]);

        return $client->fresh();
    }

    /**
     * Delete a client (soft delete).
     */
    public function deleteClient(int $id): bool
    {
        $client = Client::findOrFail($id);

        return $client->delete();
    }

    /**
     * Get client statistics.
     */
    public function getClientStats(): array
    {
        return [
            'total' => Client::count(),
            'active' => Client::active()->count(),
            'inactive' => Client::inactive()->count(),
            'archived' => Client::archived()->count(),
        ];
    }

    /**
     * Search clients by query string.
     */
    public function searchClients(string $query, int $limit = 10): Collection
    {
        return Client::where('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    /**
     * Export clients to CSV format.
     */
    public function exportToCSV(array $filters = []): string
    {
        $query = Client::query();

        // Apply filters
        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $clients = $query->get();

        // Generate CSV
        $csv = "Name,Email,Phone,Status,Created At\n";

        foreach ($clients as $client) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s"'."\n",
                str_replace('"', '""', $client->name),
                str_replace('"', '""', $client->email ?? ''),
                str_replace('"', '""', $client->phone ?? ''),
                $client->status,
                $client->created_at->format('Y-m-d H:i:s')
            );
        }

        return $csv;
    }
}
