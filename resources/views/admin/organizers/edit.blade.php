<x-layouts.admin title="Edit Organizer" heading="Edit Organizer">
    <x-card class="max-w-2xl">
        <form method="POST" action="{{ route('admin.organizers.update', $organizer) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <x-input label="Contact name" name="name" :value="$organizer->name" required />
            <x-input label="Business name" name="business_name" :value="$organizer->business_name" required />
            <x-input label="Email address" name="email" type="email" :value="$organizer->email" required />
            <x-input label="Phone number" name="phone" :value="$organizer->phone" required />
            <x-input label="New password" name="password" type="password" hint="Leave blank to keep the current password." />

            <div class="flex justify-end gap-3 border-t border-neutral-100 pt-6">
                <x-button href="{{ route('admin.organizers.show', $organizer) }}" variant="outline">Cancel</x-button>
                <x-button type="submit" variant="dark">Save Changes</x-button>
            </div>
        </form>
    </x-card>
</x-layouts.admin>
