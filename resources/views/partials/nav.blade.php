<header class="sticky top-0 z-40 border-b border-neutral-100 bg-white/90 backdrop-blur" style="padding-top: env(safe-area-inset-top)">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}">
            <x-logo />
        </a>

        <nav class="hidden items-center gap-8 lg:flex">
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-neutral-700 hover:text-neutral-950">Events</a>
            <a href="{{ route('home') }}#for-organizers" class="text-sm font-semibold text-neutral-700 hover:text-neutral-950">For Organizers</a>
            <a href="{{ route('pages.contact') }}" class="text-sm font-semibold text-neutral-700 hover:text-neutral-950">Contact</a>
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ route('organizer.login') }}" class="text-sm font-semibold text-neutral-700 hover:text-neutral-950">Organizer Login</a>
            <x-button href="{{ route('organizer.login') }}" size="sm">Create an Event</x-button>
        </div>

        <a
            href="{{ route('events.index') }}"
            class="flex h-10 w-10 items-center justify-center rounded-full text-neutral-700 hover:bg-neutral-100 lg:hidden"
            aria-label="Search events"
        >
            <x-heroicon-o-magnifying-glass class="h-5 w-5" />
        </a>
    </div>
</header>
