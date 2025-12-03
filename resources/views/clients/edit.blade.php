<x-app-layout>
    <x-slot name="header">
        Edit Client
    </x-slot>

    <x-slot name="description">
        Update client information
    </x-slot>

    <div class="max-w-3xl">
        <x-card>
            <form method="POST" action="{{ route('clients.update', $client) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Name <span class="text-accent-danger">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name', $client->name) }}"
                        required
                        autofocus
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('name') border-accent-danger @enderror"
                    />
                    @error('name')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email', $client->email) }}"
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('email') border-accent-danger @enderror"
                    />
                    @error('email')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Phone
                    </label>
                    <input
                        type="tel"
                        name="phone"
                        id="phone"
                        value="{{ old('phone', $client->phone) }}"
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('phone') border-accent-danger @enderror"
                    />
                    @error('phone')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Status <span class="text-accent-danger">*</span>
                    </label>
                    <select
                        name="status"
                        id="status"
                        required
                        class="w-full px-4 py-2 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent @error('status') border-accent-danger @enderror"
                    >
                        <option value="active" {{ old('status', $client->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $client->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archived" {{ old('status', $client->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4">
                    <x-button type="submit" variant="primary" size="md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Client
                    </x-button>

                    <a href="{{ route('clients.show', $client) }}">
                        <x-button type="button" variant="secondary" size="md">
                            Cancel
                        </x-button>
                    </a>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
