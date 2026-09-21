<x-layouts.app title="About">
    <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold uppercase tracking-wide text-brand">About Tiko</p>
        <h1 class="mt-2 text-3xl font-extrabold text-neutral-900 sm:text-4xl">Making event tickets simple, for everyone.</h1>

        <div class="mt-8 space-y-5 text-base leading-relaxed text-neutral-600">
            <p>
                Tiko is an online ticketing platform built for event organizers and the people who love going to
                events. Whether it's a concert, a party, a conference, or a festival, Tiko makes it easy to publish
                an event, sell tickets, and welcome guests at the door.
            </p>
            <p>
                We believe buying a ticket should take minutes, not an account, a password, and a dozen steps.
                That's why customers can browse events, pick a ticket, pay, and receive their digital ticket by
                WhatsApp and email — no sign up required.
            </p>
            <p>
                For organizers, Tiko keeps things simple too: publish your event, set your ticket types and prices,
                and track sales and check-ins from one clean dashboard.
            </p>
        </div>

        <div class="mt-10">
            <x-button href="{{ route('events.index') }}">Explore Events</x-button>
        </div>
    </div>
</x-layouts.app>
