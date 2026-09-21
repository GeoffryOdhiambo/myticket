@php
    $order = $ticket->orderItem->order;
    $event = $order->event;
    $ticketType = $ticket->orderItem->ticketType;
@endphp

<x-layouts.app title="Ticket {{ $ticket->ticket_number }}">
    <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="text-center">
            @if($ticket->checked_in)
                <x-badge color="neutral">
                    <x-heroicon-o-check-badge class="h-3.5 w-3.5" /> Checked In
                </x-badge>
            @else
                <x-badge color="success">
                    <x-heroicon-o-check-circle class="h-3.5 w-3.5" /> Valid Ticket
                </x-badge>
            @endif
        </div>

        <div class="mt-6 overflow-hidden rounded-3xl border border-neutral-100 bg-white shadow-md">
            <div class="bg-neutral-900 px-8 py-6 text-center">
                <x-logo dark size="text-xl" />
                <p class="mt-1 text-xs font-medium uppercase tracking-widest text-neutral-400">Digital Ticket</p>
            </div>

            <div class="px-8 py-8">
                <p class="text-xs font-semibold uppercase tracking-wide text-brand">{{ $ticketType->name }}</p>
                <h1 class="mt-1 text-2xl font-extrabold text-neutral-900">{{ $event->name }}</h1>

                <div class="mt-6 grid grid-cols-2 gap-5 border-t border-dashed border-neutral-200 pt-6 text-sm sm:grid-cols-3">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-neutral-400">Date</p>
                        <p class="mt-0.5 font-semibold text-neutral-900">{{ $event->event_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-neutral-400">Time</p>
                        <p class="mt-0.5 font-semibold text-neutral-900">{{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-neutral-400">Venue</p>
                        <p class="mt-0.5 font-semibold text-neutral-900">{{ $event->venue }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-neutral-400">Guest</p>
                        <p class="mt-0.5 font-semibold text-neutral-900">{{ $order->customer_name }}</p>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-neutral-400">Ticket No.</p>
                        <p class="mt-0.5 font-semibold text-neutral-900">{{ $ticket->ticket_number }}</p>
                    </div>
                </div>

                <div class="mt-8 flex flex-col items-center border-t border-dashed border-neutral-200 pt-8">
                    <img src="{{ $qrDataUri }}" alt="Ticket QR code" class="h-44 w-44">
                    <p class="mt-3 text-xs text-neutral-400">Present this QR code at the entrance for check-in.</p>

                    @if($ticket->checked_in)
                        <p class="mt-2 text-xs font-semibold text-neutral-500">
                            Checked in {{ $ticket->checked_in_at->format('d M Y, g:i A') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 text-center">
            <x-button href="{{ route('ticket.download', $ticket) }}" variant="outline">
                <x-heroicon-o-arrow-down-tray class="h-4 w-4" /> Download PDF
            </x-button>
        </div>
    </div>
</x-layouts.app>
