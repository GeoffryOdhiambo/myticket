@props(['title' => null, 'portal', 'navItems', 'userName', 'logoutRoute', 'heading' => null])

<x-layouts.base :title="$title">
    <div class="min-h-screen bg-neutral-50 lg:flex">
        <!-- Mobile / tablet top bar -->
        <div
            class="flex items-center justify-between border-b border-neutral-800 bg-neutral-900 px-4 py-3 lg:hidden"
            style="padding-top: max(0.75rem, env(safe-area-inset-top))"
        >
            <div class="flex items-center gap-2">
                <x-logo dark size="text-lg" />
                <span class="text-xs font-medium text-neutral-400">{{ $portal }}</span>
            </div>
            <form method="POST" action="{{ $logoutRoute }}">
                @csrf
                <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-lg text-neutral-300 hover:bg-neutral-800 hover:text-white" aria-label="Logout">
                    <x-heroicon-o-arrow-left-start-on-rectangle class="h-5 w-5" />
                </button>
            </form>
        </div>

        <!-- Sidebar (desktop only) -->
        <aside class="hidden shrink-0 bg-neutral-900 lg:block lg:w-64 lg:min-h-screen">
            <div class="flex items-center gap-2 px-6 py-6">
                <x-logo dark />
            </div>
            <p class="px-6 text-xs font-medium uppercase tracking-wide text-neutral-500">{{ $portal }}</p>

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
        <div class="min-w-0 flex-1 pb-20 lg:pb-0">
            <div class="mx-auto max-w-6xl px-3.5 py-4 sm:px-6 sm:py-8 lg:px-10">
                @if($heading)
                    <h1 class="text-lg font-extrabold text-neutral-900 sm:text-2xl">{{ $heading }}</h1>
                @endif

                @if(session('success'))
                    <x-alert type="success" class="mt-3 sm:mt-4">{{ session('success') }}</x-alert>
                @endif
                @if(session('error'))
                    <x-alert type="error" class="mt-3 sm:mt-4">{{ session('error') }}</x-alert>
                @endif

                <div class="{{ $heading ? 'mt-4 sm:mt-6' : '' }}">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom tab bar (mobile / tablet only) -->
    <nav
        class="fixed inset-x-0 bottom-0 z-40 border-t border-neutral-100 bg-white/95 backdrop-blur lg:hidden"
        style="padding-bottom: env(safe-area-inset-bottom)"
    >
        <div class="grid" style="grid-template-columns: repeat({{ count($navItems) }}, minmax(0, 1fr))">
            @foreach($navItems as $item)
                <a
                    href="{{ $item['url'] }}"
                    class="flex flex-col items-center gap-1 py-2.5 text-[11px] font-semibold {{ $item['active'] ? 'text-brand' : 'text-neutral-400' }}"
                >
                    <x-dynamic-component :component="'heroicon-' . ($item['active'] ? 's' : 'o') . '-' . $item['icon']" class="h-6 w-6" />
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
</x-layouts.base>
