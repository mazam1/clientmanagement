<x-app-layout>
    <x-slot name="header">
        Clients
    </x-slot>

    <x-slot name="description">
        Manage your client relationships
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <x-card padding="false">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary uppercase">Total</p>
                            <p class="text-2xl font-bold text-text-primary dark:text-dark-text-primary mt-1">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-accent-primary/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card padding="false">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary uppercase">Active</p>
                            <p class="text-2xl font-bold text-accent-success mt-1">{{ $stats['active'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-accent-success/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card padding="false">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary uppercase">Inactive</p>
                            <p class="text-2xl font-bold text-text-secondary dark:text-dark-text-secondary mt-1">{{ $stats['inactive'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-text-tertiary/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-text-secondary dark:text-dark-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-card>

            <x-card padding="false">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary uppercase">Archived</p>
                            <p class="text-2xl font-bold text-accent-warning mt-1">{{ $stats['archived'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-accent-warning/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-accent-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Search and Filters Card -->
        <x-card>
            <form method="GET" action="{{ route('clients.index') }}" class="space-y-4">
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
                                placeholder="Search by name, email, or phone..."
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary placeholder-text-tertiary dark:placeholder-dark-text-tertiary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                            />
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-text-tertiary dark:text-dark-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-2">Status</label>
                        <select
                            name="status"
                            id="status"
                            class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                        >
                            <option value="">All Status</option>
                            <option value="active" {{ ($filters['status'] ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="archived" {{ ($filters['status'] ?? '') === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end gap-2">
                        <x-button type="submit" variant="primary" size="md" class="flex-1">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter
                        </x-button>
                        @if($filters['search'] || $filters['status'])
                            <a href="{{ route('clients.index') }}">
                                <x-button type="button" variant="ghost" size="md">
                                    Clear
                                </x-button>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </x-card>

        <!-- Clients Table Card -->
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-text-primary dark:text-dark-text-primary">Client List</h3>
                <div class="flex gap-3">
                    @can('export-clients')
                        <a href="{{ route('clients.export', request()->query()) }}">
                            <x-button variant="secondary" size="sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export CSV
                            </x-button>
                        </a>
                    @endcan

                    @can('create-clients')
                        <a href="{{ route('clients.create') }}">
                            <x-button variant="primary" size="sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Client
                            </x-button>
                        </a>
                    @endcan
                </div>
            </div>

            @if($clients->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-text-tertiary dark:text-dark-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-text-primary dark:text-dark-text-primary">No clients found</h3>
                    <p class="mt-1 text-sm text-text-secondary dark:text-dark-text-secondary">
                        @if($filters['search'] || $filters['status'])
                            Try adjusting your search or filter criteria.
                        @else
                            Get started by creating your first client.
                        @endif
                    </p>
                    @can('create-clients')
                        @if(!$filters['search'] && !$filters['status'])
                            <div class="mt-6">
                                <a href="{{ route('clients.create') }}">
                                    <x-button variant="primary" size="md">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add Your First Client
                                    </x-button>
                                </a>
                            </div>
                        @endif
                    @endcan
                </div>
            @else
                <!-- Clients Table (Desktop) -->
                <div class="hidden lg:block">
                    <x-table :headers="['Name', 'Email', 'Phone', 'Status', 'Created', 'Actions']">
                        @foreach($clients as $client)
                            <tr class="hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors cursor-pointer" onclick="window.location='{{ route('clients.show', $client) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-accent-primary flex items-center justify-center text-white font-semibold mr-3">
                                            {{ strtoupper(substr($client->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-text-primary dark:text-dark-text-primary">{{ $client->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-text-secondary dark:text-dark-text-secondary">{{ $client->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-text-secondary dark:text-dark-text-secondary">{{ $client->phone ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($client->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-accent-success text-white">
                                            Active
                                        </span>
                                    @elseif($client->status === 'inactive')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-text-tertiary dark:bg-dark-text-tertiary text-white">
                                            Inactive
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-accent-warning text-white">
                                            Archived
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-text-tertiary dark:text-dark-text-tertiary text-sm">{{ $client->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4" onclick="event.stopPropagation()">
                                    <div class="flex items-center gap-2">
                                        @can('edit-clients')
                                            <a href="{{ route('clients.edit', $client) }}" class="text-accent-primary hover:text-accent-primary/80">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endcan

                                        @can('delete-clients')
                                            <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Are you sure you want to delete this client?')">
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

                <!-- Clients Cards (Mobile/Tablet) -->
                <div class="lg:hidden space-y-4">
                    @foreach($clients as $client)
                        <div class="bg-bg-secondary dark:bg-dark-bg-secondary border border-border-medium dark:border-dark-border-medium rounded-lg p-4">
                            <a href="{{ route('clients.show', $client) }}" class="block">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 rounded-full bg-accent-primary flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-text-primary dark:text-dark-text-primary truncate">{{ $client->name }}</h3>
                                        @if($client->status === 'active')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-accent-success text-white">
                                                Active
                                            </span>
                                        @elseif($client->status === 'inactive')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-text-tertiary dark:bg-dark-text-tertiary text-white">
                                                Inactive
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-accent-warning text-white">
                                                Archived
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex items-center text-text-secondary dark:text-dark-text-secondary">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="truncate">{{ $client->email ?? 'No email' }}</span>
                                    </div>
                                    <div class="flex items-center text-text-secondary dark:text-dark-text-secondary">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span>{{ $client->phone ?? 'No phone' }}</span>
                                    </div>
                                    <div class="flex items-center text-text-tertiary dark:text-dark-text-tertiary text-xs">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Created {{ $client->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </a>
                            <div class="flex gap-2 mt-3 pt-3 border-t border-border-light dark:border-dark-border-light">
                                @can('edit-clients')
                                    <a href="{{ route('clients.edit', $client) }}" class="flex-1 flex items-center justify-center px-3 py-2 bg-accent-primary text-white rounded-lg text-sm font-medium hover:bg-accent-primary/90 transition-colors min-h-[44px]">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                @endcan
                                @can('delete-clients')
                                    <form method="POST" action="{{ route('clients.destroy', $client) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this client?')">
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
                    {{ $clients->links() }}
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
