<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen transition-colors duration-200">
    <div class="container mx-auto px-6 py-12">
        <!-- Header with Dark Mode Toggle -->
        <div class="flex justify-between items-center mb-12">
            <div>
                <h1 class="text-4xl font-bold mb-2" style="color: var(--color-text-primary);">
                    Client Management Dashboard
                </h1>
                <p class="text-lg" style="color: var(--color-text-secondary);">
                    Phase 00: Project Setup Complete ✓
                </p>
            </div>
            
            <!-- Dark Mode Toggle Button -->
            <button 
                id="theme-toggle" 
                class="px-4 py-2 rounded-lg font-medium transition-all duration-200"
                style="background-color: var(--color-text-primary); color: white;"
                onmouseover="this.style.opacity='0.85'"
                onmouseout="this.style.opacity='1'"
            >
                <span id="theme-toggle-text">🌙 Dark Mode</span>
            </button>
        </div>

        <!-- Feature Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <!-- Card 1: Laravel -->
            <div class="p-6 rounded-lg border transition-all duration-200" 
                 style="background-color: var(--color-bg-primary); border-color: var(--color-border-light);">
                <div class="text-3xl mb-3">⚡</div>
                <h3 class="text-xl font-semibold mb-2" style="color: var(--color-text-primary);">
                    Laravel 12
                </h3>
                <p style="color: var(--color-text-secondary);">
                    Latest Laravel framework with PHP 8.2+ support
                </p>
            </div>

            <!-- Card 2: Tailwind CSS -->
            <div class="p-6 rounded-lg border transition-all duration-200" 
                 style="background-color: var(--color-bg-primary); border-color: var(--color-border-light);">
                <div class="text-3xl mb-3">🎨</div>
                <h3 class="text-xl font-semibold mb-2" style="color: var(--color-text-primary);">
                    Tailwind CSS 4.0
                </h3>
                <p style="color: var(--color-text-secondary);">
                    Custom monochrome theme with light/dark mode
                </p>
            </div>

            <!-- Card 3: RBAC -->
            <div class="p-6 rounded-lg border transition-all duration-200" 
                 style="background-color: var(--color-bg-primary); border-color: var(--color-border-light);">
                <div class="text-3xl mb-3">🔐</div>
                <h3 class="text-xl font-semibold mb-2" style="color: var(--color-text-primary);">
                    Spatie Permissions
                </h3>
                <p style="color: var(--color-text-secondary);">
                    Role-based access control ready for implementation
                </p>
            </div>
        </div>

        <!-- Status Section -->
        <div class="p-8 rounded-lg border mb-8" 
             style="background-color: var(--color-bg-primary); border-color: var(--color-border-light);">
            <h2 class="text-2xl font-semibold mb-4" style="color: var(--color-text-primary);">
                ✅ Phase 00 Completed
            </h2>
            <ul class="space-y-2">
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span style="color: var(--color-text-secondary);">Laravel 12.40.2 installed and configured</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span style="color: var(--color-text-secondary);">MySQL database connection verified</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span style="color: var(--color-text-secondary);">spatie/laravel-permission installed with migrations</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span style="color: var(--color-text-secondary);">Tailwind CSS 4.0 configured with monochrome theme</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span style="color: var(--color-text-secondary);">Project directory structure created</span>
                </li>
                <li class="flex items-start">
                    <span class="mr-2">✓</span>
                    <span style="color: var(--color-text-secondary);">Dark mode support enabled</span>
                </li>
            </ul>
        </div>

        <!-- Color Palette Demo -->
        <div class="p-8 rounded-lg border" 
             style="background-color: var(--color-bg-primary); border-color: var(--color-border-light);">
            <h2 class="text-2xl font-semibold mb-4" style="color: var(--color-text-primary);">
                Monochrome Color Palette
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="w-full h-16 rounded mb-2" style="background-color: var(--color-bg-primary); border: 1px solid var(--color-border-medium);"></div>
                    <p class="text-sm" style="color: var(--color-text-secondary);">Primary BG</p>
                </div>
                <div class="text-center">
                    <div class="w-full h-16 rounded mb-2" style="background-color: var(--color-bg-secondary);"></div>
                    <p class="text-sm" style="color: var(--color-text-secondary);">Secondary BG</p>
                </div>
                <div class="text-center">
                    <div class="w-full h-16 rounded mb-2" style="background-color: var(--color-text-primary);"></div>
                    <p class="text-sm" style="color: var(--color-text-secondary);">Text Primary</p>
                </div>
                <div class="text-center">
                    <div class="w-full h-16 rounded mb-2" style="background-color: var(--color-accent-primary);"></div>
                    <p class="text-sm" style="color: var(--color-text-secondary);">Accent</p>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="mt-8 p-6 rounded-lg" style="background-color: var(--color-bg-tertiary);">
            <h3 class="text-lg font-semibold mb-2" style="color: var(--color-text-primary);">
                📋 Next Phase
            </h3>
            <p style="color: var(--color-text-secondary);">
                Phase 01: Authentication & RBAC Foundation
            </p>
        </div>
    </div>

    <script>
        // Dark mode toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleText = document.getElementById('theme-toggle-text');
        const html = document.documentElement;

        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            html.classList.add('dark');
            themeToggleText.textContent = '☀️ Light Mode';
        }

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            
            if (html.classList.contains('dark')) {
                localStorage.setItem('theme', 'dark');
                themeToggleText.textContent = '☀️ Light Mode';
            } else {
                localStorage.setItem('theme', 'light');
                themeToggleText.textContent = '🌙 Dark Mode';
            }
        });
    </script>
</body>
</html>
