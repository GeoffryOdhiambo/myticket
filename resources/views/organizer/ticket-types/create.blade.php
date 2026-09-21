<x-layouts.organizer title="Add Ticket Type" heading="Add Ticket Type">
    <p class="-mt-4 text-sm text-neutral-500">{{ $event->name }}</p>

    <x-card class="mt-6 max-w-2xl">
        <form method="POST" action="{{ route('organizer.events.ticket-types.store', $event) }}" class="space-y-6">
            @csrf
            @include('organizer.ticket-types._form', ['ticketType' => null])

            <div class="flex justify-end gap-3 border-t border-neutral-100 pt-6">
                <x-button href="{{ route('organizer.events.show', $event) }}" variant="outline">Cancel</x-button>
                <x-button type="submit">Add Ticket Type</x-button>
            </div>
        </form>
    </x-card>
</x-layouts.organizer>
