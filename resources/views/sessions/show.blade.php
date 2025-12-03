<x-app-layout>
    <x-slot name="title">Session Details</x-slot>
    <x-slot name="description">View session information</x-slot>

    <x-slot name="action">
        <div class="flex gap-3">
            @can('edit-sessions')
                <x-button href="{{ route('sessions.edit', $session->id) }}" variant="secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Session
                </x-button>
            @endcan
            @can('delete-sessions')
                <form method="POST" action="{{ route('sessions.destroy', $session->id) }}" onsubmit="return confirm('Are you sure you want to delete this session?');">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" variant="danger">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </x-button>
                </form>
            @endcan
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <!-- Session Information -->
        <x-card class="mb-6">
            <div class="space-y-6">
                <!-- Client -->
                <div class="flex items-start justify-between pb-6 border-b border-border-primary dark:border-dark-border-primary">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-full bg-accent-primary flex items-center justify-center text-white text-2xl font-semibold">
                                {{ substr($session->client->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-text-primary dark:text-dark-text-primary">
                                {{ $session->client->name }}
                            </h3>
                            <p class="text-sm text-text-secondary dark:text-dark-text-secondary">
                                Client
                            </p>
                            @can('view-clients')
                                <a href="{{ route('clients.show', $session->client->id) }}" class="text-sm text-accent-primary hover:text-accent-secondary mt-1 inline-block">
                                    View Client Profile →
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Session Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Session Date -->
                    <div>
                        <label class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-1">
                            Session Date
                        </label>
                        <p class="text-lg text-text-primary dark:text-dark-text-primary">
                            {{ \Carbon\Carbon::parse($session->session_date)->format('F d, Y') }}
                        </p>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">
                            {{ \Carbon\Carbon::parse($session->session_date)->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Duration -->
                    <div>
                        <label class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-1">
                            Duration
                        </label>
                        <p class="text-lg text-text-primary dark:text-dark-text-primary">
                            {{ $session->duration_minutes }} minutes
                        </p>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">
                            {{ number_format($session->duration_minutes / 60, 1) }} hours
                        </p>
                    </div>

                    <!-- Created At -->
                    <div>
                        <label class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-1">
                            Created
                        </label>
                        <p class="text-lg text-text-primary dark:text-dark-text-primary">
                            {{ $session->created_at->format('M d, Y') }}
                        </p>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">
                            {{ $session->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Last Updated -->
                    <div>
                        <label class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-1">
                            Last Updated
                        </label>
                        <p class="text-lg text-text-primary dark:text-dark-text-primary">
                            {{ $session->updated_at->format('M d, Y') }}
                        </p>
                        <p class="text-sm text-text-secondary dark:text-dark-text-secondary">
                            {{ $session->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <!-- Session Notes -->
                @if($session->notes)
                    <div class="pt-6 border-t border-border-primary dark:border-dark-border-primary">
                        <label class="block text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-2">
                            Session Notes
                        </label>
                        <div class="p-4 bg-bg-secondary dark:bg-dark-bg-secondary rounded-lg">
                            <p class="text-text-primary dark:text-dark-text-primary whitespace-pre-wrap">{{ $session->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </x-card>

        <!-- Back Button -->
        <div class="flex justify-start">
            <x-button href="{{ route('sessions.index') }}" variant="ghost">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Sessions
            </x-button>
        </div>
    </div>
</x-app-layout>
