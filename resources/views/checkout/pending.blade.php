@php($settings = \App\Models\Setting::current())

<x-layouts.app title="Order Status">
    <div
        class="mx-auto max-w-xl px-4 py-16 sm:px-6 lg:px-8"
        x-data="{ status: '{{ $order->status }}' }"
        @if($order->status === 'pending' && !$isManual)
            x-init="setInterval(() => {
                fetch('{{ route('checkout.status', $order) }}').then(r => r.json()).then(d => {
                    if (d.status !== status) { status = d.status; if (status !== 'pending') window.location.reload(); }
                });
            }, 4000)"
        @endif
    >
        <template x-if="status === 'pending'">
            <x-card class="text-center">
                @if($isManual)
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-light text-brand-dark">
                        <x-heroicon-o-clock class="h-8 w-8" />
                    </span>
                    <h1 class="mt-5 text-xl font-extrabold text-neutral-900">Awaiting Payment</h1>
                    <p class="mt-2 text-sm text-neutral-500">
                        Order <span class="font-semibold text-neutral-700">{{ $order->order_number }}</span> for {{ $settings->formatPrice($order->total) }} is ready.
                        This environment uses the manual payment driver — confirm payment below to simulate a successful transaction.
                    </p>
                    <form method="POST" action="{{ route('checkout.simulate', $order) }}" class="mt-6">
                        @csrf
                        <x-button type="submit" class="w-full" size="lg">Simulate Payment</x-button>
                    </form>
                @else
                    <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-light text-brand-dark animate-pulse">
                        <x-heroicon-o-device-phone-mobile class="h-8 w-8" />
                    </span>
                    <h1 class="mt-5 text-xl font-extrabold text-neutral-900">Check Your Phone</h1>
                    <p class="mt-2 text-sm text-neutral-500">
                        We've sent an M-Pesa payment prompt to your phone for {{ $settings->formatPrice($order->total) }}.
                        Enter your M-Pesa PIN to complete the purchase. This page will update automatically.
                    </p>
                @endif
            </x-card>
        </template>

        <template x-if="status === 'paid'">
            <x-card class="text-center">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <x-heroicon-o-check-circle class="h-8 w-8" />
                </span>
                <h1 class="mt-5 text-xl font-extrabold text-neutral-900">Payment Confirmed</h1>
                <p class="mt-2 text-sm text-neutral-500">
                    Your ticket{{ $order->items->sum('quantity') > 1 ? 's have' : ' has' }} been generated and sent to your WhatsApp and email.
                </p>

                <div class="mt-6 space-y-2 text-left">
                    @foreach($order->items as $item)
                        @foreach($item->tickets as $ticket)
                            <a href="{{ route('ticket.show', $ticket) }}" class="flex items-center justify-between rounded-xl border border-neutral-100 px-4 py-3 hover:border-brand">
                                <span class="text-sm font-semibold text-neutral-800">{{ $item->ticketType->name }} · {{ $ticket->ticket_number }}</span>
                                <x-heroicon-o-arrow-right class="h-4 w-4 text-brand" />
                            </a>
                        @endforeach
                    @endforeach
                </div>
            </x-card>
        </template>

        <template x-if="status === 'failed' || status === 'cancelled'">
            <x-card class="text-center">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 text-red-600">
                    <x-heroicon-o-x-circle class="h-8 w-8" />
                </span>
                <h1 class="mt-5 text-xl font-extrabold text-neutral-900">Payment Failed</h1>
                <p class="mt-2 text-sm text-neutral-500">Your payment could not be completed. No charges were made.</p>
                <div class="mt-6">
                    <x-button href="{{ route('events.show', $order->event) }}">Try Again</x-button>
                </div>
            </x-card>
        </template>
    </div>
</x-layouts.app>
