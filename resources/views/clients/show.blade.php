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
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-text-primary dark:text-dark-text-primary">Invoices</h3>
                @can('create-invoices')
                    <x-button href="{{ route('invoices.create', ['client_id' => $client->id]) }}" variant="primary" size="sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Invoice
                    </x-button>
                @endcan
            </div>

            @if($client->invoices->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-border-medium dark:border-dark-border-medium">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-text-primary dark:text-dark-text-primary">Invoice #</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-text-primary dark:text-dark-text-primary">Issued Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-text-primary dark:text-dark-text-primary">Amount</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-text-primary dark:text-dark-text-primary">Status</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-text-primary dark:text-dark-text-primary">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light dark:divide-dark-border-light">
                            @foreach($client->invoices->sortByDesc('issued_at') as $invoice)
                                <tr class="hover:bg-bg-secondary dark:hover:bg-dark-bg-secondary transition-colors">
                                    <td class="px-4 py-3 text-sm font-medium text-text-primary dark:text-dark-text-primary font-mono">
                                        {{ $invoice->invoice_number }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-text-secondary dark:text-dark-text-secondary">
                                        {{ $invoice->issued_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-text-primary dark:text-dark-text-primary">
                                        ${{ number_format($invoice->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
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
                                    <td class="px-4 py-3 text-sm text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('view-invoices')
                                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-accent-primary hover:text-accent-secondary" title="View">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="text-text-secondary dark:text-dark-text-secondary hover:text-accent-primary dark:hover:text-accent-primary" title="Print">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                    </svg>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($client->invoices->count() > 10)
                    @can('view-invoices')
                        <div class="mt-4 text-center">
                            <a href="{{ route('invoices.index', ['client_id' => $client->id]) }}" class="text-sm text-accent-primary hover:text-accent-secondary font-medium">
                                View All {{ $client->invoices->count() }} Invoices →
                            </a>
                        </div>
                    @endcan
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-text-secondary dark:text-dark-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-text-secondary dark:text-dark-text-secondary mt-2">No invoices generated yet.</p>
                    @can('create-invoices')
                        <div class="mt-4">
                            <x-button href="{{ route('invoices.create', ['client_id' => $client->id]) }}" variant="primary" size="sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create First Invoice
                            </x-button>
                        </div>
                    @endcan
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
