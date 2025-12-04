<!-- Topbar -->
<header class="fixed top-0 right-0 left-0 lg:ml-sidebar h-16 bg-bg-primary dark:bg-dark-bg-primary border-b border-border-light dark:border-dark-border-light z-30 transition-all duration-200">
    <div class="h-full px-6 flex items-center justify-between">
        <!-- Left Section: Mobile/Tablet Menu + Breadcrumbs -->
        <div class="flex items-center gap-4">
            <!-- Hamburger Menu Button (Mobile/Tablet Only) -->
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2.5 rounded-lg text-text-secondary dark:text-dark-text-secondary hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center"
                aria-label="Toggle menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Breadcrumbs -->
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
        </div>

        <!-- Right Section: Actions -->
        <div class="flex items-center gap-4">
            <!-- Search Bar -->
            <div
                x-data="globalSearch()"
                @click.away="showDropdown = false"
                class="relative hidden lg:block"
            >
                <input
                    type="search"
                    x-model="query"
                    @input="performSearch()"
                    placeholder="Search clients, sessions, invoices..."
                    class="w-80 h-10 pl-10 pr-10 rounded-lg border border-border-medium dark:border-dark-border-medium bg-bg-secondary dark:bg-dark-bg-secondary text-text-primary dark:text-dark-text-primary text-sm focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent transition-all"
                />
                <svg class="absolute left-3 top-3 w-4 h-4 text-text-tertiary dark:text-dark-text-tertiary pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>

                <!-- Loading Spinner -->
                <div x-show="isSearching" class="absolute right-3 top-3 pointer-events-none" style="display: none;">
                    <svg class="animate-spin h-4 w-4 text-accent-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <!-- Search Results Dropdown -->
                <div
                    x-show="showDropdown"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute top-full left-0 right-0 mt-2 bg-bg-primary dark:bg-dark-bg-primary border border-border-medium dark:border-dark-border-medium rounded-lg shadow-xl max-h-96 overflow-y-auto z-50 min-w-max"
                    style="display: none;"
                >
                    <div class="py-2">
                        <template x-for="result in results" :key="result.url">
                            <a
                                :href="result.url"
                                class="flex items-start gap-3 px-4 py-3 hover:bg-bg-secondary dark:hover:bg-dark-bg-secondary transition-colors"
                            >
                                <!-- Icon -->
                                <div class="flex-shrink-0 mt-0.5 text-text-tertiary dark:text-dark-text-tertiary">
                                    <template x-if="result.type === 'client'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="result.type === 'session'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="result.type === 'invoice'">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </template>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-sm font-medium text-text-primary dark:text-dark-text-primary" x-text="result.title"></p>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                            :class="{
                                                'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200': result.type === 'client',
                                                'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200': result.type === 'session',
                                                'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200': result.type === 'invoice'
                                            }"
                                            x-text="result.type === 'client' ? 'Client' : result.type === 'session' ? 'Session' : 'Invoice'"
                                        ></span>
                                    </div>
                                    <p class="text-xs text-text-tertiary dark:text-dark-text-tertiary" x-text="result.subtitle"></p>
                                </div>
                            </a>
                        </template>

                        <!-- No Results -->
                        <template x-if="!isSearching && results.length === 0 && query.length >= 2">
                            <div class="px-4 py-8 text-center">
                                <svg class="w-12 h-12 mx-auto text-text-tertiary dark:text-dark-text-tertiary mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-text-secondary dark:text-dark-text-secondary">No results found</p>
                                <p class="text-xs text-text-tertiary dark:text-dark-text-tertiary mt-1">Try a different search term</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <script>
                function globalSearch() {
                    return {
                        query: '',
                        results: [],
                        isSearching: false,
                        showDropdown: false,
                        searchTimeout: null,

                        performSearch() {
                            clearTimeout(this.searchTimeout);

                            if (this.query.length < 2) {
                                this.results = [];
                                this.showDropdown = false;
                                return;
                            }

                            this.isSearching = true;
                            this.showDropdown = true;

                            this.searchTimeout = setTimeout(() => {
                                fetch('{{ route('search') }}?query=' + encodeURIComponent(this.query))
                                    .then(response => response.json())
                                    .then(data => {
                                        this.results = data;
                                        this.isSearching = false;
                                    })
                                    .catch(error => {
                                        console.error('Search error:', error);
                                        this.results = [];
                                        this.isSearching = false;
                                    });
                            }, 1000);
                        }
                    }
                }
            </script>

            <!-- Theme Toggle -->
            <button
                id="theme-toggle"
                onclick="ThemeManager.toggle()"
                class="p-2.5 rounded-lg text-text-secondary dark:text-dark-text-secondary hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center"
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
            <button class="relative p-2.5 rounded-lg text-text-secondary dark:text-dark-text-secondary hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center">
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
