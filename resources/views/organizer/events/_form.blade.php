@php
    $event = $event ?? null;
@endphp

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <x-input label="Event name" name="name" :value="$event?->name" required />
    </div>

    <div class="sm:col-span-2">
        <x-textarea label="Description" name="description" :value="$event?->description" :rows="5" required />
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-semibold text-neutral-800 mb-1.5">
            Event image @if(!$event)<span class="text-brand">*</span>@endif
        </label>
        @if($event?->image_url)
            <img src="{{ $event->image_url }}" alt="" class="mb-3 h-32 w-full rounded-xl object-cover">
        @endif
        <input type="file" name="image" accept="image/*" class="block w-full text-sm text-neutral-600 file:mr-4 file:rounded-full file:border-0 file:bg-brand-light file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-dark hover:file:bg-orange-100">
        @error('image')
            <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <x-select label="Category" name="category_id" :options="$categories->pluck('name', 'id')" :value="$event?->category_id" placeholder="Select a category" required />

    <x-input label="Venue" name="venue" :value="$event?->venue" required />
    <x-input label="Location (City/Area)" name="location" :value="$event?->location" required />
    <x-input label="Event date" name="event_date" type="date" :value="$event?->event_date?->format('Y-m-d')" required />
    <x-input label="Start time" name="start_time" type="time" :value="$event ? substr($event->start_time, 0, 5) : null" required />
    <x-input label="End time" name="end_time" type="time" :value="$event ? substr($event->end_time, 0, 5) : null" required />
    <x-input label="Contact email" name="contact_email" type="email" :value="$event?->contact_email" />
    <x-input label="Contact phone" name="contact_phone" :value="$event?->contact_phone" />
</div>
