@props(['icon' => 'inbox', 'title', 'description' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center py-16 px-6']) }}>
    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-neutral-100 text-neutral-400">
        <x-dynamic-component :component="'heroicon-o-' . $icon" class="h-7 w-7" />
    </div>
    <p class="mt-4 text-base font-semibold text-neutral-900">{{ $title }}</p>
    @if($description)
        <p class="mt-1 text-sm text-neutral-500 max-w-sm">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
