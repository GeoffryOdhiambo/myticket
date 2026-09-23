@php
    $pillBase = 'inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-semibold transition-colors';
    $pillActive = 'bg-neutral-900 text-white';
    $pillInactive = 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200';
@endphp

<x-layouts.organizer title="Attendees — {{ $event->name }}" heading="Attendees">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-neutral-500">{{ $event->name }} · {{ $event->event_date->format('D, d M Y') }}</p>
        <x-button href="{{ route('organizer.events.attendees.download', $event) }}" variant="outline" size="sm">
            <x-heroicon-o-arrow-down-tray class="h-4 w-4" /> Download PDF
        </x-button>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('organizer.events.attendees', $event) }}" class="{{ $pillBase }} {{ $status === null ? $pillActive : $pillInactive }}">
            All <span>{{ $totalCount }}</span>
        </a>
        <a href="{{ route('organizer.events.attendees', ['event' => $event, 'status' => 'checked_in']) }}" class="{{ $pillBase }} {{ $status === 'checked_in' ? $pillActive : $pillInactive }}">
            Checked In <span>{{ $checkedInCount }}</span>
        </a>
        <a href="{{ route('organizer.events.attendees', ['event' => $event, 'status' => 'pending']) }}" class="{{ $pillBase }} {{ $status === 'pending' ? $pillActive : $pillInactive }}">
            Pending <span>{{ $pendingCount }}</span>
        </a>
    </div>

    <x-card padded="{{ false }}" class="mt-4 overflow-hidden">
        @if($tickets->isEmpty())
            <x-empty-state icon="ticket" title="No attendees found." description="{{ $status ? 'Try a different filter.' : 'Paid tickets for this event will show up here.' }}" />
        @else
            {{-- Mobile: stacked cards --}}
            <div class="divide-y divide-neutral-100 sm:hidden">
                @foreach($tickets as $ticket)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-neutral-900">{{ $ticket->orderItem->order->customer_name }}</p>
                                <p class="mt-0.5 truncate text-xs text-neutral-400">{{ $ticket->ticket_number }} · {{ $ticket->orderItem->ticketType->name }}</p>
                            </div>
                            <x-badge :color="$ticket->checked_in ? 'success' : 'warning'">
                                {{ $ticket->checked_in ? 'Checked In' : 'Pending' }}
                            </x-badge>
                        </div>
                        <div class="mt-1.5 flex items-center justify-between text-xs text-neutral-400">
                            <span>{{ $ticket->orderItem->order->customer_whatsapp }}</span>
                            @if($ticket->checked_in)
                                <span>{{ $ticket->checked_in_at->format('d M, g:i A') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop: table --}}
            <div class="hidden overflow-x-auto sm:block">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-neutral-100 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Ticket No.</th>
                            <th class="px-5 py-3">Guest</th>
                            <th class="px-5 py-3">Ticket Type</th>
                            <th class="px-5 py-3">Phone</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($tickets as $ticket)
                            <tr>
                                <td class="px-5 py-3.5 font-semibold text-neutral-900">{{ $ticket->ticket_number }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $ticket->orderItem->order->customer_name }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $ticket->orderItem->ticketType->name }}</td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $ticket->orderItem->order->customer_whatsapp }}</td>
                                <td class="px-5 py-3.5">
                                    <x-badge :color="$ticket->checked_in ? 'success' : 'warning'">
                                        {{ $ticket->checked_in ? 'Checked In' : 'Pending' }}
                                    </x-badge>
                                    @if($ticket->checked_in)
                                        <span class="ml-2 text-xs text-neutral-400">{{ $ticket->checked_in_at->format('d M, g:i A') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</x-layouts.organizer>
