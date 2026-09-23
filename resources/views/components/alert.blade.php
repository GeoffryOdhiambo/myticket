@props(['type' => 'info'])

@php
    $styles = [
        'success' => ['bg' => 'bg-emerald-50 border-emerald-200 text-emerald-800', 'icon' => 'check-circle'],
        'error' => ['bg' => 'bg-red-50 border-red-200 text-red-800', 'icon' => 'x-circle'],
        'warning' => ['bg' => 'bg-amber-50 border-amber-200 text-amber-800', 'icon' => 'exclamation-triangle'],
        'info' => ['bg' => 'bg-neutral-50 border-neutral-200 text-neutral-800', 'icon' => 'information-circle'],
    ];
    $style = $styles[$type] ?? $styles['info'];
@endphp

<div {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-xl border px-4 py-3.5 text-sm font-medium {$style['bg']}"]) }}>
    <x-dynamic-component :component="'heroicon-o-' . $style['icon']" class="h-5 w-5 shrink-0 mt-0.5" />
    <div>{{ $slot }}</div>
</div>
