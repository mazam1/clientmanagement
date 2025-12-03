@props(['headers' => [], 'striped' => false])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full']) }}>
        @if(count($headers) > 0)
        <thead class="bg-bg-tertiary dark:bg-dark-bg-tertiary">
            <tr>
                @foreach($headers as $header)
                <th class="px-6 py-3 text-left text-xs font-semibold text-text-secondary dark:text-dark-text-secondary uppercase tracking-wider border-b border-border-light dark:border-dark-border-light">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        @endif
        <tbody class="divide-y divide-border-light dark:divide-dark-border-light">
            {{ $slot }}
        </tbody>
    </table>
</div>
