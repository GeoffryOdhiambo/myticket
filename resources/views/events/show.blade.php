@php
    $isPast = $event->event_date->isPast();
    $ticketTypes = $event->ticketTypes;
@endphp

<x-layouts.app :title="$event->name" :description="\Illuminate\Support\Str::limit(strip_tags($event->description), 150)">
    <div class="relative h-64 w-full overflow-hidden bg-neutral-900 sm:h-80 lg:h-96">
        <img src="{{ $event->image_url }}" alt="{{ $event->name }}" class="h-full w-full object-cover opacity-90">
        <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/80 via-neutral-950/10 to-transparent"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-10">
            <div class="lg:col-span-2">
                <div class="-mt-12 relative sm:-mt-16">
                    @if($event->category)
                        <x-badge color="brand">{{ $event->category->name }}</x-badge>
                    @endif
                    <h1 class="mt-2 text-xl font-extrabold text-white sm:mt-3 sm:text-3xl" style="text-shadow: 0 2px 12px rgba(0,0,0,0.4)">
                        {{ $event->name }}
                    </h1>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-2.5 sm:mt-8 sm:grid-cols-3 sm:gap-4">
                    <div class="flex items-start gap-3 rounded-xl border border-neutral-100 bg-white p-3 shadow-sm sm:rounded-2xl sm:p-4">
                        <x-heroicon-o-calendar-days class="h-5 w-5 shrink-0 text-brand" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-neutral-400">Date &amp; Time</p>
                            <p class="mt-0.5 text-sm font-semibold text-neutral-900">{{ $event->event_date->format('D, d M Y') }}</p>
                            <p class="text-xs text-neutral-500">{{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }} – {{ \Illuminate\Support\Carbon::parse($event->end_time)->format('g:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-xl border border-neutral-100 bg-white p-3 shadow-sm sm:rounded-2xl sm:p-4">
                        <x-heroicon-o-map-pin class="h-5 w-5 shrink-0 text-brand" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-neutral-400">Venue</p>
                            <p class="mt-0.5 text-sm font-semibold text-neutral-900">{{ $event->venue }}</p>
                            <p class="text-xs text-neutral-500">{{ $event->location }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 rounded-xl border border-neutral-100 bg-white p-3 shadow-sm sm:rounded-2xl sm:p-4">
                        <x-heroicon-o-user-circle class="h-5 w-5 shrink-0 text-brand" />
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-neutral-400">Organized by</p>
                            <p class="mt-0.5 text-sm font-semibold text-neutral-900">{{ $event->organizer->business_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 sm:mt-10">
                    <h2 class="text-base font-bold text-neutral-900 sm:text-lg">About This Event</h2>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-neutral-600 sm:mt-3">{{ $event->description }}</p>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="lg:sticky lg:top-24">
                    <x-card>
                        <h2 class="text-base font-bold text-neutral-900 sm:text-lg">Choose Your Ticket</h2>

                        @if($isPast)
                            <p class="mt-4 text-sm text-neutral-500">This event has already taken place.</p>
                        @elseif($ticketTypes->isEmpty())
                            <p class="mt-4 text-sm text-neutral-500">Tickets are not yet available for this event.</p>
                        @else
                            <form
                                x-data="ticketSelector({{ $ticketTypes->map(fn ($t) => ['id' => $t->id, 'price' => $t->price, 'max' => $t->available_quantity])->toJson() }}, '{{ \App\Models\Setting::current()->currency }}')"
                                action="{{ route('checkout.create', $event) }}"
                                method="GET"
                                class="mt-5 space-y-4"
                            >
                                @foreach($ticketTypes as $type)
                                    <div class="rounded-xl border border-neutral-100 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-bold text-neutral-900">{{ $type->name }}</p>
                                                @if($type->description)
                                                    <p class="mt-0.5 text-xs text-neutral-500">{{ $type->description }}</p>
                                                @endif
                                                <p class="mt-1.5 text-sm font-extrabold text-brand">{{ $type->price_label }}</p>
                                                @if(!is_null($type->available_quantity))
                                                    <p class="mt-0.5 text-[11px] text-neutral-400">
                                                        {{ $type->available_quantity > 0 ? $type->available_quantity . ' left' : 'Sold out' }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <button type="button" @click="decrement({{ $type->id }})" class="flex h-8 w-8 items-center justify-center rounded-full border border-neutral-200 text-neutral-600 hover:bg-neutral-50">
                                                    <x-heroicon-o-minus class="h-4 w-4" />
                                                </button>
                                                <input type="number" name="items[{{ $type->id }}]" x-model.number="quantities[{{ $type->id }}]" readonly class="w-8 border-0 p-0 text-center text-sm font-bold text-neutral-900 focus:ring-0">
                                                <button type="button" @click="increment({{ $type->id }})" class="flex h-8 w-8 items-center justify-center rounded-full border border-neutral-200 text-neutral-600 hover:bg-neutral-50">
                                                    <x-heroicon-o-plus class="h-4 w-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="border-t border-neutral-100 pt-4">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-neutral-500">Total</span>
                                        <span class="text-base font-extrabold text-neutral-900" x-text="formattedTotal()"></span>
                                    </div>

                                    <x-button type="submit" class="mt-4 w-full" x-bind:disabled="totalQuantity() === 0">
                                        Proceed to Checkout
                                    </x-button>
                                </div>
                            </form>
                        @endif
                    </x-card>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
