@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'hint' => null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-neutral-800 mb-1.5">
            {{ $label }} @if($required)<span class="text-brand">*</span>@endif
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full rounded-xl border px-4 py-2.5 text-sm text-neutral-900 placeholder-neutral-400 transition-colors focus:outline-none focus:ring-2 focus:ring-brand/30 ' . ($errors->has($name) ? 'border-red-300 focus:border-red-400' : 'border-neutral-200 focus:border-brand')]) }}
    >

    @if($hint && !$errors->has($name))
        <p class="mt-1.5 text-xs text-neutral-500">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="mt-1.5 text-xs font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
