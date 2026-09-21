<x-layouts.app title="Events">
    <div class="border-b border-neutral-100 bg-neutral-50">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-extrabold text-neutral-900 sm:text-3xl">Explore Events</h1>
            <p class="mt-1 text-sm text-neutral-500">Find concerts, parties, conferences, and festivals near you.</p>

            <form method="GET" action="{{ route('events.index') }}" class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-4">
                <div class="relative sm:col-span-2">
                    <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-neutral-400" />
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Search events"
                        class="w-full rounded-xl border border-neutral-200 bg-white py-2.5 pl-11 pr-4 text-sm text-neutral-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-brand/30"
                    >
                </div>

                <select name="category" class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm text-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand/30">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->slug }}" @selected(($filters['category'] ?? null) === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>

                <input
                    type="date"
                    name="date"
                    value="{{ $filters['date'] ?? '' }}"
                    class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm text-neutral-900 focus:outline-none focus:ring-2 focus:ring-brand/30"
                >

                <input
                    type="text"
                    name="location"
                    value="{{ $filters['location'] ?? '' }}"
                    placeholder="Location"
                    class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-brand/30 sm:col-span-2"
                >

                <div class="flex gap-2 sm:col-span-2">
                    <x-button type="submit" class="flex-1">Search</x-button>
                    @if(array_filter($filters))
                        <x-button href="{{ route('events.index') }}" variant="outline">Clear</x-button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @if($events->isEmpty())
            <x-empty-state icon="calendar-days" title="No events found." description="Try adjusting your search or filters." />
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($events as $event)
                    <x-event-card :event="$event" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
