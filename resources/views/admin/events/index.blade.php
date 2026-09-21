@php
    $settings = \App\Models\Setting::current();
@endphp

<x-layouts.admin title="Events" heading="Events">
    <form method="GET" action="{{ route('admin.events.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search events..." class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">

        <select name="organizer" class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
            <option value="">All Organizers</option>
            @foreach($organizers as $organizer)
                <option value="{{ $organizer->id }}" @selected(($filters['organizer'] ?? null) == $organizer->id)>{{ $organizer->business_name }}</option>
            @endforeach
        </select>

        <select name="status" class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
            <option value="">All Status</option>
            @foreach(['draft' => 'Draft', 'published' => 'Published', 'unpublished' => 'Unpublished', 'suspended' => 'Suspended'] as $value => $label)
                <option value="{{ $value }}" @selected(($filters['status'] ?? null) === $value)>{{ $label }}</option>
            @endforeach
        </select>

        <div class="sm:col-span-3">
            <x-button type="submit" size="sm">Filter</x-button>
            @if(array_filter($filters))
                <x-button href="{{ route('admin.events.index') }}" variant="outline" size="sm">Clear</x-button>
            @endif
        </div>
    </form>

    <x-card padded="{{ false }}" class="mt-6 overflow-hidden">
        @if($events->isEmpty())
            <x-empty-state icon="calendar-days" title="No events found." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-neutral-100 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                        <tr>
                            <th class="px-5 py-3">Event</th>
                            <th class="px-5 py-3">Organizer</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Sold</th>
                            <th class="px-5 py-3">Revenue</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($events as $event)
                            <tr>
                                <td class="px-5 py-3.5 font-semibold text-neutral-900">{{ $event->name }}</td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $event->organizer->business_name }}</td>
                                <td class="px-5 py-3.5 text-neutral-500">{{ $event->event_date->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <x-badge :color="match($event->status) { 'published' => 'success', 'draft' => 'neutral', 'suspended' => 'danger', default => 'warning' }">
                                        {{ ucfirst($event->status) }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $event->tickets_sold }}</td>
                                <td class="px-5 py-3.5 text-neutral-600">{{ $settings->formatPrice($event->revenue) }}</td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.events.show', $event) }}" class="font-semibold text-brand hover:text-brand-dark">View</a>
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
</x-layouts.admin>
