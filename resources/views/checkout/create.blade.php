@php
    $settings = \App\Models\Setting::current();
@endphp

<x-layouts.app title="Checkout">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-neutral-500 hover:text-neutral-800">
            <x-heroicon-o-arrow-left class="h-4 w-4" /> Back to event
        </a>

        <h1 class="mt-4 text-2xl font-extrabold text-neutral-900">Checkout</h1>
        <p class="mt-1 text-sm text-neutral-500">Enter your details to receive your ticket by WhatsApp and email.</p>

        <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-5">
            <div class="lg:col-span-3">
                <x-card>
                    <h2 class="text-base font-bold text-neutral-900">Your Details</h2>

                    <form method="POST" action="{{ route('checkout.store', $event) }}" class="mt-5 space-y-4">
                        @csrf
                        @foreach($lines as $line)
                            <input type="hidden" name="items[{{ $line['type']->id }}]" value="{{ $line['quantity'] }}">
                        @endforeach

                        <x-input label="Full name" name="customer_name" required autofocus />
                        <x-input label="WhatsApp phone number" name="customer_whatsapp" type="tel" placeholder="e.g. 0712 345 678" required hint="Your ticket will be sent to this number." />
                        <x-input label="Email address" name="customer_email" type="email" required />

                        <x-button type="submit" class="w-full" size="lg">
                            Continue to Payment
                        </x-button>
                    </form>
                </x-card>
            </div>

            <div class="lg:col-span-2">
                <x-card>
                    <h2 class="text-base font-bold text-neutral-900">Order Summary</h2>
                    <p class="mt-1 text-sm text-neutral-500">{{ $event->name }}</p>
                    <p class="text-xs text-neutral-400">{{ $event->event_date->format('D, d M Y') }} · {{ $event->venue }}</p>

                    <div class="mt-5 space-y-3 border-t border-neutral-100 pt-4">
                        @foreach($lines as $line)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-neutral-600">{{ $line['quantity'] }} × {{ $line['type']->name }}</span>
                                <span class="font-semibold text-neutral-900">{{ $settings->formatPrice($line['subtotal']) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-neutral-100 pt-4">
                        <span class="text-sm font-semibold text-neutral-900">Total</span>
                        <span class="text-lg font-extrabold text-brand">{{ $settings->formatPrice($subtotal) }}</span>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-layouts.app>
