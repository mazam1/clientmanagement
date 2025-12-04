<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-bg-secondary dark:bg-dark-bg-secondary transition-colors duration-200">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen">
            <!-- Sidebar -->
            @include('layouts.partials.sidebar')

            <!-- Main Content Area -->
            <div class="lg:ml-sidebar transition-all duration-200">
                <!-- Topbar -->
                @include('layouts.partials.topbar')

                <!-- Page Content -->
                <main class="pt-16 px-4 py-6 md:px-6 md:py-8">
                    <div class="max-w-7xl mx-auto">
                        <!-- Page Header -->
                        @if(isset($header))
                            <div class="mb-4 md:mb-6">
                                <h1 class="text-2xl md:text-3xl font-semibold text-text-primary dark:text-dark-text-primary mb-2">
                                    {{ $header }}
                                </h1>
                                @if(isset($description))
                                    <p class="text-sm text-text-secondary dark:text-dark-text-secondary">
                                        {{ $description }}
                                    </p>
                                @endif
                            </div>
                        @endif

                        <!-- Flash Messages -->
                        @if(session('success'))
                            <div class="mb-6 p-4 bg-accent-success/10 border border-accent-success/20 rounded-lg text-accent-success">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 p-4 bg-accent-danger/10 border border-accent-danger/20 rounded-lg text-accent-danger">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        <!-- Main Content Slot -->
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
