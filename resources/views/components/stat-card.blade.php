@props(['icon' => 'chart-bar', 'label', 'value', 'accent' => 'brand'])

@php
    $accents = [
        'brand' => 'bg-brand-light text-brand-dark',
        'dark' => 'bg-neutral-900 text-white',
        'success' => 'bg-emerald-50 text-emerald-700',
        'neutral' => 'bg-neutral-100 text-neutral-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl border border-neutral-100 bg-white p-5 shadow-sm']) }}>
    <div class="flex items-center gap-3">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl {{ $accents[$accent] ?? $accents['brand'] }}">
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-5 w-5" />
        </span>
        <p class="text-sm font-medium text-neutral-500">{{ $label }}</p>
    </div>
    <p class="mt-4 text-2xl font-extrabold text-neutral-900">{{ $value }}</p>
</div>
