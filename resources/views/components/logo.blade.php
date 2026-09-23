@props(['dark' => false, 'size' => 'text-2xl'])

@if($dark)
    {{-- The logo image's dark strokes disappear against a dark sidebar/header, so dark contexts get the text wordmark instead. --}}
    <span {{ $attributes->merge(['class' => "inline-flex items-center font-extrabold tracking-tight $size text-white"]) }}>
        My<span class="text-brand">Ticket</span>
    </span>
@else
    <img src="{{ asset('images/logo.png') }}" alt="MyTicket" {{ $attributes->merge(['class' => 'h-8 w-auto sm:h-9']) }}>
@endif
