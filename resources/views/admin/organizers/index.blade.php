<x-layouts.admin title="Organizers" heading="Organizers">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.organizers.index') }}" class="flex-1 max-w-sm">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search organizers..." class="w-full rounded-xl border border-neutral-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30">
        </form>
        <x-button href="{{ route('admin.organizers.create') }}">
            <x-heroicon-o-plus class="h-4 w-4" /> Create Organizer
        </x-button>
    </div>

    <x-card padded="{{ false }}" class="mt-6 overflow-hidden">
        @if($organizers->isEmpty())
            <x-empty-state icon="building-storefront" title="No organizers found." />
        @else
            <table class="w-full text-left text-sm">
                <thead class="border-b border-neutral-100 bg-neutral-50 text-xs font-semibold uppercase tracking-wide text-neutral-500">
                    <tr>
                        <th class="px-5 py-3">Business</th>
                        <th class="px-5 py-3">Contact</th>
                        <th class="px-5 py-3">Events</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($organizers as $organizer)
                        <tr>
                            <td class="px-5 py-3.5 font-semibold text-neutral-900">{{ $organizer->business_name }}</td>
                            <td class="px-5 py-3.5 text-neutral-500">{{ $organizer->name }} · {{ $organizer->email }}</td>
                            <td class="px-5 py-3.5 text-neutral-600">{{ $organizer->events_count }}</td>
                            <td class="px-5 py-3.5">
                                <x-badge :color="$organizer->status === 'active' ? 'success' : 'danger'">{{ ucfirst($organizer->status) }}</x-badge>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.organizers.show', $organizer) }}" class="font-semibold text-brand hover:text-brand-dark">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-card>

    @if($organizers->hasPages())
        <div class="mt-6">{{ $organizers->links() }}</div>
    @endif
</x-layouts.admin>
