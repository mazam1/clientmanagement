<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientSession;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getStats(): array
    {
        $totalRevenue = Invoice::paid()->sum('total_amount');
        $activeClients = Client::active()->count();
        $upcomingSessions = ClientSession::where('session_date', '>=', now())
            ->where('session_date', '<=', now()->addDays(7))
            ->count();
        $unpaidInvoices = Invoice::unpaid()->count();

        return [
            'total_revenue' => $totalRevenue,
            'active_clients' => $activeClients,
            'upcoming_sessions' => $upcomingSessions,
            'unpaid_invoices' => $unpaidInvoices,
        ];
    }

    public function getTrends(): array
    {
        // Current period (this month)
        $currentRevenue = Invoice::paid()
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('total_amount');

        // Previous period (last month)
        $previousRevenue = Invoice::paid()
            ->whereMonth('payment_date', now()->subMonth()->month)
            ->whereYear('payment_date', now()->subMonth()->year)
            ->sum('total_amount');

        // Current active clients
        $currentActiveClients = Client::active()->count();

        // Previous active clients (30 days ago)
        $previousActiveClients = Client::where('status', 'active')
            ->where('created_at', '<=', now()->subDays(30))
            ->count();

        return [
            'revenue_trend' => $this->calculateTrend($currentRevenue, $previousRevenue),
            'clients_trend' => $this->calculateTrend($currentActiveClients, $previousActiveClients),
        ];
    }

    private function calculateTrend(float|int $current, float|int $previous): array
    {
        if ($previous == 0) {
            return [
                'percentage' => $current > 0 ? 100 : 0,
                'direction' => $current > 0 ? 'up' : 'neutral',
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;

        return [
            'percentage' => round(abs($percentage), 1),
            'direction' => $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'neutral'),
        ];
    }

    public function getRecentActivity(int $limit = 10): array
    {
        $activities = [];

        $recentInvoices = Invoice::with('client')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn ($invoice) => [
                'type' => 'invoice',
                'icon' => 'document',
                'message' => "Invoice {$invoice->invoice_number} created for {$invoice->client->name}",
                'amount' => $invoice->total_amount,
                'status' => $invoice->payment_status,
                'timestamp' => $invoice->created_at,
            ]);

        $recentSessions = ClientSession::with('client')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn ($session) => [
                'type' => 'session',
                'icon' => 'calendar',
                'message' => "Session logged for {$session->client->name}",
                'duration' => $session->duration_minutes,
                'timestamp' => $session->created_at,
            ]);

        $activities = $recentInvoices->concat($recentSessions)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values()
            ->toArray();

        return $activities;
    }

    public function getRevenueByMonth(int $months = 6): array
    {
        $data = Invoice::paid()
            ->where('payment_date', '>=', now()->subMonths($months))
            ->select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        return $data;
    }

    public function getTopClients(int $limit = 5): array
    {
        return Client::withCount('invoices')
            ->withSum('invoices', 'total_amount')
            ->orderByDesc('invoices_sum_total_amount')
            ->take($limit)
            ->get()
            ->map(fn ($client) => [
                'id' => $client->id,
                'name' => $client->name,
                'total_revenue' => $client->invoices_sum_total_amount ?? 0,
                'invoice_count' => $client->invoices_count,
            ])
            ->toArray();
    }
}
