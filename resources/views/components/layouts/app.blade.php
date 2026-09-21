@props(['title' => null, 'description' => null])

<x-layouts.base :title="$title" :description="$description">
    @include('partials.nav')

    <main>
        {{ $slot }}
    </main>

    @include('partials.footer')
</x-layouts.base>
