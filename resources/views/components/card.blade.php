@props(['padded' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-neutral-100 shadow-sm ' . ($padded ? 'p-6' : '')]) }}>
    {{ $slot }}
</div>
