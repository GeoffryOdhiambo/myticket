@php
    $settings = \App\Models\Setting::current();
@endphp

<x-layouts.admin title="{{ $organizer->business_name }}">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-extrabold text-neutral-900">{{ $organizer->business_name }}</h1>
                <x-badge :color="$organizer->status === 'active' ? 'success' : 'danger'">{{ ucfirst($organizer->status) }}</x-badge>
            </div>
            <p class="mt-1 text-sm text-neutral-500">{{ $organizer->name }} · {{ $organizer->email }} · {{ $organizer->phone }}</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <x-button href="{{ route('admin.organizers.edit', $organizer) }}" variant="outline" size="sm">
                <x-heroicon-o-pencil-square class="h-4 w-4" /> Edit
            </x-button>
            @if($organizer->status === 'active')
                <form method="POST" action="{{ route('admin.organizers.suspend', $organizer) }}" onsubmit="return confirm('Suspend this organizer? They will be logged out and unable to sign in.');">
                    @csrf
                    <x-button type="submit" variant="danger" size="sm">Suspend</x-button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.organizers.activate', $organizer) }}">
                    @csrf
                    <x-button type="submit" size="sm">Activate</x-button>
                </form>
            @endif
        </div>
    </div>

    <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-3">
        <x-stat-card icon="calendar-days" label="Total Events" :value="$events->count()" />
        <x-stat-card icon="ticket" label="Tickets Sold" :value="$ticketsSold" />
        <x-stat-card icon="banknotes" label="Total Sales" :value="$settings->formatPrice($totalSales)" accent="dark" />
    </div>

    <div class="mt-8">
        <h2 class="text-lg font-bold text-neutral-900">Events</h2>
        <x-card padded="{{ false }}" class="mt-4 overflow-hidden">
            @if($events->isEmpty())
                <x-empty-state icon="calendar-days" title="This organizer has not created any events yet." />
            @else
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-neutral-100 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Event</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($events as $event)
                            <tr>
                                <td class="px-5 py-3.5 font-semibold text-neutral-900">{{ $event->name }}</td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $event->event_date->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <x-badge :color="match($event->status) { 'published' => 'success', 'draft' => 'neutral', 'suspended' => 'danger', default => 'warning' }">
                                        {{ ucfirst($event->status) }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.events.show', $event) }}" class="font-semibold text-brand hover:text-brand-dark">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </x-card>
    </div>
</x-layouts.admin>
