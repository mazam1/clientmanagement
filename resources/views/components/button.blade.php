@props(['variant' => 'primary', 'size' => 'md'])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2';

$variants = [
    'primary' => 'bg-text-primary dark:bg-dark-text-primary text-bg-primary dark:text-dark-bg-primary hover:opacity-85 focus:ring-text-primary',
    'secondary' => 'bg-transparent border border-border-medium dark:border-dark-border-medium text-text-primary dark:text-dark-text-primary hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary focus:ring-border-dark',
    'ghost' => 'bg-transparent text-text-secondary dark:text-dark-text-secondary hover:bg-bg-tertiary dark:hover:bg-dark-bg-tertiary focus:ring-border-medium',
    'danger' => 'bg-accent-danger text-white hover:opacity-85 focus:ring-accent-danger',
    'success' => 'bg-accent-success text-white hover:opacity-85 focus:ring-accent-success',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-5 py-2.5 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
    {{ $slot }}
</button>
