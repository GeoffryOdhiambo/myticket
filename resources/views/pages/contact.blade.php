@php
    $settings = \App\Models\Setting::current();
@endphp

<x-layouts.app title="Contact">
    <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 lg:px-8">
        <p class="text-sm font-semibold uppercase tracking-wide text-brand">Get in Touch</p>
        <h1 class="mt-2 text-3xl font-extrabold text-neutral-900 sm:text-4xl">We're here to help.</h1>
        <p class="mt-4 text-base text-neutral-600">
            Have a question about an order, a ticket, or hosting your event on Tiko? Reach out and our team will get back to you.
        </p>

        <div class="mt-10 grid gap-4 sm:grid-cols-2">
            <x-card>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-light text-brand-dark">
                    <x-heroicon-o-envelope class="h-5 w-5" />
                </div>
                <p class="mt-4 text-sm font-semibold text-neutral-500">Email</p>
                <p class="mt-1 text-base font-bold text-neutral-900">{{ $settings->support_email }}</p>
            </x-card>

            <x-card>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-light text-brand-dark">
                    <x-heroicon-o-phone class="h-5 w-5" />
                </div>
                <p class="mt-4 text-sm font-semibold text-neutral-500">Phone</p>
                <p class="mt-1 text-base font-bold text-neutral-900">{{ $settings->support_phone }}</p>
            </x-card>
        </div>
    </div>
</x-layouts.app>
