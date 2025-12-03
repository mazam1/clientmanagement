<!-- Topbar -->
<header class="fixed top-0 right-0 left-0 ml-sidebar h-16 bg-bg-primary dark:bg-dark-bg-primary border-b border-border-light dark:border-dark-border-light z-30 transition-all duration-200">
    <div class="h-full px-6 flex items-center justify-between">
        <!-- Left Section: Breadcrumbs -->
        <div class="flex items-center space-x-2 text-sm">
            <a href="{{ route('dashboard') }}" class="text-text-secondary dark:text-dark-text-secondary hover:text-text-primary dark:hover:text-dark-text-primary transition-colors">
                Home
            </a>
            @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                @foreach($breadcrumbs as $breadcrumb)
                    <span class="text-text-tertiary dark:text-dark-text-tertiary">/</span>
                    @if(!$loop->last)
                        <a href="{{ $breadcrumb['url'] }}" class="text-text-secondary dark:text-dark-text-secondary hover:text-text-primary dark:hover:text-dark-text-primary transition-colors">
                            {{ $breadcrumb['label'] }}
                        </a>
                    @else
                        <span class="text-text-primary dark:text-dark-text-primary font-medium">
                            {{ $breadcrumb['label'] }}
                        </span>
                    @endif
                @endforeach
            @endif
        </div>

        <!-- Right Section: Actions -->
        <div class="flex items-center gap-4">
            <!-- Search Bar -->
            <div class="relative hidden md:block">
                <input
                    type="search"
                    placeholder="Search..."
                    class="w-64 h-9 pl-10 pr-4 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-secondary dark:bg-dark-bg-secondary text-text-primary dark:text-dark-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent transition-all"
                />
                <svg class="absolute left-3 top-2.5 w-4 h-4 text-text-tertiary dark:text-dark-text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <!-- Theme Toggle -->
            <button
                id="theme-toggle"
                onclick="ThemeManager.toggle()"
                class="p-2 rounded-lg text-text-secondary dark:text-dark-text-secondary hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors"
                aria-label="Toggle theme"
            >
                <!-- Sun Icon (shown in dark mode) -->
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <!-- Moon Icon (shown in light mode) -->
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>

            <!-- Notifications -->
            <button class="relative p-2 rounded-lg text-text-secondary dark:text-dark-text-secondary hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <!-- Badge -->
                <span class="absolute top-1 right-1 w-2 h-2 bg-accent-danger rounded-full"></span>
            </button>

            <!-- User Dropdown -->
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button
                    @click="open = !open"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors"
                >
                    <div class="w-8 h-8 rounded-full bg-accent-primary flex items-center justify-center text-white text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden lg:block text-sm font-medium text-text-primary dark:text-dark-text-primary">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4 text-text-secondary dark:text-dark-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-bg-primary dark:bg-dark-bg-primary border border-border-light dark:border-dark-border-light py-1"
                    style="display: none;"
                >
                    <div class="px-4 py-3 border-b border-border-light dark:border-dark-border-light">
                        <p class="text-sm font-medium text-text-primary dark:text-dark-text-primary">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-text-tertiary dark:text-dark-text-tertiary mt-0.5">{{ auth()->user()->email }}</p>
                        <p class="text-xs text-text-tertiary dark:text-dark-text-tertiary mt-0.5">{{ auth()->user()->roles->first()?->name ?? 'User' }}</p>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-text-secondary dark:text-dark-text-secondary hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-accent-danger hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors text-left">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
