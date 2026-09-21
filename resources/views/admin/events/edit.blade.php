<x-layouts.admin title="Edit Event" heading="Edit Event">
    <x-card class="max-w-3xl">
        <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            @include('organizer.events._form', ['event' => $event])

            <div class="flex justify-end gap-3 border-t border-neutral-100 pt-6">
                <x-button href="{{ route('admin.events.show', $event) }}" variant="outline">Cancel</x-button>
                <x-button type="submit" variant="dark">Save Changes</x-button>
            </div>
        </form>
    </x-card>
</x-layouts.admin>
