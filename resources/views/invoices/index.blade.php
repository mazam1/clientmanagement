<x-app-layout>
    <x-slot name="title">Invoices</x-slot>
    <x-slot name="description">Manage invoices and billing</x-slot>

    <x-slot name="action">
        @can('create-invoices')
            <x-button href="{{ route('invoices.create') }}" variant="primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Invoice
            </x-button>
        @endcan
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2 lg:grid-cols-4">
        <x-card :padding="false">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Total Revenue</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">
                            ${{ number_format($stats['total_revenue'], 2) }}
                        </p>
                    </div>
                    <div class="p-3 bg-success/10 dark:bg-success/20 rounded-lg">
                        <svg class="w-8 h-8 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card :padding="false">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Unpaid Amount</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">
                            ${{ number_format($stats['unpaid_amount'], 2) }}
                        </p>
                    </div>
                    <div class="p-3 bg-danger/10 dark:bg-danger/20 rounded-lg">
                        <svg class="w-8 h-8 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card :padding="false">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Paid Invoices</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">
                            {{ $stats['paid_invoices'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-accent-primary/10 dark:bg-accent-primary/20 rounded-lg">
                        <svg class="w-8 h-8 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card :padding="false">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">Unpaid Invoices</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">
                            {{ $stats['unpaid_invoices'] }}
                        </p>
                    </div>
                    <div class="p-3 bg-warning/10 dark:bg-warning/20 rounded-lg">
                        <svg class="w-8 h-8 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Search and Filters -->
    <x-card class="mb-6">
        <form method="GET" action="{{ route('invoices.index') }}" class="space-y-4">
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
                        placeholder="Search by invoice # or client..."
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

                <!-- Payment Status Filter -->
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Payment Status
                    </label>
                    <select
                        name="payment_status"
                        id="payment_status"
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent"
                    >
                        <option value="">All Statuses</option>
                        <option value="unpaid" {{ ($filters['payment_status'] ?? '') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="paid" {{ ($filters['payment_status'] ?? '') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ ($filters['payment_status'] ?? '') == 'partial' ? 'selected' : '' }}>Partial</option>
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
            </div>

            <div class="flex flex-wrap gap-3">
                <x-button type="submit" variant="primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Apply Filters
                </x-button>
                <x-button href="{{ route('invoices.index') }}" variant="ghost">
                    Clear Filters
                </x-button>
            </div>
        </form>
    </x-card>

    <!-- Invoices Table -->
    <x-card>
        @if($invoices->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-border-primary dark:border-dark-border-primary">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Invoice #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Issued Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-primary dark:divide-dark-border-primary">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-bg-secondary dark:hover:bg-dark-bg-secondary transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-accent-primary">
                                        {{ $invoice->invoice_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-accent-primary flex items-center justify-center text-white text-sm font-semibold">
                                                {{ substr($invoice->client->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <a href="{{ route('clients.show', $invoice->client->id) }}" class="text-sm font-medium text-text-primary dark:text-dark-text-primary hover:text-accent-primary">
                                                {{ $invoice->client->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-text-primary dark:text-dark-text-primary">
                                        {{ $invoice->issued_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-text-primary dark:text-dark-text-primary">
                                        ${{ number_format($invoice->total_amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($invoice->payment_status === 'paid')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success/10 dark:bg-success/20 text-success">
                                            Paid
                                        </span>
                                    @elseif($invoice->payment_status === 'partial')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 dark:bg-warning/20 text-warning">
                                            Partial
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-danger/10 dark:bg-danger/20 text-danger">
                                            Unpaid
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('view-invoices')
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="text-accent-primary hover:text-accent-secondary">
                                                View
                                            </a>
                                            <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="text-accent-primary hover:text-accent-secondary">
                                                Print
                                            </a>
                                        @endcan
                                        @can('edit-invoices')
                                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="text-accent-primary hover:text-accent-secondary">
                                                Edit
                                            </a>
                                        @endcan
                                        @can('delete-invoices')
                                            <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
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

            <!-- Mobile/Tablet Cards -->
            <div class="lg:hidden space-y-4">
                @foreach($invoices as $invoice)
                    <div class="bg-bg-secondary dark:bg-dark-bg-secondary border border-border-medium dark:border-dark-border-medium rounded-lg p-4">
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="block mb-3">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    <div class="w-12 h-12 rounded-full bg-accent-primary flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                                        {{ substr($invoice->client->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-accent-primary">{{ $invoice->invoice_number }}</p>
                                        <p class="text-base font-semibold text-text-primary dark:text-dark-text-primary truncate">{{ $invoice->client->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-lg font-bold text-text-primary dark:text-dark-text-primary">${{ number_format($invoice->total_amount, 2) }}</p>
                                </div>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-text-secondary dark:text-dark-text-secondary">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $invoice->issued_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($invoice->payment_status === 'paid')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-success/10 dark:bg-success/20 text-success">
                                            Paid
                                        </span>
                                    @elseif($invoice->payment_status === 'partial')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-warning/10 dark:bg-warning/20 text-warning">
                                            Partial
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-danger/10 dark:bg-danger/20 text-danger">
                                            Unpaid
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        <div class="flex gap-2 pt-3 border-t border-border-light dark:border-dark-border-light">
                            @can('view-invoices')
                                <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="flex-1 flex items-center justify-center px-3 py-2 bg-bg-primary dark:bg-dark-bg-primary border border-border-medium dark:border-dark-border-medium text-text-primary dark:text-dark-text-primary rounded-lg text-sm font-medium hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors min-h-[44px]">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                    Print
                                </a>
                            @endcan
                            @can('edit-invoices')
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="flex-1 flex items-center justify-center px-3 py-2 bg-accent-primary text-white rounded-lg text-sm font-medium hover:bg-accent-primary/90 transition-colors min-h-[44px]">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                            @endcan
                            @can('delete-invoices')
                                <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
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
                {{ $invoices->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-text-secondary dark:text-dark-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-text-primary dark:text-dark-text-primary">No invoices found</h3>
                <p class="mt-1 text-sm text-text-secondary dark:text-dark-text-secondary">
                    @if(request()->hasAny(['search', 'client_id', 'payment_status', 'date_from']))
                        Try adjusting your filters to find what you're looking for.
                    @else
                        Get started by creating a new invoice.
                    @endif
                </p>
                @can('create-invoices')
                    <div class="mt-6">
                        <x-button href="{{ route('invoices.create') }}" variant="primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create First Invoice
                        </x-button>
                    </div>
                @endcan
            </div>
        @endif
    </x-card>
</x-app-layout>
