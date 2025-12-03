<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 1cm;
            }
        }
    </style>
</head>
<body class="bg-white p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Print Button (hidden when printing) -->
        <div class="no-print mb-6 flex justify-end gap-3">
            <button
                onclick="window.print()"
                class="px-4 py-2 bg-accent-primary text-white rounded-lg hover:bg-accent-primary/90 transition-colors flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Invoice
            </button>
            <a
                href="{{ route('invoices.show', $invoice->id) }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Close
            </a>
        </div>

        <!-- Invoice Header -->
        <div class="mb-8 pb-6 border-b-2 border-gray-300">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">INVOICE</h1>
                    <p class="text-lg text-gray-600 font-mono">{{ $invoice->invoice_number }}</p>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ config('app.name', 'Client Management') }}</h2>
                    <p class="text-gray-600">Invoice Date: {{ $invoice->issued_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Bill To Section -->
        <div class="mb-8 grid grid-cols-2 gap-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Bill To</h3>
                <div class="text-gray-900">
                    <p class="font-semibold text-lg">{{ $invoice->client->name }}</p>
                    @if($invoice->client->email)
                        <p class="text-gray-600">{{ $invoice->client->email }}</p>
                    @endif
                    @if($invoice->client->phone)
                        <p class="text-gray-600">{{ $invoice->client->phone }}</p>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Payment Status</h3>
                <div>
                    @if($invoice->payment_status === 'paid')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            PAID
                        </span>
                        @if($invoice->payment_date)
                            <p class="text-gray-600 mt-2">Paid on {{ $invoice->payment_date->format('F d, Y') }}</p>
                        @endif
                    @elseif($invoice->payment_status === 'partial')
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            PARTIAL PAYMENT
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                            UNPAID
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sessions Table -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sessions</h3>
            <table class="w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 border-b border-gray-300">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 border-b border-gray-300">Description</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900 border-b border-gray-300">Duration</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900 border-b border-gray-300">Rate</th>
                        <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900 border-b border-gray-300">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $sessions = $invoice->sessions();
                        $hourlyRate = $invoice->hourly_rate ?? 0;
                    @endphp
                    @foreach($sessions as $session)
                        @php
                            $hours = $session->duration_minutes / 60;
                            $amount = $hours * $hourlyRate;
                        @endphp
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $session->notes ?: 'Client session' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                {{ number_format($hours, 2) }} hrs
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                ${{ number_format($hourlyRate, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 text-right">
                                ${{ number_format($amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Invoice Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-80">
                <div class="space-y-2">
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>

                    @if($invoice->tax_rate > 0)
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Tax ({{ number_format($invoice->tax_rate, 2) }}%):</span>
                            <span class="font-medium text-gray-900">${{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-3 border-t-2 border-gray-300">
                        <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                        <span class="text-2xl font-bold text-gray-900">${{ number_format($invoice->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 pt-6 border-t border-gray-300 text-center text-gray-600 text-sm">
            <p>Thank you for your business!</p>
            <p class="mt-1">{{ config('app.name', 'Client Management') }} - Generated on {{ now()->format('F d, Y') }}</p>
        </div>
    </div>

    <script>
        // Auto-print when opened in a new window (optional)
        // window.onload = function() { window.print(); };
    </script>
</body>
</html>
