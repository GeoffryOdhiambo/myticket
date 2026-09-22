@props(['icon' => 'exclamation-triangle', 'title', 'description' => null])

<x-layouts.app :title="$title">
    <div class="mx-auto flex max-w-lg flex-col items-center px-4 py-16 text-center sm:px-6 sm:py-28">
        <span class="flex h-14 w-14 items-center justify-center rounded-full bg-neutral-100 text-neutral-400 sm:h-16 sm:w-16">
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-7 w-7 sm:h-8 sm:w-8" />
        </span>
        <h1 class="mt-5 text-xl font-extrabold text-neutral-900 sm:mt-6 sm:text-2xl">{{ $title }}</h1>
        @if($description)
            <p class="mt-2 text-sm text-neutral-500">{{ $description }}</p>
        @endif

        @isset($action)
            <div class="mt-6 sm:mt-8">{{ $action }}</div>
        @endisset
    </div>
</x-layouts.app>
