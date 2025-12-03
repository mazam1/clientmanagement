<x-app-layout>
    <x-slot name="header">
        Invoice #{{ $invoice->invoice_number }}
    </x-slot>

    <x-slot name="description">
        Invoice details and sessions
    </x-slot>

    <div class="space-y-6">
        <!-- Invoice Info Card -->
        <x-card title="Invoice Information">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Invoice Number</label>
                    <p class="text-text-primary dark:text-dark-text-primary font-mono">{{ $invoice->invoice_number }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Client</label>
                    <a href="{{ route('clients.show', $invoice->client_id) }}" class="text-accent-primary hover:underline">
                        {{ $invoice->client->name }}
                    </a>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Invoice Date</label>
                    <p class="text-text-primary dark:text-dark-text-primary">{{ $invoice->issued_at->format('F d, Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Payment Status</label>
                    @if($invoice->payment_status === 'paid')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-accent-success text-white">
                            Paid
                        </span>
                    @elseif($invoice->payment_status === 'partial')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-accent-warning text-white">
                            Partial
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-accent-danger text-white">
                            Unpaid
                        </span>
                    @endif
                </div>

                @if($invoice->payment_date)
                    <div>
                        <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Payment Date</label>
                        <p class="text-text-primary dark:text-dark-text-primary">{{ $invoice->payment_date->format('F d, Y') }}</p>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-text-tertiary dark:text-dark-text-tertiary mb-1">Created</label>
                    <p class="text-text-primary dark:text-dark-text-primary">{{ $invoice->created_at->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                @can('view-invoices')
                    <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank">
                        <x-button variant="primary" size="md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Invoice
                        </x-button>
                    </a>
                @endcan

                @can('edit-invoices')
                    <a href="{{ route('invoices.edit', $invoice->id) }}">
                        <x-button variant="secondary" size="md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Invoice
                        </x-button>
                    </a>
                @endcan

                @if($invoice->payment_status !== 'paid')
                    @can('edit-invoices')
                        <form method="POST" action="{{ route('invoices.mark-paid', $invoice->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <x-button type="submit" variant="success" size="md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Mark as Paid
                            </x-button>
                        </form>
                    @endcan
                @else
                    @can('edit-invoices')
                        <form method="POST" action="{{ route('invoices.mark-unpaid', $invoice->id) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <x-button type="submit" variant="secondary" size="md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Mark as Unpaid
                            </x-button>
                        </form>
                    @endcan
                @endif

                <a href="{{ route('invoices.index') }}">
                    <x-button variant="secondary" size="md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Invoices
                    </x-button>
                </a>

                @can('delete-invoices')
                    <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" onsubmit="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.')" class="ml-auto">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger" size="md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </x-button>
                    </form>
                @endcan
            </div>
        </x-card>

        <!-- Invoice Summary Card -->
        <x-card title="Invoice Summary">
            <div class="space-y-4">
                <div class="flex justify-between items-center pb-3 border-b border-border-medium dark:border-dark-border-medium">
                    <span class="text-text-secondary dark:text-dark-text-secondary">Total Hours</span>
                    <span class="font-medium text-text-primary dark:text-dark-text-primary">
                        {{ number_format($invoice->sessions()->sum('duration_minutes') / 60, 2) }} hours
                    </span>
                </div>

                <div class="flex justify-between items-center pb-3 border-b border-border-medium dark:border-dark-border-medium">
                    <span class="text-text-secondary dark:text-dark-text-secondary">Hourly Rate</span>
                    <span class="font-medium text-text-primary dark:text-dark-text-primary">
                        ${{ number_format($invoice->hourly_rate ?? 0, 2) }}
                    </span>
                </div>

                <div class="flex justify-between items-center pb-3 border-b border-border-medium dark:border-dark-border-medium">
                    <span class="text-text-secondary dark:text-dark-text-secondary">Subtotal</span>
                    <span class="font-medium text-text-primary dark:text-dark-text-primary">
                        ${{ number_format($invoice->subtotal, 2) }}
                    </span>
                </div>

                @if($invoice->tax_rate > 0)
                    <div class="flex justify-between items-center pb-3 border-b border-border-medium dark:border-dark-border-medium">
                        <span class="text-text-secondary dark:text-dark-text-secondary">
                            Tax ({{ number_format($invoice->tax_rate, 2) }}%)
                        </span>
                        <span class="font-medium text-text-primary dark:text-dark-text-primary">
                            ${{ number_format($invoice->tax_amount, 2) }}
                        </span>
                    </div>
                @endif

                <div class="flex justify-between items-center pt-3">
                    <span class="text-lg font-semibold text-text-primary dark:text-dark-text-primary">Total Amount</span>
                    <span class="text-2xl font-bold text-accent-primary">
                        ${{ number_format($invoice->total_amount, 2) }}
                    </span>
                </div>
            </div>
        </x-card>

        <!-- Sessions List -->
        <x-card title="Included Sessions ({{ $invoice->sessions()->count() }})">
            @if($invoice->sessions()->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-border-medium dark:border-dark-border-medium">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-text-primary dark:text-dark-text-primary">Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-text-primary dark:text-dark-text-primary">Duration</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-text-primary dark:text-dark-text-primary">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light dark:divide-dark-border-light">
                            @foreach($invoice->sessions() as $session)
                                <tr class="hover:bg-bg-secondary dark:hover:bg-dark-bg-secondary transition-colors">
                                    <td class="px-4 py-3 text-sm text-text-primary dark:text-dark-text-primary">
                                        {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-text-primary dark:text-dark-text-primary">
                                        {{ $session->duration_minutes }} min
                                    </td>
                                    <td class="px-4 py-3 text-sm text-text-secondary dark:text-dark-text-secondary">
                                        {{ $session->notes ?: '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-text-tertiary dark:text-dark-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="mt-2 text-text-secondary dark:text-dark-text-secondary">No sessions found</p>
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
