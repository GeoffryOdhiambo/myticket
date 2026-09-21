<x-layouts.organizer title="Create Event" heading="Create Event">
    <x-card class="max-w-3xl">
        <form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('organizer.events._form', ['event' => null])

            <div class="flex justify-end gap-3 border-t border-neutral-100 pt-6">
                <x-button href="{{ route('organizer.events.index') }}" variant="outline">Cancel</x-button>
                <x-button type="submit">Create Event</x-button>
            </div>
        </form>
    </x-card>
</x-layouts.organizer>
