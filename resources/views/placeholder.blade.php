<x-app-layout>
    <x-slot name="header">
        {{ $module }}
    </x-slot>

    <x-slot name="description">
        This module is coming soon
    </x-slot>

    <x-card>
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-text-tertiary dark:text-dark-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-text-primary dark:text-dark-text-primary">{{ $module }} Module</h3>
            <p class="mt-1 text-sm text-text-secondary dark:text-dark-text-secondary">
                This feature will be implemented in the next phase of development.
            </p>
            <div class="mt-6">
                <a href="{{ route('dashboard') }}">
                    <x-button variant="primary" size="md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Back to Dashboard
                    </x-button>
                </a>
            </div>
        </div>
    </x-card>
</x-app-layout>
