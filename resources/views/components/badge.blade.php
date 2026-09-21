@props(['color' => 'neutral'])

@php
    $colors = [
        'brand' => 'bg-brand-light text-brand-dark',
        'success' => 'bg-emerald-50 text-emerald-700',
        'warning' => 'bg-amber-50 text-amber-700',
        'danger' => 'bg-red-50 text-red-700',
        'neutral' => 'bg-neutral-100 text-neutral-600',
        'dark' => 'bg-neutral-900 text-white',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold ' . ($colors[$color] ?? $colors['neutral'])]) }}>
    {{ $slot }}
</span>
