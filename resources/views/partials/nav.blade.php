<header x-data="{ open: false }" class="sticky top-0 z-40 border-b border-neutral-100 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}">
            <x-logo />
        </a>

        <nav class="hidden items-center gap-8 md:flex">
            <a href="{{ route('events.index') }}" class="text-sm font-semibold text-neutral-700 hover:text-neutral-950">Events</a>
            <a href="{{ route('home') }}#for-organizers" class="text-sm font-semibold text-neutral-700 hover:text-neutral-950">For Organizers</a>
            <a href="{{ route('pages.contact') }}" class="text-sm font-semibold text-neutral-700 hover:text-neutral-950">Contact</a>
        </nav>

        <div class="hidden items-center gap-3 md:flex">
            <a href="{{ route('organizer.login') }}" class="text-sm font-semibold text-neutral-700 hover:text-neutral-950">Organizer Login</a>
            <x-button href="{{ route('organizer.login') }}" size="sm">Create an Event</x-button>
        </div>

        <button @click="open = !open" class="flex h-10 w-10 items-center justify-center rounded-full text-neutral-700 hover:bg-neutral-100 md:hidden" aria-label="Toggle menu">
            <x-heroicon-o-bars-3 class="h-6 w-6" x-show="!open" />
            <x-heroicon-o-x-mark class="h-6 w-6" x-show="open" x-cloak />
        </button>
    </div>

    <div x-show="open" x-cloak x-transition class="border-t border-neutral-100 px-4 pb-4 md:hidden">
        <nav class="flex flex-col gap-1 pt-3">
            <a href="{{ route('events.index') }}" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">Events</a>
            <a href="{{ route('home') }}#for-organizers" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">For Organizers</a>
            <a href="{{ route('pages.contact') }}" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">Contact</a>
            <a href="{{ route('organizer.login') }}" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">Organizer Login</a>
            <div class="pt-2">
                <x-button href="{{ route('organizer.login') }}" class="w-full">Create an Event</x-button>
            </div>
        </nav>
    </div>
</header>
