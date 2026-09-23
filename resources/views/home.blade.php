@php
    $headline = 'Your Event. Your Ticket. Your Experience.';
    $words = explode(' ', $headline);
@endphp

<x-layouts.app>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-neutral-950">
        <img
            src="https://images.unsplash.com/photo-1478147427282-58a87a120781?auto=format&fit=crop&w=1600&q=70"
            alt="Crowd celebrating at a live event"
            class="absolute inset-0 h-full w-full object-cover opacity-60"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-950 via-neutral-950/70 to-neutral-950/30"></div>

        <div class="relative mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 sm:py-28 lg:py-36 lg:px-8">
            <h1 class="text-3xl font-extrabold leading-tight text-white sm:text-5xl lg:text-6xl">
                @foreach($words as $i => $word)
                    <span class="word-in" style="animation-delay: {{ $i * 0.09 }}s">{{ $word }}</span>{{ $loop->last ? '' : ' ' }}
                @endforeach
            </h1>

            <p class="mx-auto mt-4 max-w-xl text-sm text-neutral-200 sm:mt-6 sm:text-lg">
                MyTicket makes it easy to discover events and buy tickets online — concerts, parties, conferences, festivals, and unforgettable experiences.
            </p>

            <form action="{{ route('events.index') }}" method="GET" class="mx-auto mt-6 flex max-w-lg flex-col gap-3 sm:mt-8 sm:flex-row">
                <div class="relative flex-1">
                    <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-neutral-400" />
                    <input
                        type="text"
                        name="q"
                        placeholder="Search events"
                        class="w-full rounded-full border-0 bg-white py-3.5 pl-12 pr-4 text-sm text-neutral-900 placeholder-neutral-400 shadow-lg focus:outline-none focus:ring-2 focus:ring-brand"
                    >
                </div>
                <x-button type="submit" size="lg">Explore Events</x-button>
            </form>
        </div>
    </section>

    {{-- Featured Events --}}
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-20 lg:px-8">
        <div class="flex items-end justify-between animate-in">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-brand sm:text-sm">Don't Miss Out</p>
                <h2 class="mt-1 text-xl font-extrabold text-neutral-900 sm:text-3xl">Featured Events</h2>
            </div>
            <a href="{{ route('events.index') }}" class="hidden text-sm font-semibold text-brand hover:text-brand-dark sm:block">View all events</a>
        </div>

        @if($featuredEvents->isEmpty())
            <div class="mt-6 sm:mt-8">
                <x-empty-state icon="calendar-days" title="No events available." description="Check back soon — new events are added regularly." />
            </div>
        @else
            <div class="mt-5 grid grid-cols-1 gap-4 sm:mt-8 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                @foreach($featuredEvents as $event)
                    <div class="animate-in" style="transition-delay: {{ $loop->index * 0.06 }}s">
                        <x-event-card :event="$event" />
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-8 text-center sm:hidden">
            <x-button href="{{ route('events.index') }}" variant="outline">View all events</x-button>
        </div>
    </section>

    {{-- Categories --}}
    <section class="bg-neutral-50 py-12 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-in">
                <p class="text-xs font-semibold uppercase tracking-wide text-brand sm:text-sm">Browse by Category</p>
                <h2 class="mt-1 text-xl font-extrabold text-neutral-900 sm:text-3xl">Find Your Next Experience</h2>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3 sm:mt-10 sm:grid-cols-3 sm:gap-4 lg:grid-cols-6">
                @foreach($categories as $category)
                    <a
                        href="{{ route('events.index', ['category' => $category->slug]) }}"
                        class="animate-in flex flex-col items-center gap-2 rounded-xl border border-neutral-100 bg-white p-4 text-center shadow-sm transition-shadow hover:shadow-md sm:gap-3 sm:rounded-2xl sm:p-6"
                        style="transition-delay: {{ $loop->index * 0.05 }}s"
                    >
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-light text-brand-dark sm:h-12 sm:w-12">
                            <x-dynamic-component :component="'heroicon-o-' . $category->icon" class="h-5 w-5 sm:h-6 sm:w-6" />
                        </span>
                        <span class="text-xs font-semibold text-neutral-800 sm:text-sm">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How MyTicket Works --}}
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-20 lg:px-8">
        <div class="text-center animate-in">
            <p class="text-xs font-semibold uppercase tracking-wide text-brand sm:text-sm">Simple &amp; Fast</p>
            <h2 class="mt-1 text-xl font-extrabold text-neutral-900 sm:text-3xl">How MyTicket Works</h2>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 sm:mt-10 sm:grid-cols-2 lg:grid-cols-4">
            @foreach([
                ['icon' => 'magnifying-glass', 'title' => 'Find an Event', 'text' => 'Browse concerts, parties, conferences and more happening near you.'],
                ['icon' => 'ticket', 'title' => 'Choose Your Ticket', 'text' => 'Pick the ticket type and quantity that fits your plans.'],
                ['icon' => 'credit-card', 'title' => 'Pay', 'text' => 'Pay securely online in just a few taps.'],
                ['icon' => 'qr-code', 'title' => 'Get Your Ticket', 'text' => 'Receive your digital ticket instantly by WhatsApp and email.'],
            ] as $step)
                <div class="animate-in text-center" style="transition-delay: {{ $loop->index * 0.08 }}s">
                    <span class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-neutral-900 text-white sm:h-14 sm:w-14 sm:rounded-2xl">
                        <x-dynamic-component :component="'heroicon-o-' . $step['icon']" class="h-5 w-5 sm:h-6 sm:w-6" />
                    </span>
                    <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-brand sm:mt-4">Step {{ $loop->iteration }}</p>
                    <h3 class="mt-1 text-sm font-bold text-neutral-900 sm:text-base">{{ $step['title'] }}</h3>
                    <p class="mt-1.5 text-xs text-neutral-500 sm:mt-2 sm:text-sm">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-3 gap-4 border-t border-neutral-100 pt-8 text-center sm:mt-14 sm:pt-10">
            <div class="animate-in">
                <p class="text-2xl font-extrabold text-neutral-900 sm:text-4xl"><span data-counter-target="{{ $stats['events'] }}">0</span>+</p>
                <p class="mt-1 text-xs text-neutral-500 sm:text-sm">Events Hosted</p>
            </div>
            <div class="animate-in" style="transition-delay: 0.08s">
                <p class="text-2xl font-extrabold text-neutral-900 sm:text-4xl"><span data-counter-target="{{ $stats['organizers'] }}">0</span>+</p>
                <p class="mt-1 text-xs text-neutral-500 sm:text-sm">Organizers</p>
            </div>
            <div class="animate-in" style="transition-delay: 0.16s">
                <p class="text-2xl font-extrabold text-neutral-900 sm:text-4xl"><span data-counter-target="{{ $stats['tickets'] }}">0</span>+</p>
                <p class="mt-1 text-xs text-neutral-500 sm:text-sm">Tickets Sold</p>
            </div>
        </div>
    </section>

    {{-- For Organizers --}}
    <section id="for-organizers" class="bg-neutral-900">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-20 lg:px-8">
            <div class="grid items-center gap-6 lg:grid-cols-2 lg:gap-10">
                <div class="animate-in">
                    <p class="text-xs font-semibold uppercase tracking-wide text-brand sm:text-sm">Hosting an Event?</p>
                    <h2 class="mt-2 text-xl font-extrabold text-white sm:text-3xl">Sell your tickets online with MyTicket and make entry management simple.</h2>
                    <p class="mt-3 text-sm text-neutral-300 sm:mt-4 sm:text-base">
                        Publish your event, set your ticket types, and start selling in minutes. Track sales and
                        check in guests with a scanner built for your phone.
                    </p>
                    <div class="mt-6 sm:mt-8">
                        <x-button href="{{ route('organizer.login') }}" size="lg">Create an Event</x-button>
                    </div>
                </div>
                <div class="animate-in overflow-hidden rounded-3xl" style="transition-delay: 0.1s">
                    <img
                        src="https://images.unsplash.com/photo-1508997449629-303059a039c0?auto=format&fit=crop&w=1000&q=70"
                        alt="Conference and exhibition event"
                        class="h-full w-full object-cover"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
