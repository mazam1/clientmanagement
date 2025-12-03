<x-app-layout>
    <x-slot name="title">Sessions</x-slot>
    <x-slot name="description">Manage client sessions and appointments</x-slot>

    <x-slot name="action">
        @can('create-sessions')
            <x-button href="{{ route('sessions.create') }}" variant="primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Session
            </x-button>
        @endcan
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
        <x-card :padding="false">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Total Sessions</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">
                            {{ $stats['total_sessions'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-accent-primary/10 dark:bg-accent-primary/20 rounded-lg">
                        <svg class="w-8 h-8 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card :padding="false">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Upcoming Sessions</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">
                            {{ $stats['upcoming_sessions'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-accent-secondary/10 dark:bg-accent-secondary/20 rounded-lg">
                        <svg class="w-8 h-8 text-accent-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card :padding="false">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Total Hours</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">
                            {{ number_format($stats['total_hours'], 1) }}h
                        </p>
                    </div>
                    <div class="p-3 bg-success/10 dark:bg-success/20 rounded-lg">
                        <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card :padding="false">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Upcoming Hours</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">
                            {{ number_format($stats['upcoming_hours'], 1) }}h
                        </p>
                    </div>
                    <div class="p-3 bg-warning/10 dark:bg-warning/20 rounded-lg">
                        <svg class="w-8 h-8 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Search and Filters -->
    <x-card class="mb-6">
        <form method="GET" action="{{ route('sessions.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Search
                    </label>
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search by client or notes..."
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                    />
                </div>

                <!-- Client Filter -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Client
                    </label>
                    <select
                        name="client_id"
                        id="client_id"
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                    >
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ ($filters['client_id'] ?? '') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Date From
                    </label>
                    <input
                        type="date"
                        name="date_from"
                        id="date_from"
                        value="{{ $filters['date_from'] ?? '' }}"
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                    />
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Date To
                    </label>
                    <input
                        type="date"
                        name="date_to"
                        id="date_to"
                        value="{{ $filters['date_to'] ?? '' }}"
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                    />
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <x-button type="submit" variant="primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Apply Filters
                </x-button>
                <x-button href="{{ route('sessions.index') }}" variant="ghost">
                    Clear Filters
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Sessions Table -->
    <x-card>
        @if($sessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-border-primary dark:border-dark-border-primary">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Session Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Duration
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Notes
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-primary dark:divide-dark-border-primary">
                        @foreach($sessions as $session)
                            <tr class="hover:bg-bg-secondary dark:hover:bg-dark-bg-secondary transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-accent-primary flex items-center justify-center text-white font-semibold">
                                                {{ substr($session->client->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <a href="{{ route('clients.show', $session->client->id) }}" class="text-sm font-medium text-accent-primary hover:text-accent-secondary">
                                                {{ $session->client->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-text-primary dark:text-dark-text-primary">
                                        {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-text-secondary dark:text-dark-text-secondary">
                                        {{ \Carbon\Carbon::parse($session->session_date)->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-accent-primary/10 dark:bg-accent-primary/20 text-accent-primary">
                                        {{ $session->duration_minutes }} min ({{ number_format($session->duration_minutes / 60, 1) }}h)
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-text-secondary dark:text-dark-text-secondary max-w-xs truncate">
                                        {{ $session->notes ?: '—' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('view-sessions')
                                            <a href="{{ route('sessions.show', $session->id) }}" class="text-accent-primary hover:text-accent-secondary">
                                                View
                                            </a>
                                        @endcan
                                        @can('edit-sessions')
                                            <a href="{{ route('sessions.edit', $session->id) }}" class="text-accent-primary hover:text-accent-secondary">
                                                Edit
                                            </a>
                                        @endcan
                                        @can('delete-sessions')
                                            <form method="POST" action="{{ route('sessions.destroy', $session->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this session?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger hover:text-danger/80">
                                                    Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $sessions->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-text-secondary dark:text-dark-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-text-primary dark:text-dark-text-primary">No sessions found</h3>
                <p class="mt-1 text-sm text-text-secondary dark:text-dark-text-secondary">
                    @if(request()->hasAny(['search', 'client_id', 'date_from', 'date_to']))
                        Try adjusting your filters to find what you're looking for.
                    @else
                        Get started by creating a new session.
                    @endif
                </p>
                @can('create-sessions')
                    <div class="mt-6">
                        <x-button href="{{ route('sessions.create') }}" variant="primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add First Session
                        </x-button>
                    </div>
                @endcan
            </div>
        @endif
    </x-card>
</x-app-layout>
