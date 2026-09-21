<x-layouts.organizer title="Dashboard" heading="Dashboard">
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <x-stat-card icon="calendar-days" label="Total Events" :value="$totalEvents" />
        <x-stat-card icon="check-circle" label="Active Events" :value="$activeEvents" accent="success" />
        <x-stat-card icon="ticket" label="Tickets Sold" :value="$ticketsSold" />
        <x-stat-card icon="banknotes" label="Total Revenue" :value="\App\Models\Setting::current()->formatPrice($totalRevenue)" accent="dark" />
    </div>

    <div class="mt-8">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-neutral-900">Recent Events</h2>
            <x-button href="{{ route('organizer.events.create') }}" size="sm">
                <x-heroicon-o-plus class="h-4 w-4" /> New Event
            </x-button>
        </div>

        <x-card padded="{{ false }}" class="mt-4 overflow-hidden">
            @if($recentEvents->isEmpty())
                <x-empty-state icon="calendar-days" title="You have not created any events yet." description="Create your first event to start selling tickets on Tiko.">
                    <x-slot:action>
                        <x-button href="{{ route('organizer.events.create') }}">Create Event</x-button>
                    </x-slot:action>
                </x-empty-state>
            @else
                <div class="overflow-x-auto">
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
                        @foreach($recentEvents as $event)
                            <tr>
                                <td class="px-5 py-3.5 font-semibold text-neutral-900">{{ $event->name }}</td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $event->event_date->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <x-badge :color="match($event->status) { 'published' => 'success', 'draft' => 'neutral', 'suspended' => 'danger', default => 'warning' }">
                                        {{ ucfirst($event->status) }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('organizer.events.show', $event) }}" class="font-semibold text-brand hover:text-brand-dark">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @endif
        </x-card>
    </div>
</x-layouts.organizer>
