@props(['type' => 'success'])

@php
    $styles = [
        'success' => ['bg' => 'bg-emerald-50 border-emerald-200 text-emerald-800', 'icon' => 'check-circle'],
        'error' => ['bg' => 'bg-red-50 border-red-200 text-red-800', 'icon' => 'x-circle'],
    ];
    $style = $styles[$type] ?? $styles['success'];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-x-4 bottom-20 z-50 flex items-start gap-3 rounded-xl border px-4 py-3.5 text-sm font-medium shadow-lg sm:inset-x-auto sm:right-4 sm:w-full sm:max-w-sm lg:bottom-4 {{ $style['bg'] }}"
>
    <x-dynamic-component :component="'heroicon-o-' . $style['icon']" class="h-5 w-5 shrink-0 mt-0.5" />
    <div class="flex-1">{{ $slot }}</div>
    <button type="button" @click="show = false" class="shrink-0 text-current opacity-60 hover:opacity-100" aria-label="Dismiss">
        <x-heroicon-o-x-mark class="h-4 w-4" />
    </button>
</div>
