<x-app-layout>
    <x-slot name="header">
        Edit Invoice #{{ $invoice->invoice_number }}
    </x-slot>

    <x-slot name="description">
        Update invoice details
    </x-slot>

    <div class="max-w-4xl">
        <x-card>
            <form
                method="POST"
                action="{{ route('invoices.update', $invoice->id) }}"
                class="space-y-6"
                x-data="{
                    clientId: {{ $invoice->client_id }},
                    sessions: {{ $unbilledSessions->merge($selectedSessions)->toJson() }},
                    selectedSessions: {{ json_encode($invoice->session_ids) }},
                    hourlyRate: {{ old('hourly_rate', $invoice->hourly_rate ?? 0) }},
                    taxRate: {{ old('tax_rate', $invoice->tax_rate ?? 0) }},
                    paymentStatus: '{{ old('payment_status', $invoice->payment_status) }}',

                    get totalMinutes() {
                        return this.selectedSessions.reduce((sum, sessionId) => {
                            const session = this.sessions.find(s => s.id == sessionId);
                            return sum + (session ? session.duration_minutes : 0);
                        }, 0);
                    },

                    get totalHours() {
                        return this.totalMinutes / 60;
                    },

                    get subtotal() {
                        return this.totalHours * this.hourlyRate;
                    },

                    get taxAmount() {
                        return this.subtotal * (this.taxRate / 100);
                    },

                    get total() {
                        return this.subtotal + this.taxAmount;
                    },

                    async loadSessions() {
                        if (!this.clientId) {
                            this.sessions = [];
                            this.selectedSessions = [];
                            return;
                        }

                        const response = await fetch(`{{ route('invoices.create') }}?client_id=${this.clientId}`);
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const dataScript = doc.querySelector('[x-data]');

                        if (dataScript) {
                            const match = dataScript.getAttribute('x-data').match(/sessions:\s*(\[.*?\])/s);
                            if (match) {
                                this.sessions = JSON.parse(match[1]);
                                this.selectedSessions = [];
                            }
                        }
                    }
                }"
            >
                @csrf
                @method('PUT')

                <!-- Client Selection -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Client <span class="text-accent-danger">*</span>
                    </label>
                    <select
                        name="client_id"
                        id="client_id"
                        required
                        x-model="clientId"
                        @change="loadSessions()"
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('client_id') border-accent-danger @enderror"
                    >
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sessions Selection -->
                <div x-show="sessions.length > 0">
                    <label class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Select Sessions <span class="text-accent-danger">*</span>
                    </label>
                    <div class="space-y-2 max-h-64 overflow-y-auto p-4 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-secondary dark:bg-dark-bg-secondary">
                        <template x-for="session in sessions" :key="session.id">
                            <label class="flex items-start gap-3 p-3 rounded-lg hover:bg-bg-primary dark:hover:bg-dark-bg-primary cursor-pointer transition-colors">
                                <input
                                    type="checkbox"
                                    name="session_ids[]"
                                    :value="session.id"
                                    x-model="selectedSessions"
                                    class="mt-1 rounded border-border-medium dark:border-dark-border-medium text-accent-primary focus:ring-accent-primary focus:ring-offset-0"
                                />
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-sm font-medium text-text-primary dark:text-dark-text-primary" x-text="new Date(session.session_date).toLocaleDateString()"></span>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-accent-primary/10 text-accent-primary" x-text="`${session.duration_minutes} min`"></span>
                                    </div>
                                    <p class="text-sm text-text-secondary dark:text-dark-text-secondary" x-text="session.notes || 'No notes'"></p>
                                </div>
                            </label>
                        </template>
                    </div>
                    @error('session_ids')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                    @error('session_ids.*')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hourly Rate -->
                <div>
                    <label for="hourly_rate" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Hourly Rate ($) <span class="text-accent-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="hourly_rate"
                        id="hourly_rate"
                        x-model.number="hourlyRate"
                        value="{{ old('hourly_rate', $invoice->hourly_rate ?? 0) }}"
                        min="0"
                        step="0.01"
                        required
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('hourly_rate') border-accent-danger @enderror"
                    />
                    @error('hourly_rate')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tax Rate -->
                <div>
                    <label for="tax_rate" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Tax Rate (%)
                    </label>
                    <input
                        type="number"
                        name="tax_rate"
                        id="tax_rate"
                        x-model.number="taxRate"
                        value="{{ old('tax_rate', $invoice->tax_rate ?? 0) }}"
                        min="0"
                        max="100"
                        step="0.01"
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('tax_rate') border-accent-danger @enderror"
                    />
                    @error('tax_rate')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Invoice Calculation Preview -->
                <div x-show="selectedSessions.length > 0" class="p-4 rounded-lg bg-bg-secondary dark:bg-dark-bg-secondary border border-border-medium dark:border-dark-border-medium">
                    <h3 class="text-sm font-medium text-text-primary dark:text-dark-text-primary mb-3">Invoice Preview</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-text-secondary dark:text-dark-text-secondary">Total Hours:</span>
                            <span class="font-medium text-text-primary dark:text-dark-text-primary" x-text="totalHours.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary dark:text-dark-text-secondary">Subtotal:</span>
                            <span class="font-medium text-text-primary dark:text-dark-text-primary" x-text="'$' + subtotal.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between" x-show="taxRate > 0">
                            <span class="text-text-secondary dark:text-dark-text-secondary">Tax (<span x-text="taxRate.toFixed(2)"></span>%):</span>
                            <span class="font-medium text-text-primary dark:text-dark-text-primary" x-text="'$' + taxAmount.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-border-medium dark:border-dark-border-medium">
                            <span class="font-medium text-text-primary dark:text-dark-text-primary">Total Amount:</span>
                            <span class="font-bold text-lg text-accent-primary" x-text="'$' + total.toFixed(2)"></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div>
                    <label class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Payment Status <span class="text-accent-danger">*</span>
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="payment_status"
                                value="unpaid"
                                x-model="paymentStatus"
                                {{ old('payment_status', $invoice->payment_status) === 'unpaid' ? 'checked' : '' }}
                                class="border-border-medium dark:border-dark-border-medium text-accent-primary focus:ring-accent-primary focus:ring-offset-0"
                            />
                            <span class="text-sm text-text-primary dark:text-dark-text-primary">Unpaid</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="payment_status"
                                value="paid"
                                x-model="paymentStatus"
                                {{ old('payment_status', $invoice->payment_status) === 'paid' ? 'checked' : '' }}
                                class="border-border-medium dark:border-dark-border-medium text-accent-primary focus:ring-accent-primary focus:ring-offset-0"
                            />
                            <span class="text-sm text-text-primary dark:text-dark-text-primary">Paid</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="payment_status"
                                value="partial"
                                x-model="paymentStatus"
                                {{ old('payment_status', $invoice->payment_status) === 'partial' ? 'checked' : '' }}
                                class="border-border-medium dark:border-dark-border-medium text-accent-primary focus:ring-accent-primary focus:ring-offset-0"
                            />
                            <span class="text-sm text-text-primary dark:text-dark-text-primary">Partial</span>
                        </label>
                    </div>
                    @error('payment_status')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Date (conditional) -->
                <div x-show="paymentStatus === 'paid'">
                    <label for="payment_date" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Payment Date <span class="text-accent-danger">*</span>
                    </label>
                    <input
                        type="date"
                        name="payment_date"
                        id="payment_date"
                        value="{{ old('payment_date', $invoice->payment_date?->format('Y-m-d')) }}"
                        :required="paymentStatus === 'paid'"
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('payment_date') border-accent-danger @enderror"
                    />
                    @error('payment_date')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Invoice Date -->
                <div>
                    <label for="issued_at" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Invoice Date <span class="text-accent-danger">*</span>
                    </label>
                    <input
                        type="date"
                        name="issued_at"
                        id="issued_at"
                        value="{{ old('issued_at', $invoice->issued_at->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}"
                        required
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('issued_at') border-accent-danger @enderror"
                    />
                    @error('issued_at')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4">
                    <x-button type="submit" variant="primary" size="md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Invoice
                    </x-button>

                    <a href="{{ route('invoices.show', $invoice->id) }}">
                        <x-button type="button" variant="secondary" size="md">
                            Cancel
                        </x-button>
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
