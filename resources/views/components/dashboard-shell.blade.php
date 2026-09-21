@props(['title' => null, 'portal', 'navItems', 'userName', 'logoutRoute', 'heading' => null])

<x-layouts.base :title="$title">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-neutral-50 md:flex">
        <!-- Mobile top bar -->
        <div class="flex items-center justify-between border-b border-neutral-800 bg-neutral-900 px-4 py-3 md:hidden">
            <div class="flex items-center gap-2">
                <x-logo dark size="text-lg" />
                <span class="text-xs font-medium text-neutral-400">{{ $portal }}</span>
            </div>
            <button @click="sidebarOpen = !sidebarOpen" class="flex h-9 w-9 items-center justify-center rounded-lg text-white hover:bg-neutral-800" aria-label="Toggle menu">
                <x-heroicon-o-bars-3 class="h-6 w-6" />
            </button>
        </div>

        <!-- Sidebar -->
        <aside
            x-show="sidebarOpen || window.innerWidth >= 768"
            x-transition
            class="w-full shrink-0 bg-neutral-900 md:block md:w-64 md:min-h-screen"
            :class="sidebarOpen ? 'block' : 'hidden md:block'"
        >
            <div class="hidden items-center gap-2 px-6 py-6 md:flex">
                <x-logo dark />
            </div>
            <p class="hidden px-6 text-xs font-medium uppercase tracking-wide text-neutral-500 md:block">{{ $portal }}</p>

            <nav class="mt-3 flex flex-col gap-1 px-3 pb-6">
                @foreach($navItems as $item)
                    <a
                        href="{{ $item['url'] }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition-colors {{ $item['active'] ? 'bg-brand text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}"
                    >
                        <x-dynamic-component :component="'heroicon-o-' . $item['icon']" class="h-5 w-5 shrink-0" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="mt-auto border-t border-neutral-800 px-3 py-4">
                <div class="px-3 py-2">
                    <p class="text-sm font-semibold text-white line-clamp-1">{{ $userName }}</p>
                </div>
                <form method="POST" action="{{ $logoutRoute }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-neutral-300 hover:bg-neutral-800 hover:text-white">
                        <x-heroicon-o-arrow-left-start-on-rectangle class="h-5 w-5 shrink-0" />
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main content -->
        <div class="min-w-0 flex-1">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-10">
                @if($heading)
                    <h1 class="text-2xl font-extrabold text-neutral-900">{{ $heading }}</h1>
                @endif

                @if(session('success'))
                    <x-alert type="success" class="mt-4">{{ session('success') }}</x-alert>
                @endif
                @if(session('error'))
                    <x-alert type="error" class="mt-4">{{ session('error') }}</x-alert>
                @endif

                <div class="{{ $heading ? 'mt-6' : '' }}">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.base>
