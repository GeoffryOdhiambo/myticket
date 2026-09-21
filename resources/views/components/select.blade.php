@props([
    'label' => null,
    'name',
    'options' => [],
    'value' => null,
    'required' => false,
    'placeholder' => null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-neutral-800 mb-1.5">
            {{ $label }} @if($required)<span class="text-brand">*</span>@endif
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full rounded-xl border px-4 py-2.5 text-sm text-neutral-900 transition-colors focus:outline-none focus:ring-2 focus:ring-brand/30 ' . ($errors->has($name) ? 'border-red-300 focus:border-red-400' : 'border-neutral-200 focus:border-brand')]) }}
    >
        @if($placeholder)
            <option value="" @if(old($name, $value) === null) selected @endif>{{ $placeholder }}</option>
        @endif
        @foreach($options as $optValue => $optLabel)
            <option value="{{ $optValue }}" @selected((string) old($name, $value) === (string) $optValue)>{{ $optLabel }}</option>
        @endforeach
    </select>

    @error($name)
        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
