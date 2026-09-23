<x-layouts.admin title="Settings" heading="Platform Settings">
    <x-card class="max-w-2xl">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <x-input label="Platform name" name="platform_name" :value="$settings->platform_name" required />
            <x-input label="Support email" name="support_email" type="email" :value="$settings->support_email" required />
            <x-input label="Support phone" name="support_phone" :value="$settings->support_phone" required />

            <div class="grid grid-cols-2 gap-4">
                <x-input label="Platform fee (%)" name="platform_fee_percent" type="number" min="0" max="100" :value="$settings->platform_fee_percent" required />
                <x-input label="Currency" name="currency" :value="$settings->currency" required />
            </div>

            <x-select
                label="Payment driver"
                name="payment_driver"
                :options="['manual' => 'Manual (local/dev)', 'mpesa' => 'M-Pesa STK Push']"
                :value="$settings->payment_driver"
                hint="M-Pesa requires MPESA_* credentials to be configured in the environment."
                required
            />

            <div class="border-t border-neutral-100 pt-5">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="require_organizer_approval" value="1" {{ old('require_organizer_approval', $settings->require_organizer_approval) ? 'checked' : '' }} class="mt-0.5 rounded border-neutral-300 text-brand focus:ring-brand/30">
                    <span>
                        <span class="block text-sm font-semibold text-neutral-800">Require approval for new organizer accounts</span>
                        <span class="block text-xs text-neutral-500">When on, organizers who register themselves are held as "Pending" until you approve them from the Organizers page. When off, new registrations can log in and create events immediately.</span>
                    </span>
                </label>
            </div>

            <div class="flex justify-end border-t border-neutral-100 pt-6">
                <x-button type="submit" variant="dark">Save Settings</x-button>
            </div>
        </form>
    </x-card>
</x-layouts.admin>
