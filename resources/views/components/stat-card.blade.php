@props(['title', 'value', 'icon' => null, 'color' => 'primary', 'trend' => null])

@php
$colorClasses = [
    'primary' => 'text-accent-primary bg-accent-primary/10',
    'success' => 'text-accent-success bg-accent-success/10',
    'warning' => 'text-accent-warning bg-accent-warning/10',
    'danger' => 'text-accent-danger bg-accent-danger/10',
];

$trendColors = [
    'up' => 'text-accent-success',
    'down' => 'text-accent-danger',
    'neutral' => 'text-text-tertiary dark:text-dark-text-tertiary',
];
@endphp

<div class="bg-bg-primary dark:bg-dark-bg-primary border border-border-medium dark:border-dark-border-medium rounded-lg p-6 hover:shadow-md transition-shadow">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-text-secondary dark:text-dark-text-secondary mb-1">{{ $title }}</p>
            <p class="text-3xl font-bold text-text-primary dark:text-dark-text-primary">{{ $value }}</p>
            
            @if($trend)
                <div class="flex items-center mt-2 text-sm {{ $trendColors[$trend['direction']] }}">
                    @if($trend['direction'] === 'up')
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($trend['direction'] === 'down')
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                    <span class="font-medium">{{ $trend['percentage'] }}%</span>
                    <span class="ml-1 text-text-tertiary dark:text-dark-text-tertiary">vs last month</span>
                </div>
            @endif
        </div>
        @if($icon)
            <div class="ml-4 p-3 rounded-lg {{ $colorClasses[$color] ?? $colorClasses['primary'] }}">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
