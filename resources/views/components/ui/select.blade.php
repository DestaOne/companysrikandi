{{-- Props: name (wajib), label, options (nilai => teks), value --}}
@props(['name', 'label' => null, 'options' => [], 'value' => ''])

@php
    $field = 'block w-full rounded-md border-0 px-3 py-2 text-sm text-ink ring-1 ring-gold-300 outline-none focus:ring-2 focus:ring-gold-500';
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif

    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => $field]) }}>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $optionValue === (string) $value)>{{ $optionLabel }}</option>
        @endforeach
    </select>

    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
