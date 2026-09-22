@php
    $tabs = [
        ['label' => 'Home', 'icon' => 'home', 'url' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'Events', 'icon' => 'ticket', 'url' => route('events.index'), 'active' => request()->routeIs('events.*')],
        ['label' => 'Organizers', 'icon' => 'building-storefront', 'url' => route('organizer.login'), 'active' => request()->routeIs('organizer.*')],
    ];
@endphp

<nav
    x-data="{ moreOpen: false }"
    class="fixed inset-x-0 bottom-0 z-40 border-t border-neutral-100 bg-white/95 backdrop-blur lg:hidden"
    style="padding-bottom: env(safe-area-inset-bottom)"
>
    <div class="grid grid-cols-4">
        @foreach($tabs as $tab)
            <a
                href="{{ $tab['url'] }}"
                class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-semibold {{ $tab['active'] ? 'text-brand' : 'text-neutral-400' }}"
            >
                <x-dynamic-component :component="'heroicon-' . ($tab['active'] ? 's' : 'o') . '-' . $tab['icon']" class="h-6 w-6" />
                {{ $tab['label'] }}
            </a>
        @endforeach

        <button
            type="button"
            @click="moreOpen = true"
            class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-semibold text-neutral-400"
        >
            <x-heroicon-o-ellipsis-horizontal class="h-6 w-6" />
            More
        </button>
    </div>

    <div
        x-show="moreOpen"
        x-cloak
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="moreOpen = false"
        class="fixed inset-0 z-40 bg-neutral-950/40"
    ></div>

    <div
        x-show="moreOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="translate-y-full"
        class="fixed inset-x-0 bottom-0 z-50 rounded-t-3xl bg-white p-5 shadow-2xl"
        style="padding-bottom: calc(env(safe-area-inset-bottom) + 1.25rem)"
    >
        <div class="mx-auto mb-4 h-1.5 w-10 rounded-full bg-neutral-200"></div>

        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('pages.about') }}" @click="moreOpen = false" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                <x-heroicon-o-information-circle class="h-5 w-5 text-neutral-400" /> About
            </a>
            <a href="{{ route('pages.contact') }}" @click="moreOpen = false" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                <x-heroicon-o-envelope class="h-5 w-5 text-neutral-400" /> Contact
            </a>
            <a href="{{ route('pages.terms') }}" @click="moreOpen = false" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                <x-heroicon-o-document-text class="h-5 w-5 text-neutral-400" /> Terms
            </a>
            <a href="{{ route('pages.privacy') }}" @click="moreOpen = false" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                <x-heroicon-o-shield-check class="h-5 w-5 text-neutral-400" /> Privacy
            </a>
        </div>

        <div class="mt-2 border-t border-neutral-100 pt-4">
            <x-button href="{{ route('organizer.login') }}" class="w-full">Create an Event</x-button>
        </div>
    </div>
</nav>
