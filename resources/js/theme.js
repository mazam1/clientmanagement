/**
 * Theme Manager
 * Handles dark/light mode toggle with localStorage persistence
 */

const ThemeManager = {
    storageKey: 'theme',

    /**
     * Initialize theme on page load
     */
    init() {
        const savedTheme = this.getTheme();

        if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            this.enableDarkMode();
        } else {
            this.enableLightMode();
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!this.getTheme()) { // Only apply if user hasn't set preference
                e.matches ? this.enableDarkMode() : this.enableLightMode();
            }
        });
    },

    /**
     * Get current theme from localStorage
     */
    getTheme() {
        return localStorage.getItem(this.storageKey);
    },

    /**
     * Set theme in localStorage
     */
    setTheme(theme) {
        localStorage.setItem(this.storageKey, theme);
    },

    /**
     * Enable dark mode
     */
    enableDarkMode() {
        document.documentElement.classList.add('dark');
        this.setTheme('dark');
        this.updateToggleButton();
    },

    /**
     * Enable light mode
     */
    enableLightMode() {
        document.documentElement.classList.remove('dark');
        this.setTheme('light');
        this.updateToggleButton();
    },

    /**
     * Toggle between dark and light mode
     */
    toggle() {
        if (document.documentElement.classList.contains('dark')) {
            this.enableLightMode();
        } else {
            this.enableDarkMode();
        }
    },

    /**
     * Update toggle button icon/text if exists
     */
    updateToggleButton() {
        const button = document.getElementById('theme-toggle');
        if (!button) return;

        const isDark = document.documentElement.classList.contains('dark');
        const icon = button.querySelector('svg');

        if (icon) {
            // Update icon if needed (sun for dark mode, moon for light mode)
            button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        }
    }
};

// Initialize theme on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => ThemeManager.init());
} else {
    ThemeManager.init();
}

// Export for use in components
window.ThemeManager = ThemeManager;
