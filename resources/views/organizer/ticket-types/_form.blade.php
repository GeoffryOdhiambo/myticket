@php
    $ticketType = $ticketType ?? null;
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input label="Ticket name" name="name" :value="$ticketType?->name" placeholder="e.g. Regular, VIP, Couples" required />
    </div>
    <div class="sm:col-span-2">
        <x-textarea label="Short description" name="description" :value="$ticketType?->description" :rows="3" />
    </div>
    <x-input label="Price (KES)" name="price" type="number" min="0" :value="$ticketType?->price" required />
    <x-input label="Available quantity" name="quantity" type="number" min="1" :value="$ticketType?->quantity" hint="Leave blank for unlimited." />
    <x-select label="Status" name="status" :options="['active' => 'Active', 'inactive' => 'Inactive']" :value="$ticketType?->status ?? 'active'" required />
</div>
