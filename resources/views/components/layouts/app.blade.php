@props(['title' => null, 'description' => null])

<x-layouts.base :title="$title" :description="$description">
    @include('partials.nav')

    <main class="pb-20 lg:pb-0">
        {{ $slot }}
    </main>

    <div class="hidden lg:block">
        @include('partials.footer')
    </div>

    @include('partials.bottom-nav')
</x-layouts.base>
