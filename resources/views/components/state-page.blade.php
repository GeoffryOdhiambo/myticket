@props(['icon' => 'exclamation-triangle', 'title', 'description' => null])

<x-layouts.app :title="$title">
    <div class="mx-auto flex max-w-lg flex-col items-center px-4 py-28 text-center sm:px-6">
        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-8 w-8" />
        </span>
        <h1 class="mt-6 text-2xl font-extrabold text-neutral-900">{{ $title }}</h1>
        @if($description)
            <p class="mt-2 text-sm text-neutral-500">{{ $description }}</p>
        @endif

        @isset($action)
            <div class="mt-8">{{ $action }}</div>
        @endisset
    </div>
</x-layouts.app>
