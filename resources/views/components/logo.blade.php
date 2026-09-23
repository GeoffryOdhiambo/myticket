@props(['dark' => false, 'size' => 'text-2xl'])

@if($dark)
    {{-- The logo image's dark strokes disappear against a dark sidebar/header, so dark contexts get the text wordmark instead. --}}
    <span {{ $attributes->merge(['class' => "inline-flex items-center font-extrabold tracking-tight $size text-white"]) }}>
        My<span class="text-brand">Ticket</span>
    </span>
@else
    <picture>
        <source srcset="{{ asset('images/logo.webp') }}" type="image/webp">
        <img src="{{ asset('images/logo.png') }}" alt="MyTicket" {{ $attributes->merge(['class' => 'h-10 w-auto sm:h-12']) }}>
    </picture>
@endif
