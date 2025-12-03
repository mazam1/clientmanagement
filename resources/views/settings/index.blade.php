<x-app-layout>
    <x-slot name="header">Settings</x-slot>
    <x-slot name="description">Manage application settings</x-slot>

    <x-card title="Application Settings">
        @if(session('success'))
            <div class="mb-4 p-4 bg-accent-success/10 border border-accent-success/20 rounded-lg text-accent-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Hourly Rate ($)
                    </label>
                    <input type="number" name="hourly_rate" step="0.01" value="{{ old('hourly_rate', $settings['hourly_rate']) }}"
                           class="w-full px-4 py-2 border border-border-medium dark:border-dark-border-medium rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary">
                    @error('hourly_rate')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Tax Rate (%)
                    </label>
                    <input type="number" name="tax_rate" step="0.01" value="{{ old('tax_rate', $settings['tax_rate']) }}"
                           class="w-full px-4 py-2 border border-border-medium dark:border-dark-border-medium rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary">
                    @error('tax_rate')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Company Name
                    </label>
                    <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}"
                           class="w-full px-4 py-2 border border-border-medium dark:border-dark-border-medium rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary">
                    @error('company_name')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Company Email
                    </label>
                    <input type="email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}"
                           class="w-full px-4 py-2 border border-border-medium dark:border-dark-border-medium rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary">
                    @error('company_email')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Company Phone
                    </label>
                    <input type="text" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}"
                           class="w-full px-4 py-2 border border-border-medium dark:border-dark-border-medium rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary">
                    @error('company_phone')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-text-primary dark:text-dark-text-primary mb-2">
                        Company Address
                    </label>
                    <textarea name="company_address" rows="3"
                              class="w-full px-4 py-2 border border-border-medium dark:border-dark-border-medium rounded-lg bg-bg-primary dark:bg-dark-bg-primary text-text-primary dark:text-dark-text-primary focus:ring-2 focus:ring-accent-primary">{{ old('company_address', $settings['company_address']) }}</textarea>
                    @error('company_address')
                        <p class="mt-1 text-sm text-accent-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-text-primary dark:bg-dark-text-primary text-bg-primary dark:text-dark-bg-primary rounded-lg hover:opacity-90 transition-opacity">
                    Save Settings
                </button>
            </div>
        </form>
    </x-card>
</x-app-layout>
