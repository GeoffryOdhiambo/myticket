@props(['dark' => false, 'size' => 'text-2xl'])

<span {{ $attributes->merge(['class' => "inline-flex items-center font-extrabold tracking-tight $size " . ($dark ? 'text-white' : 'text-neutral-900')]) }}>
    Tik<span class="text-brand">o</span>
</span>
