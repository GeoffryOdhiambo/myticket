@props(['event'])

<a href="{{ route('events.show', $event->slug) }}" class="group block overflow-hidden rounded-2xl border border-neutral-100 bg-white shadow-sm transition-shadow hover:shadow-md">
    <div class="relative aspect-[4/3] w-full overflow-hidden bg-neutral-100">
        <img
            src="{{ $event->image_url }}"
            alt="{{ $event->name }}"
            loading="lazy"
            class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
        >
        @if($event->category)
            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-3 py-1 text-xs font-semibold text-neutral-800 backdrop-blur">
                {{ $event->category->name }}
            </span>
        @endif
    </div>

    <div class="p-5">
        <p class="text-xs font-semibold uppercase tracking-wide text-brand">
            {{ $event->event_date->format('D, d M Y') }}
        </p>
        <h3 class="mt-1.5 text-base font-bold text-neutral-900 line-clamp-1">{{ $event->name }}</h3>

        <p class="mt-1.5 flex items-center gap-1.5 text-sm text-neutral-500">
            <x-heroicon-o-map-pin class="h-4 w-4 shrink-0" />
            <span class="line-clamp-1">{{ $event->location }}</span>
        </p>

        <div class="mt-4 flex items-center justify-between border-t border-neutral-100 pt-4">
            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-neutral-400">From</p>
                <p class="text-sm font-extrabold text-neutral-900">{{ $event->starting_price_label }}</p>
            </div>
            <span class="inline-flex items-center gap-1 text-sm font-semibold text-brand">
                View Event
                <x-heroicon-o-arrow-right class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
            </span>
        </div>
    </div>
</a>
