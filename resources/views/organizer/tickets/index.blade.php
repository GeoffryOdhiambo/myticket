@php
    $settings = \App\Models\Setting::current();
@endphp

<x-layouts.organizer title="Sales" heading="Sales">
    <form method="GET" action="{{ route('organizer.tickets.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search name, email, phone, order..." class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 lg:col-span-1">

        <select name="event" class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
            <option value="">All Events</option>
            @foreach($events as $event)
                <option value="{{ $event->id }}" @selected(($filters['event'] ?? null) == $event->id)>{{ $event->name }}</option>
            @endforeach
        </select>

        <select name="ticket_type" class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
            <option value="">All Ticket Types</option>
            @foreach($ticketTypes as $type)
                <option value="{{ $type->id }}" @selected(($filters['ticket_type'] ?? null) == $type->id)>{{ $type->event->name }} — {{ $type->name }}</option>
            @endforeach
        </select>

        <select name="payment_status" class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
            <option value="">All Payment Status</option>
            @foreach(['pending' => 'Pending', 'paid' => 'Paid', 'failed' => 'Failed', 'cancelled' => 'Cancelled'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['payment_status'] ?? null) === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <select name="check_in" class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
            <option value="">All Check-in Status</option>
            <option value="checked_in" @selected(($filters['check_in'] ?? null) === 'checked_in')>Checked In</option>
            <option value="not_checked_in" @selected(($filters['check_in'] ?? null) === 'not_checked_in')>Not Checked In</option>
        </select>

        <div class="sm:col-span-2 lg:col-span-3">
            <x-button type="submit" size="sm">Filter</x-button>
            @if(array_filter($filters))
                <x-button href="{{ route('organizer.tickets.index') }}" variant="outline" size="sm">Clear</x-button>
            @endif
        </div>
    </form>

    <x-card padded="{{ false }}" class="mt-6 overflow-hidden">
        @if($items->isEmpty())
            <x-empty-state icon="ticket" title="No tickets found." description="Try adjusting your search or filters." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-neutral-100 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Ticket Type</th>
                            <th class="px-5 py-3">Qty</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Payment</th>
                            <th class="px-5 py-3">Check-in</th>
                            <th class="px-5 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($items as $item)
                            @php
                                $checkedInCount = $item->tickets->where('checked_in', true)->count();
                            @endphp
                            <tr>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-neutral-900">{{ $item->order->customer_name }}</p>
                                    <p class="text-xs text-neutral-400">{{ $item->order->customer_whatsapp }} · {{ $item->order->customer_email }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $item->ticketType->name }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $item->quantity }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $settings->formatPrice($item->subtotal) }}</td>
                                <td class="px-5 py-3.5">
                                    <x-badge :color="match($item->order->status) { 'paid' => 'success', 'pending' => 'warning', default => 'danger' }">
                                        {{ ucfirst($item->order->status) }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-3.5 text-neutral-600">
                                    @if($item->tickets->isEmpty())
                                        —
                                    @else
                                        {{ $checkedInCount }}/{{ $item->tickets->count() }}
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $item->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

    @if($items->hasPages())
        <div class="mt-6">{{ $items->links() }}</div>
    @endif
</x-layouts.organizer>
