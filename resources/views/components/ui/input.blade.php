{{-- Props: name (wajib), label, type, value, size (md|lg), hint (teks bantuan di bawah kolom) --}}
@props(['name', 'label' => null, 'type' => 'text', 'value' => '', 'size' => 'md', 'hint' => null])

@php
    // Nama bentuk items[0][title] dicocokkan ke kunci error items.0.title
    $errorKey = rtrim(str_replace(['][', '[', ']'], ['.', '.', ''], $name), '.');
    $field = $size === 'lg'
        ? 'block w-full rounded-lg border-0 bg-white px-3 py-2.5 text-sm sm:py-3 text-ink ring-1 ring-gold-300 outline-none focus:ring-2 focus:ring-gold-500'
        : 'block w-full rounded-md border bg-white px-3 py-2 sm:px-3.5 sm:py-2.5 text-sm text-ink outline-none transition placeholder:text-ink-800/50 focus:ring-2 '
            .($errors->has($errorKey) ? 'border-red-500 focus:ring-red-200' : 'border-ink/25 hover:border-ink/40 focus:border-gold-500 focus:ring-gold-300/60');
@endphp

<div class="space-y-1.5">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm sm:text-base text-ink {{ $size === 'lg' ? 'font-medium' : 'font-semibold' }}">{{ $label }}</label>
    @endif

    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}" {{ $attributes->merge(['class' => $field]) }}>

    @if ($hint)
        <p class="text-xs text-ink-800/80">{{ $hint }}</p>
    @endif

    @error($errorKey)
        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
