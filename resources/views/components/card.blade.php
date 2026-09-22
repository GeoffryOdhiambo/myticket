@props(['padded' => true])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-neutral-100 shadow-sm sm:rounded-2xl ' . ($padded ? 'p-4 sm:p-6' : '')]) }}>
    {{ $slot }}
</div>
