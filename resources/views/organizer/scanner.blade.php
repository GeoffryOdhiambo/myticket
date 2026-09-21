<x-layouts.organizer title="Scanner" heading="Ticket Scanner">
    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-2xl border border-neutral-100 bg-white p-4 text-center shadow-sm">
            <p class="text-xl font-extrabold text-neutral-900">{{ $sold }}</p>
            <p class="text-xs text-neutral-500">Sold</p>
        </div>
        <div class="rounded-2xl border border-neutral-100 bg-white p-4 text-center shadow-sm">
            <p class="text-xl font-extrabold text-emerald-600">{{ $checkedIn }}</p>
            <p class="text-xs text-neutral-500">Checked In</p>
        </div>
        <div class="rounded-2xl border border-neutral-100 bg-white p-4 text-center shadow-sm">
            <p class="text-xl font-extrabold text-neutral-900">{{ $remaining }}</p>
            <p class="text-xs text-neutral-500">Remaining</p>
        </div>
    </div>

    <div
        class="mt-6"
        x-data="scannerPage(
            '{{ route('organizer.scanner.verify') }}',
            '{{ route('organizer.scanner.checkin', ['ticket' => '__TICKET__']) }}'
        )"
    >
        <div class="relative mx-auto max-w-md overflow-hidden rounded-3xl bg-neutral-950 shadow-md" x-show="!result">
            <video x-ref="video" class="aspect-square w-full object-cover"></video>

            <div x-show="cameraError" x-cloak class="absolute inset-0 flex items-center justify-center bg-neutral-950 p-6 text-center">
                <p class="text-sm text-neutral-300" x-text="cameraError"></p>
            </div>

            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4 text-center">
                <p class="text-xs font-medium text-neutral-200">Point the camera at the ticket's QR code</p>
            </div>
        </div>

        <div x-show="result" x-cloak x-transition class="mx-auto max-w-md">
            <template x-if="result?.status === 'valid'">
                <x-card class="text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <x-heroicon-o-check-circle class="h-7 w-7" />
                    </span>
                    <p class="mt-3 text-lg font-extrabold text-neutral-900">Valid Ticket</p>

                    <div class="mt-4 space-y-1 text-left text-sm">
                        <p class="font-semibold text-neutral-900" x-text="result.ticket.customer_name"></p>
                        <p class="text-neutral-500" x-text="result.ticket.ticket_type + ' · ' + result.ticket.event_name"></p>
                        <p class="text-neutral-400" x-text="result.ticket.ticket_number"></p>
                    </div>

                    <button type="button" @click="checkIn()" class="mt-5 w-full rounded-full bg-brand px-5 py-3 text-sm font-semibold text-white hover:bg-brand-dark">
                        Check In
                    </button>
                    <button type="button" @click="scanNext()" class="mt-2 w-full rounded-full px-5 py-2.5 text-sm font-semibold text-neutral-500 hover:bg-neutral-50">
                        Cancel
                    </button>
                </x-card>
            </template>

            <template x-if="result?.status === 'checked_in'">
                <x-card class="text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <x-heroicon-o-check-badge class="h-7 w-7" />
                    </span>
                    <p class="mt-3 text-lg font-extrabold text-neutral-900">Checked In</p>
                    <p class="mt-1 text-sm text-neutral-500" x-text="result.ticket.customer_name"></p>

                    <button type="button" @click="scanNext()" class="mt-5 w-full rounded-full bg-neutral-900 px-5 py-3 text-sm font-semibold text-white hover:bg-neutral-800">
                        Scan Next Ticket
                    </button>
                </x-card>
            </template>

            <template x-if="result?.status === 'used'">
                <x-card class="text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                        <x-heroicon-o-exclamation-triangle class="h-7 w-7" />
                    </span>
                    <p class="mt-3 text-lg font-extrabold text-neutral-900">Already Used</p>
                    <p class="mt-1 text-sm text-neutral-500" x-text="result.ticket?.customer_name"></p>
                    <p class="mt-1 text-xs text-neutral-400" x-show="result.ticket?.checked_in_at">
                        Checked in <span x-text="result.ticket?.checked_in_at"></span>
                        <template x-if="result.ticket?.checked_in_by"> by <span x-text="result.ticket?.checked_in_by"></span></template>
                    </p>

                    <button type="button" @click="scanNext()" class="mt-5 w-full rounded-full bg-neutral-900 px-5 py-3 text-sm font-semibold text-white hover:bg-neutral-800">
                        Scan Next Ticket
                    </button>
                </x-card>
            </template>

            <template x-if="result?.status === 'invalid'">
                <x-card class="text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 text-red-600">
                        <x-heroicon-o-x-circle class="h-7 w-7" />
                    </span>
                    <p class="mt-3 text-lg font-extrabold text-neutral-900">Invalid Ticket</p>
                    <p class="mt-1 text-sm text-neutral-500">This QR code could not be verified.</p>

                    <button type="button" @click="scanNext()" class="mt-5 w-full rounded-full bg-neutral-900 px-5 py-3 text-sm font-semibold text-white hover:bg-neutral-800">
                        Scan Next Ticket
                    </button>
                </x-card>
            </template>
        </div>
    </div>
</x-layouts.organizer>
