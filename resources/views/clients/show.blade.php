<x-app-layout>
    <x-slot name="header">
        {{ $client->name }}
    </x-slot>

    <x-slot name="description">
        Client details and activity
    </x-slot>

    <div class="space-y-6">
        <!-- Client Info Card -->
        <x-card title="Client Information">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Name</label>
                    <p class="text-text-primary dark:text-dark-text-primary">{{ $client->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Email</label>
                    <p class="text-text-primary dark:text-dark-text-primary">{{ $client->email ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Phone</label>
                    <p class="text-text-primary dark:text-dark-text-primary">{{ $client->phone ?? '-' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Status</label>
                    @if($client->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-accent-success text-white">
                            Active
                        </span>
                    @elseif($client->status === 'inactive')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-text-tertiary dark:bg-dark-text-tertiary text-white">
                            Inactive
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-accent-warning text-white">
                            Archived
                        </span>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Created</label>
                    <p class="text-text-primary dark:text-dark-text-primary">{{ $client->created_at->format('F d, Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Last Updated</label>
                    <p class="text-text-primary dark:text-dark-text-primary">{{ $client->updated_at->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                @can('edit-clients')
                    <a href="{{ route('clients.edit', $client) }}">
                        <x-button variant="primary" size="md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Client
                        </x-button>
                    </a>
                @endcan

                <a href="{{ route('clients.index') }}">
                    <x-button variant="secondary" size="md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Clients
                    </x-button>
                </a>

                @can('delete-clients')
                    <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('Are you sure you want to delete this client? This action cannot be undone.')" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger" size="md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Client
                        </x-button>
                    </form>
                @endcan
            </div>
        </x-card>

        <!-- Sessions Summary Card -->
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-text-primary dark:text-dark-text-primary">Sessions Summary</h3>
                @can('create-sessions')
                    <x-button href="{{ route('sessions.create', ['client_id' => $client->id]) }}" variant="primary" size="sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Session
                    </x-button>
                @endcan
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="p-4 bg-bg-secondary dark:bg-dark-bg-secondary rounded-lg">
                    <label class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-1">Total Sessions</label>
                    <p class="text-2xl font-bold text-text-primary dark:text-dark-text-primary">{{ $client->clientSessions->count() }}</p>
                </div>

                <div class="p-4 bg-bg-secondary dark:bg-dark-bg-secondary rounded-lg">
                    <label class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-1">Total Billable Hours</label>
                    <p class="text-2xl font-bold text-text-primary dark:text-dark-text-primary">{{ number_format($client->total_billable_hours, 1) }}h</p>
                </div>

                <div class="p-4 bg-bg-secondary dark:bg-dark-bg-secondary rounded-lg">
                    <label class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-1">Total Revenue</label>
                    <p class="text-2xl font-bold text-success">${{ number_format($client->total_revenue, 2) }}</p>
                </div>
            </div>

            @if($client->clientSessions->isNotEmpty())
                <div>
                    <h4 class="text-sm font-semibold text-text-primary dark:text-dark-text-primary mb-4">Recent Sessions</h4>
                    <div class="space-y-2">
                        @foreach($client->clientSessions->sortByDesc('session_date')->take(5) as $session)
                            @can('view-sessions')
                                <a href="{{ route('sessions.show', $session->id) }}" class="block p-4 bg-bg-secondary dark:bg-dark-bg-secondary rounded-lg hover:bg-accent-primary/10 dark:hover:bg-accent-primary/20 transition-colors">
                            @else
                                <div class="block p-4 bg-bg-secondary dark:bg-dark-bg-secondary rounded-lg">
                            @endcan
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <p class="font-medium text-text-primary dark:text-dark-text-primary">
                                                {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}
                                            </p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-accent-primary/10 dark:bg-accent-primary/20 text-accent-primary">
                                                {{ $session->duration_minutes }} min ({{ number_format($session->duration_minutes / 60, 1) }}h)
                                            </span>
                                        </div>
                                        @if($session->notes)
                                            <p class="text-sm text-text-secondary dark:text-dark-text-secondary mt-1 max-w-2xl truncate">{{ $session->notes }}</p>
                                        @endif
                                    </div>
                                    @can('view-sessions')
                                        <svg class="w-5 h-5 text-text-secondary dark:text-dark-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    @endcan
                                </div>
                            @can('view-sessions')
                                </a>
                            @else
                                </div>
                            @endcan
                        @endforeach
                    </div>

                    @if($client->clientSessions->count() > 5)
                        @can('view-sessions')
                            <div class="mt-4 text-center">
                                <a href="{{ route('sessions.index', ['client_id' => $client->id]) }}" class="text-sm text-accent-primary hover:text-accent-secondary font-medium">
                                    View All {{ $client->clientSessions->count() }} Sessions →
                                </a>
                            </div>
                        @endcan
                    @endif
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-text-secondary dark:text-dark-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-text-secondary dark:text-dark-text-secondary mt-2">No sessions recorded yet.</p>
                    @can('create-sessions')
                        <div class="mt-4">
                            <x-button href="{{ route('sessions.create', ['client_id' => $client->id]) }}" variant="primary" size="sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add First Session
                            </x-button>
                        </div>
                    @endcan
                </div>
            @endif
        </x-card>

        <!-- Invoices Summary Card -->
        <x-card title="Invoices Summary">
            @if($client->invoices->isNotEmpty())
                <x-table :headers="['Invoice #', 'Issued Date', 'Amount', 'Status']">
                    @foreach($client->invoices as $invoice)
                        <tr class="hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                            <td class="px-6 py-4 font-medium text-text-primary dark:text-dark-text-primary">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 text-text-secondary dark:text-dark-text-secondary">{{ $invoice->issued_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 font-semibold text-text-primary dark:text-dark-text-primary">${{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @if($invoice->payment_status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-accent-success text-white">
                                        Paid
                                    </span>
                                @elseif($invoice->payment_status === 'partial')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-accent-warning text-white">
                                        Partial
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-accent-danger text-white">
                                        Unpaid
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            @else
                <div class="text-center py-8">
                    <p class="text-text-tertiary dark:text-dark-text-tertiary">No invoices generated yet.</p>
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
