<x-app-layout>
    <x-slot name="title">Edit Session</x-slot>
    <x-slot name="description">Update session details</x-slot>

    <div class="max-w-3xl mx-auto">
        <x-card>
            <form method="POST" action="{{ route('sessions.update', $session->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Client Selection -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Client <span class="text-danger">*</span>
                    </label>
                    <select
                        name="client_id"
                        id="client_id"
                        required
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('client_id') border-danger @enderror"
                    >
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $session->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Session Date -->
                <div>
                    <label for="session_date" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Session Date <span class="text-danger">*</span>
                    </label>
                    <input
                        type="date"
                        name="session_date"
                        id="session_date"
                        value="{{ old('session_date', \Carbon\Carbon::parse($session->session_date)->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                        required
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('session_date') border-danger @enderror"
                    />
                    @error('session_date')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-text-secondary dark:text-dark-text-secondary">
                        Session date cannot be in the future
                    </p>
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Duration (minutes) <span class="text-danger">*</span>
                    </label>
                    <input
                        type="number"
                        name="duration_minutes"
                        id="duration_minutes"
                        value="{{ old('duration_minutes', $session->duration_minutes) }}"
                        min="1"
                        max="1440"
                        required
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('duration_minutes') border-danger @enderror"
                    />
                    @error('duration_minutes')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                    <div class="mt-2 flex flex-wrap gap-2">
                        <button type="button" onclick="setDuration(30)" class="text-xs px-3 py-1 bg-bg-secondary dark:bg-dark-bg-secondary text-text-secondary dark:text-dark-text-secondary rounded hover:bg-accent-primary/10 dark:hover:bg-accent-primary/20 hover:text-accent-primary transition-colors">
                            30 min
                        </button>
                        <button type="button" onclick="setDuration(60)" class="text-xs px-3 py-1 bg-bg-secondary dark:bg-dark-bg-secondary text-text-secondary dark:text-dark-text-secondary rounded hover:bg-accent-primary/10 dark:hover:bg-accent-primary/20 hover:text-accent-primary transition-colors">
                            1 hour
                        </button>
                        <button type="button" onclick="setDuration(90)" class="text-xs px-3 py-1 bg-bg-secondary dark:bg-dark-bg-secondary text-text-secondary dark:text-dark-text-secondary rounded hover:bg-accent-primary/10 dark:hover:bg-accent-primary/20 hover:text-accent-primary transition-colors">
                            1.5 hours
                        </button>
                        <button type="button" onclick="setDuration(120)" class="text-xs px-3 py-1 bg-bg-secondary dark:bg-dark-bg-secondary text-text-secondary dark:text-dark-text-secondary rounded hover:bg-accent-primary/10 dark:hover:bg-accent-primary/20 hover:text-accent-primary transition-colors">
                            2 hours
                        </button>
                    </div>
                    <p class="mt-1 text-xs text-text-secondary dark:text-dark-text-secondary">
                        Current: {{ number_format($session->duration_minutes / 60, 1) }} hours
                    </p>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Session Notes
                    </label>
                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        maxlength="1000"
                        placeholder="Enter any notes about this session..."
                        class="w-full px-4 py-2 border border-border-primary dark:border-dark-border-primary rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('notes') border-danger @enderror"
                    >{{ old('notes', $session->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-text-secondary dark:text-dark-text-secondary">
                        Maximum 1000 characters
                    </p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-primary dark:border-dark-border-primary">
                    <x-button href="{{ route('sessions.show', $session->id) }}" variant="ghost">
                        Cancel
                    </x-button>
                    <x-button type="submit" variant="primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Session
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    @push('scripts')
    <script>
        function setDuration(minutes) {
            document.getElementById('duration_minutes').value = minutes;
        }
    </script>
    @endpush
</x-app-layout>
