@props(['title' => null, 'padding' => true])

<div {{ $attributes->merge(['class' => 'bg-bg-primary dark:bg-dark-bg-primary border border-border-light dark:border-dark-border-light rounded-lg shadow-sm transition-colors duration-200']) }}>
    @if($title)
    <div class="px-6 py-4 border-b border-border-light dark:border-dark-border-light">
        <h3 class="text-lg font-semibold text-text-primary dark:text-dark-text-primary">{{ $title }}</h3>
    </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
</div>
