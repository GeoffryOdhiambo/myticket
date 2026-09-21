@php
    $settings = \App\Models\Setting::current();
@endphp

<x-layouts.admin title="{{ $event->name }}">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-extrabold text-neutral-900">{{ $event->name }}</h1>
                <x-badge :color="match($event->status) { 'published' => 'success', 'draft' => 'neutral', 'suspended' => 'danger', default => 'warning' }">
                    {{ ucfirst($event->status) }}
                </x-badge>
            </div>
            <p class="mt-1 text-sm text-neutral-500">
                {{ $event->event_date->format('D, d M Y') }} · {{ $event->venue }}, {{ $event->location }} ·
                <a href="{{ route('admin.organizers.show', $event->organizer) }}" class="font-semibold text-brand hover:text-brand-dark">{{ $event->organizer->business_name }}</a>
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <x-button href="{{ route('admin.events.edit', $event) }}" variant="outline" size="sm">
                <x-heroicon-o-pencil-square class="h-4 w-4" /> Edit
            </x-button>
            @if($event->status === 'published')
                <form method="POST" action="{{ route('admin.events.unpublish', $event) }}">
                    @csrf
                    <x-button type="submit" variant="outline" size="sm">Unpublish</x-button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.events.publish', $event) }}">
                    @csrf
                    <x-button type="submit" size="sm">Publish</x-button>
                </form>
            @endif
            @if($event->status !== 'suspended')
                <form method="POST" action="{{ route('admin.events.suspend', $event) }}" onsubmit="return confirm('Suspend this event? It will be removed from public listings immediately.');">
                    @csrf
                    <x-button type="submit" variant="danger" size="sm">Suspend</x-button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-3">
        <x-stat-card icon="ticket" label="Tickets Sold" :value="$ticketsSold" />
        <x-stat-card icon="banknotes" label="Revenue" :value="$settings->formatPrice($revenue)" accent="dark" />
        <x-stat-card icon="receipt-percent" label="Platform Fee" :value="$settings->formatPrice($settings->platformFee($revenue))" accent="neutral" />
    </div>

    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <h2 class="text-lg font-bold text-neutral-900">About</h2>
            <x-card class="mt-4">
                <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="mb-4 h-48 w-full rounded-xl object-cover">
                <p class="whitespace-pre-line text-sm leading-relaxed text-neutral-600">{{ $event->description }}</p>
            </x-card>
        </div>

        <div>
            <h2 class="text-lg font-bold text-neutral-900">Ticket Types</h2>
            <x-card class="mt-4 space-y-3" padded="{{ true }}">
                @forelse($event->ticketTypes as $type)
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-3 last:border-0 last:pb-0">
                        <div>
                            <p class="text-sm font-semibold text-neutral-900">{{ $type->name }}</p>
                            <p class="text-xs text-neutral-400">{{ $type->quantity_sold }} sold</p>
                        </div>
                        <p class="text-sm font-semibold text-neutral-900">{{ $type->price_label }}</p>
                    </div>
                @empty
                    <p class="text-sm text-neutral-500">No ticket types configured.</p>
                @endforelse
            </x-card>
        </div>
    </div>
</x-layouts.admin>
