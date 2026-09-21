<x-layouts.admin title="Create Organizer" heading="Create Organizer">
    <x-card class="max-w-2xl">
        <form method="POST" action="{{ route('admin.organizers.store') }}" class="space-y-5">
            @csrf
            <x-input label="Contact name" name="name" required />
            <x-input label="Business name" name="business_name" required />
            <x-input label="Email address" name="email" type="email" required />
            <x-input label="Phone number" name="phone" required />
            <x-input label="Password" name="password" type="password" required hint="At least 8 characters. Share this with the organizer securely." />

            <div class="flex justify-end gap-3 border-t border-neutral-100 pt-6">
                <x-button href="{{ route('admin.organizers.index') }}" variant="outline">Cancel</x-button>
                <x-button type="submit" variant="dark">Create Organizer</x-button>
            </div>
        </form>
    </x-card>
</x-layouts.admin>
