@php
    $settings = \App\Models\Setting::current();
@endphp

<x-layouts.admin title="Tickets" heading="Tickets">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search ticket number, customer, order..." class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 sm:col-span-2">

        <select name="check_in" class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
            <option value="">All Check-in Status</option>
            <option value="checked_in" @selected(($filters['check_in'] ?? null) === 'checked_in')>Checked In</option>
            <option value="not_checked_in" @selected(($filters['check_in'] ?? null) === 'not_checked_in')>Not Checked In</option>
        </select>

        <div class="sm:col-span-3">
            <x-button type="submit" size="sm">Filter</x-button>
            @if(array_filter($filters))
                <x-button href="{{ route('admin.tickets.index') }}" variant="outline" size="sm">Clear</x-button>
            @endif
        </div>
    </form>

    <x-card padded="{{ false }}" class="mt-6 overflow-hidden">
        @if($tickets->isEmpty())
            <x-empty-state icon="ticket" title="No tickets found." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-neutral-100 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Ticket No.</th>
                            <th class="px-5 py-3">Event</th>
                            <th class="px-5 py-3">Organizer</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Type</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Check-in</th>
                            <th class="px-5 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($tickets as $ticket)
                            @php
                                $order = $ticket->orderItem->order;
                            @endphp
                            <tr>
                                <td class="px-5 py-3.5 font-semibold text-neutral-900">{{ $ticket->ticket_number }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $order->event->name }}</td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $order->event->organizer->business_name }}</td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $order->customer_name }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $ticket->orderItem->ticketType->name }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $settings->formatPrice($ticket->orderItem->unit_price) }}</td>
                                <td class="px-5 py-3.5">
                                    <x-badge :color="$ticket->checked_in ? 'success' : 'neutral'">{{ $ticket->checked_in ? 'Checked In' : 'Not Checked In' }}</x-badge>
                                </td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $ticket->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

    @if($tickets->hasPages())
        <div class="mt-6">{{ $tickets->links() }}</div>
    @endif
</x-layouts.admin>
