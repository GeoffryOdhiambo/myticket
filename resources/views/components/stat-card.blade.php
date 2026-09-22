@props(['icon' => 'chart-bar', 'label', 'value', 'accent' => 'brand'])

@php
    $accents = [
        'brand' => 'bg-brand-light text-brand-dark',
        'dark' => 'bg-neutral-900 text-white',
        'success' => 'bg-emerald-50 text-emerald-700',
        'neutral' => 'bg-neutral-100 text-neutral-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border border-neutral-100 bg-white p-3 shadow-sm sm:rounded-2xl sm:p-5']) }}>
    <div class="flex items-center gap-2 sm:gap-3">
        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg sm:h-10 sm:w-10 sm:rounded-xl {{ $accents[$accent] ?? $accents['brand'] }}">
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-3.5 w-3.5 sm:h-5 sm:w-5" />
        </span>
        <p class="truncate text-xs font-medium text-neutral-500 sm:text-sm">{{ $label }}</p>
    </div>
    <p class="mt-2 text-lg font-extrabold text-neutral-900 sm:mt-4 sm:text-2xl">{{ $value }}</p>
</div>
