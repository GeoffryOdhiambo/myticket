<x-layouts.organizer title="Events" heading="Events">
    <div class="flex justify-end">
        <x-button href="{{ route('organizer.events.create') }}">
            <x-heroicon-o-plus class="h-4 w-4" /> New Event
        </x-button>
    </div>

    <x-card padded="{{ false }}" class="mt-4 overflow-hidden">
        @if($events->isEmpty())
            <x-empty-state icon="calendar-days" title="You have not created any events yet." description="Create your first event to start selling tickets on Tiko.">
                <x-slot:action>
                    <x-button href="{{ route('organizer.events.create') }}">Create Event</x-button>
                </x-slot:action>
            </x-empty-state>
        @else
            {{-- Mobile: stacked cards --}}
            <div class="divide-y divide-neutral-100 sm:hidden">
                @foreach($events as $event)
                    <a href="{{ route('organizer.events.show', $event) }}" class="flex items-center justify-between gap-3 p-4 active:bg-neutral-50">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-neutral-900">{{ $event->name }}</p>
                            <p class="mt-0.5 text-xs text-neutral-500">{{ $event->event_date->format('d M Y') }} · {{ $event->ticket_types_count }} {{ \Illuminate\Support\Str::plural('ticket type', $event->ticket_types_count) }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <x-badge :color="match($event->status) { 'published' => 'success', 'draft' => 'neutral', 'suspended' => 'danger', default => 'warning' }">
                                {{ ucfirst($event->status) }}
                            </x-badge>
                            <x-heroicon-o-chevron-right class="h-4 w-4 text-neutral-300" />
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Desktop: table --}}
            <div class="hidden overflow-x-auto sm:block">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                    <tr>
                        <th class="px-5 py-3">Event</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Ticket Types</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($events as $event)
                        <tr>
                            <td class="px-5 py-3.5 font-semibold text-neutral-900">{{ $event->name }}</td>
                            <td class="px-5 py-3.5 text-neutral-500">{{ $event->event_date->format('d M Y') }}</td>
                            <td class="px-5 py-3.5 text-neutral-500">{{ $event->ticket_types_count }}</td>
                            <td class="px-5 py-3.5">
                                <x-badge :color="match($event->status) { 'published' => 'success', 'draft' => 'neutral', 'suspended' => 'danger', default => 'warning' }">
                                    {{ ucfirst($event->status) }}
                                </x-badge>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('organizer.events.show', $event) }}" class="font-semibold text-brand hover:text-brand-dark">Manage</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </x-card>

    @if($events->hasPages())
        <div class="mt-6">{{ $events->links() }}</div>
    @endif
</x-layouts.organizer>
