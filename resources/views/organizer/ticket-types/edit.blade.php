<x-layouts.organizer title="Edit Ticket Type" heading="Edit Ticket Type">
    <p class="-mt-4 text-sm text-neutral-500">{{ $ticketType->event->name }}</p>

    <x-card class="mt-6 max-w-2xl">
        <form method="POST" action="{{ route('organizer.ticket-types.update', $ticketType) }}" class="space-y-6">
            @csrf
            @method('PUT')
            @include('organizer.ticket-types._form', ['ticketType' => $ticketType])

            <div class="flex justify-end gap-3 border-t border-neutral-100 pt-6">
                <x-button href="{{ route('organizer.events.show', $ticketType->event) }}" variant="outline">Cancel</x-button>
                <x-button type="submit">Save Changes</x-button>
            </div>
        </form>

        <form method="POST" action="{{ route('organizer.ticket-types.destroy', $ticketType) }}" onsubmit="return confirm('Delete this ticket type? This cannot be undone.');" class="mt-4 border-t border-neutral-100 pt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700">Delete Ticket Type</button>
        </form>
    </x-card>
</x-layouts.organizer>
