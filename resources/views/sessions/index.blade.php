<x-app-layout>
    <x-slot name="header">
        Sessions
    </x-slot>

    <x-slot name="description">
        Manage client sessions and appointments
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-card padding="false">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary uppercase">Total Sessions</p>
                            <p class="text-2xl font-bold text-text-primary dark:text-dark-text-primary mt-1">{{ $stats['total_sessions'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-accent-primary/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card padding="false">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary uppercase">Upcoming Sessions</p>
                            <p class="text-2xl font-bold text-accent-secondary mt-1">{{ $stats['upcoming_sessions'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-accent-secondary/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card padding="false">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary uppercase">Total Hours</p>
                            <p class="text-2xl font-bold text-text-primary dark:text-dark-text-primary mt-1">{{ number_format($stats['total_hours'], 1) }}h</p>
                        </div>
                        <div class="w-10 h-10 bg-accent-success/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card padding="false">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary uppercase">Upcoming Hours</p>
                            <p class="text-2xl font-bold text-accent-warning mt-1">{{ number_format($stats['upcoming_hours'], 1) }}h</p>
                        </div>
                        <div class="w-10 h-10 bg-accent-warning/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Search and Filters Card -->
        <x-card>
            <form method="GET" action="{{ route('sessions.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-2">Search</label>
                        <div class="relative">
                            <input
                                type="search"
                                name="search"
                                id="search"
                                value="{{ $filters['search'] ?? '' }}"
                                placeholder="Search by client or notes..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary placeholder-text-tertiary dark:placeholder-dark-text-tertiary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                            />
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-text-tertiary dark:text-dark-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Client Filter -->
                    <div class="md:col-span-2">
                        <label for="client_id" class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-2">Client</label>
                        <select
                            name="client_id"
                            id="client_id"
                            class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                        >
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ ($filters['client_id'] ?? '') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-2">Date From</label>
                        <input
                            type="date"
                            name="date_from"
                            id="date_from"
                            value="{{ $filters['date_from'] ?? '' }}"
                            class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                        />
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-2">Date To</label>
                        <input
                            type="date"
                            name="date_to"
                            id="date_to"
                            value="{{ $filters['date_to'] ?? '' }}"
                            class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                        />
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-2 flex items-end gap-2">
                        <x-button type="submit" variant="primary" size="md" class="flex-1 md:flex-none">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter
                        </x-button>
                        @if($filters['search'] || $filters['client_id'] || $filters['date_from'] || $filters['date_to'])
                            <a href="{{ route('sessions.index') }}">
                                <x-button type="button" variant="ghost" size="md">
                                    Clear
                                </x-button>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </x-card>

        <!-- Sessions Table Card -->
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-text-primary dark:text-dark-text-primary">Session List</h3>
                <div class="flex gap-3">
                    @can('create-sessions')
                        <a href="{{ route('sessions.create') }}">
                            <x-button variant="primary" size="sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Session
                            </x-button>
                        </a>
                    @endcan
                </div>
            </div>

            @if($sessions->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-text-tertiary dark:text-dark-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-text-primary dark:text-dark-text-primary">No sessions found</h3>
                    <p class="mt-1 text-sm text-text-secondary dark:text-dark-text-secondary">
                        @if($filters['search'] || $filters['client_id'] || $filters['date_from'] || $filters['date_to'])
                            Try adjusting your search or filter criteria.
                        @else
                            Get started by creating your first session.
                        @endif
                    </p>
                    @can('create-sessions')
                        @if(!$filters['search'] && !$filters['client_id'] && !$filters['date_from'] && !$filters['date_to'])
                            <div class="mt-6">
                                <a href="{{ route('sessions.create') }}">
                                    <x-button variant="primary" size="md">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add First Session
                                    </x-button>
                                </a>
                            </div>
                        @endif
                    @endcan
                </div>
            @else
                <!-- Sessions Table (Desktop) -->
                <div class="hidden lg:block">
                    <x-table :headers="['Client', 'Session Date', 'Duration', 'Notes', 'Actions']">
                        @foreach($sessions as $session)
                            <tr class="hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors cursor-pointer" onclick="window.location='{{ route('sessions.show', $session) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-accent-primary flex items-center justify-center text-white font-semibold mr-3">
                                            {{ strtoupper(substr($session->client->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-text-primary dark:text-dark-text-primary">{{ $session->client->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-text-secondary dark:text-dark-text-secondary">
                                    <div>{{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}</div>
                                    <div class="text-xs text-text-tertiary dark:text-dark-text-tertiary">{{ \Carbon\Carbon::parse($session->session_date)->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-accent-primary text-white">
                                        {{ $session->duration_minutes }} min ({{ number_format($session->duration_minutes / 60, 1) }}h)
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-text-secondary dark:text-dark-text-secondary max-w-xs truncate">{{ $session->notes ?: '—' }}</td>
                                <td class="px-6 py-4" onclick="event.stopPropagation()">
                                    <div class="flex items-center gap-2">
                                        @can('edit-sessions')
                                            <a href="{{ route('sessions.edit', $session) }}" class="text-accent-primary hover:text-accent-primary/80">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('delete-sessions')
                                            <form method="POST" action="{{ route('sessions.destroy', $session) }}" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-accent-danger hover:text-accent-danger/80">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                </div>

                <!-- Sessions Cards (Mobile/Tablet) -->
                <div class="lg:hidden space-y-4">
                    @foreach($sessions as $session)
                        <div class="bg-bg-secondary dark:bg-dark-bg-secondary border border-border-medium dark:border-dark-border-medium rounded-lg p-4">
                            <a href="{{ route('sessions.show', $session) }}" class="block">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 rounded-full bg-accent-primary flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                                        {{ strtoupper(substr($session->client->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-text-primary dark:text-dark-text-primary truncate">{{ $session->client->name }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-accent-primary text-white">
                                            {{ $session->duration_minutes }} min ({{ number_format($session->duration_minutes / 60, 1) }}h)
                                        </span>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center text-text-secondary dark:text-dark-text-secondary">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-text-tertiary dark:text-dark-text-tertiary text-xs">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($session->session_date)->diffForHumans() }}</span>
                                    </div>
                                    @if($session->notes)
                                        <div class="text-text-secondary dark:text-dark-text-secondary text-xs pt-2 border-t border-border-light dark:border-dark-border-light">
                                            {{ Str::limit($session->notes, 100) }}
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="flex gap-2 mt-3 pt-3 border-t border-border-light dark:border-dark-border-light">
                                @can('edit-sessions')
                                    <a href="{{ route('sessions.edit', $session) }}" class="flex-1 flex items-center justify-center px-3 py-2 bg-accent-primary text-white rounded-lg text-sm font-medium hover:bg-accent-primary/90 transition-colors min-h-[44px]">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                @endcan
                                @can('delete-sessions')
                                    <form method="POST" action="{{ route('sessions.destroy', $session) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full flex items-center justify-center px-3 py-2 bg-accent-danger text-white rounded-lg text-sm font-medium hover:bg-accent-danger/90 transition-colors min-h-[44px]">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $sessions->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
