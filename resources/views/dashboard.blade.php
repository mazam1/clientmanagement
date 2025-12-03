<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <x-slot name="description">
        Welcome to your Client Management Dashboard
    </x-slot>

    <div class="space-y-6">
        <!-- Stats Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Revenue Card -->
            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-secondary dark:text-dark-text-secondary">Total Revenue</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">$0</p>
                        <p class="text-xs text-accent-success mt-1">+0% from last month</p>
                    </div>
                    <div class="w-12 h-12 bg-accent-success/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </x-card>

            <!-- Active Clients Card -->
            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-secondary dark:text-dark-text-secondary">Active Clients</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">0</p>
                        <p class="text-xs text-text-tertiary dark:text-dark-text-tertiary mt-1">Total clients</p>
                    </div>
                    <div class="w-12 h-12 bg-accent-primary/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </x-card>

            <!-- Upcoming Sessions Card -->
            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-secondary dark:text-dark-text-secondary">Upcoming Sessions</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">0</p>
                        <p class="text-xs text-text-tertiary dark:text-dark-text-tertiary mt-1">Next 7 days</p>
                    </div>
                    <div class="w-12 h-12 bg-accent-info/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </x-card>

            <!-- Unpaid Invoices Card -->
            <x-card>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-text-secondary dark:text-dark-text-secondary">Unpaid Invoices</p>
                        <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary mt-2">0</p>
                        <p class="text-xs text-accent-danger mt-1">Requires attention</p>
                    </div>
                    <div class="w-12 h-12 bg-accent-warning/10 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Welcome Card -->
        <x-card title="Welcome back, {{ auth()->user()->name }}!">
            <p class="text-text-secondary dark:text-dark-text-secondary">
                You're successfully logged in to the Client Management Dashboard.
            </p>
        </x-card>

        <!-- Role & Permissions Card -->
        <x-card title="Your Role & Permissions">
            <div class="space-y-4">
                <!-- Role Display -->
                <div>
                    <span class="text-sm font-medium text-text-secondary dark:text-dark-text-secondary">Current Role:</span>
                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-text-primary dark:bg-dark-text-primary text-bg-primary dark:text-dark-bg-primary">
                        {{ auth()->user()->roles->pluck('name')->join(', ') ?: 'No Role' }}
                    </span>
                </div>

                <!-- Permissions List -->
                <div>
                    <h4 class="text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-2">Your Permissions:</h4>
                    <div class="bg-bg-tertiary dark:bg-dark-bg-tertiary rounded-lg p-4">
                        @if(auth()->user()->hasRole('Super Admin'))
                            <div class="flex items-center text-accent-success">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <strong>Super Admin - All Permissions Granted Implicitly</strong>
                            </div>
                            <p class="text-sm text-text-secondary dark:text-dark-text-secondary mt-2">
                                As a Super Admin, you have unrestricted access to all features and permissions in the system.
                            </p>
                        @else
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @forelse(auth()->user()->getAllPermissions() as $permission)
                                    <li class="flex items-center text-sm text-text-primary dark:text-dark-text-primary">
                                        <svg class="w-4 h-4 mr-2 text-accent-success" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $permission->name }}
                                    </li>
                                @empty
                                    <li class="text-sm text-text-tertiary dark:text-dark-text-tertiary col-span-2">No explicit permissions assigned</li>
                                @endforelse
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
