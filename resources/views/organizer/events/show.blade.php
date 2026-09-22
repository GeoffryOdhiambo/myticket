@php
    $settings = \App\Models\Setting::current();
@endphp

<x-layouts.organizer title="{{ $event->name }}">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-extrabold text-neutral-900 sm:text-2xl">{{ $event->name }}</h1>
                <x-badge :color="match($event->status) { 'published' => 'success', 'draft' => 'neutral', 'suspended' => 'danger', default => 'warning' }">
                    {{ ucfirst($event->status) }}
                </x-badge>
            </div>
            <p class="mt-1 text-sm text-neutral-500">{{ $event->event_date->format('D, d M Y') }} · {{ $event->venue }}, {{ $event->location }}</p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if($event->isPublished())
                <a href="{{ route('events.show', $event) }}" target="_blank" rel="noopener">
                    <x-button variant="outline" size="sm"><x-heroicon-o-eye class="h-4 w-4" /> View Live</x-button>
                </a>
                <form method="POST" action="{{ route('organizer.events.unpublish', $event) }}">
                    @csrf
                    <x-button type="submit" variant="outline" size="sm">Unpublish</x-button>
                </form>
            @else
                <form method="POST" action="{{ route('organizer.events.publish', $event) }}">
                    @csrf
                    <x-button type="submit" size="sm">Publish Event</x-button>
                </form>
            @endif
            <x-button href="{{ route('organizer.events.edit', $event) }}" variant="outline" size="sm">
                <x-heroicon-o-pencil-square class="h-4 w-4" /> Edit
            </x-button>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-2.5 sm:gap-4 lg:grid-cols-4">
        <x-stat-card icon="ticket" label="Tickets Sold" :value="$ticketsSold" />
        <x-stat-card icon="banknotes" label="Gross Sales" :value="$settings->formatPrice($grossSales)" />
        <x-stat-card icon="receipt-percent" label="Platform Fee" :value="$settings->formatPrice($platformFee)" accent="neutral" />
        <x-stat-card icon="wallet" label="Your Earnings" :value="$settings->formatPrice($earnings)" accent="dark" />
    </div>

    <div class="mt-6 sm:mt-8">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-neutral-900 sm:text-lg">Ticket Types</h2>
            <x-button href="{{ route('organizer.events.ticket-types.create', $event) }}" size="sm">
                <x-heroicon-o-plus class="h-4 w-4" /> Add Ticket Type
            </x-button>
        </div>

        <x-card padded="{{ false }}" class="mt-4 overflow-hidden">
            @if($event->ticketTypes->isEmpty())
                <x-empty-state icon="ticket" title="No ticket types yet." description="Add at least one ticket type before publishing this event." />
            @else
                <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-neutral-100 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Name</th>
                            <th class="px-5 py-3">Price</th>
                            <th class="px-5 py-3">Sold</th>
                            <th class="px-5 py-3">Available</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($event->ticketTypes as $type)
                            <tr>
                                <td class="px-5 py-3.5 font-semibold text-neutral-900">{{ $type->name }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $type->price_label }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $type->quantity_sold }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $type->available_quantity ?? 'Unlimited' }}</td>
                                <td class="px-5 py-3.5">
                                    <x-badge :color="$type->status === 'active' ? 'success' : 'neutral'">{{ ucfirst($type->status) }}</x-badge>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('organizer.ticket-types.edit', $type) }}" class="font-semibold text-brand hover:text-brand-dark">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @endif
        </x-card>
    </div>

    <div class="mt-6 sm:mt-8">
        <h2 class="text-base font-bold text-neutral-900 sm:text-lg">About</h2>
        <x-card class="mt-4">
            <p class="whitespace-pre-line text-sm leading-relaxed text-neutral-600">{{ $event->description }}</p>
        </x-card>
    </div>
</x-layouts.organizer>
