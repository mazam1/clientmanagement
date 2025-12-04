import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // Enable dark mode with class strategy

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Monochrome Light Mode Palette
                'bg-primary': '#FFFFFF',
                'bg-secondary': '#F8F9FA',
                'bg-tertiary': '#F1F3F5',

                'border-light': '#E9ECEF',
                'border-medium': '#DEE2E6',
                'border-dark': '#CED4DA',

                'text-primary': '#212529',
                'text-secondary': '#6C757D',
                'text-tertiary': '#ADB5BD',

                'sidebar-bg': '#1A1D1F',
                'sidebar-text': '#E9ECEF',
                'sidebar-text-muted': '#868E96',
                'sidebar-hover': '#2C3034',
                'sidebar-active': '#343A40',

                // Dark Mode Palette
                'dark-bg-primary': '#121212',
                'dark-bg-secondary': '#1E1E1E',
                'dark-bg-tertiary': '#2A2A2A',

                'dark-border-light': '#333333',
                'dark-border-medium': '#404040',
                'dark-border-dark': '#4D4D4D',

                'dark-text-primary': '#FFFFFF',
                'dark-text-secondary': '#CCCCCC',
                'dark-text-tertiary': '#999999',

                'dark-sidebar-bg': '#0A0A0A',
                'dark-sidebar-text': '#FFFFFF',
                'dark-sidebar-text-muted': '#808080',
                'dark-sidebar-hover': '#1A1A1A',
                'dark-sidebar-active': '#252525',

                // Accent Colors (Strategic use only)
                'accent-primary': '#0066FF',
                'accent-success': '#00C853',
                'accent-warning': '#FFB300',
                'accent-danger': '#FF1744',
                'accent-info': '#00B8D4',
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '104': '26rem',
                'sidebar': '260px',
            },
            width: {
                'sidebar': '260px',
                'sidebar-collapsed': '64px',
            },
            minHeight: {
                'topbar': '64px',
            },
        },
    },

    plugins: [forms],
};
