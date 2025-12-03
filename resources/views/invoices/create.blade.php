<x-app-layout>
    <x-slot name="header">
        Create Invoice
    </x-slot>

    <x-slot name="description">
        Generate a new invoice from client sessions
    </x-slot>

    <div class="max-w-4xl">
        <x-card>
            <form
                method="POST"
                action="{{ route('invoices.store') }}"
                class="space-y-6"
                x-data="{
                    clientId: {{ $selectedClientId ?? 'null' }},
                    sessions: {{ $unbilledSessions ? $unbilledSessions->toJson() : '[]' }},
                    selectedSessions: [],
                    hourlyRate: {{ $defaultHourlyRate }},
                    taxRate: {{ $defaultTaxRate }},
                    paymentStatus: 'unpaid',

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
                            <option value="{{ $client->id }}" {{ old('client_id', $selectedClientId) == $client->id ? 'selected' : '' }}>
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

                <!-- No Sessions Message -->
                <div x-show="clientId && sessions.length === 0" class="p-4 rounded-lg bg-accent-warning/10 border border-accent-warning/20">
                    <p class="text-sm text-accent-warning">
                        <svg class="inline-block w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        This client has no unbilled sessions. Please create sessions first before generating an invoice.
                    </p>
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
                        value="{{ old('hourly_rate', $defaultHourlyRate) }}"
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
                        value="{{ old('tax_rate', $defaultTaxRate) }}"
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
                                {{ old('payment_status', 'unpaid') === 'unpaid' ? 'checked' : '' }}
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
                                {{ old('payment_status') === 'paid' ? 'checked' : '' }}
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
                                {{ old('payment_status') === 'partial' ? 'checked' : '' }}
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
                        value="{{ old('payment_date') }}"
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
                        value="{{ old('issued_at', date('Y-m-d')) }}"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Create Invoice
                    </x-button>

                    <a href="{{ route('invoices.index') }}">
                        <x-button type="button" variant="secondary" size="md">
                            Cancel
                        </x-button>
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
