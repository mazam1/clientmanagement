<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <x-slot name="description">
        Welcome to your Client Management Dashboard
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stat-card
                title="Total Revenue"
                :value="'$' . number_format($stats['total_revenue'], 2)"
                color="success"
                :trend="$trends['revenue_trend']"
                :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg>'"
            />
            <x-stat-card
                title="Active Clients"
                :value="$stats['active_clients']"
                color="primary"
                :trend="$trends['clients_trend']"
                :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z\'/></svg>'"
            />
            <x-stat-card
                title="Upcoming Sessions"
                :value="$stats['upcoming_sessions']"
                color="primary"
                :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg>'"
            />
            <x-stat-card
                title="Unpaid Invoices"
                :value="$stats['unpaid_invoices']"
                color="warning"
                :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'/></svg>'"
            />
        </div>

        <!-- Activity & Top Clients Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Activity -->
            <x-card title="Recent Activity">
                <div class="space-y-4">
                    @forelse($activities as $activity)
                        <div class="flex items-start gap-3 pb-3 border-b border-border-light dark:border-dark-border-light last:border-0">
                            <div class="p-2 rounded-lg bg-accent-primary/10">
                                @if($activity['type'] === 'invoice')
                                    <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-text-primary dark:text-dark-text-primary">{{ $activity['message'] }}</p>
                                <p class="text-xs text-text-tertiary dark:text-dark-text-tertiary mt-1">{{ $activity['timestamp']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-text-tertiary dark:text-dark-text-tertiary py-4">No recent activity</p>
                    @endforelse
                </div>
            </x-card>

            <!-- Top Clients -->
            <x-card title="Top Clients">
                <div class="space-y-3">
                    @forelse($topClients as $client)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-bg-secondary dark:bg-dark-bg-secondary">
                            <div>
                                <p class="font-medium text-text-primary dark:text-dark-text-primary">{{ $client['name'] }}</p>
                                <p class="text-sm text-text-tertiary dark:text-dark-text-tertiary">{{ $client['invoice_count'] }} invoices</p>
                            </div>
                            <span class="font-bold text-accent-success">${{ number_format($client['total_revenue'], 2) }}</span>
                        </div>
                    @empty
                        <p class="text-center text-text-tertiary dark:text-dark-text-tertiary py-4">No client data available</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>
