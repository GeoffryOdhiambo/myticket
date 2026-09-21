@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'bg-brand text-white hover:bg-brand-dark focus-visible:outline-brand',
        'dark' => 'bg-neutral-900 text-white hover:bg-neutral-800 focus-visible:outline-neutral-900',
        'outline' => 'bg-white text-neutral-900 border border-neutral-200 hover:border-neutral-300 hover:bg-neutral-50 focus-visible:outline-neutral-400',
        'ghost' => 'bg-transparent text-neutral-700 hover:bg-neutral-100 focus-visible:outline-neutral-400',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600',
    ];

    $sizes = [
        'sm' => 'px-3.5 py-1.5 text-sm gap-1.5',
        'md' => 'px-5 py-2.5 text-sm gap-2',
        'lg' => 'px-7 py-3.5 text-base gap-2.5',
    ];

    $classes = 'inline-flex items-center justify-center rounded-full font-semibold transition-colors duration-150 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:opacity-50 disabled:pointer-events-none '
        . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
