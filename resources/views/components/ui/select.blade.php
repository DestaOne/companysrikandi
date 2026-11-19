{{-- Props: name (wajib), label, options (nilai => teks), value, hint (teks bantuan di bawah kolom) --}}
@props(['name', 'label' => null, 'options' => [], 'value' => '', 'hint' => null])

@php
    // Nama bentuk items[0][title] dicocokkan ke kunci error items.0.title
    $errorKey = rtrim(str_replace(['][', '[', ']'], ['.', '.', ''], $name), '.');
    $field = 'block w-full rounded-md border bg-white px-3 py-2 sm:px-3.5 sm:py-2.5 text-sm text-ink outline-none transition focus:ring-2 '
        .($errors->has($errorKey) ? 'border-red-500 focus:ring-red-200' : 'border-ink/25 hover:border-ink/40 focus:border-gold-500 focus:ring-gold-300/60');
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm sm:text-base font-semibold text-ink">{{ $label }}</label>
    @endif

    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => $field]) }}>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $optionValue === (string) $value)>{{ $optionLabel }}</option>
        @endforeach
    </select>

    @if ($hint)
        <p class="text-xs text-ink-800/80">{{ $hint }}</p>
    @endif

    @error($errorKey)
        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
