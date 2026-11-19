{{-- Props: name (wajib), label, current (URL gambar saat ini, untuk halaman edit), hint (teks bantuan), contain (true = gambar utuh tanpa dipotong, untuk logo), dark (true = latar pratinjau gelap, untuk logo terang); slot actions (tombol tambahan di bawah teks bantuan, opsional) --}}
@props(['name', 'label' => null, 'current' => null, 'hint' => 'JPG, PNG, atau WEBP, maksimal 2 MB.', 'contain' => false, 'dark' => false])

@php
    // Nama bentuk items[0][title] dicocokkan ke kunci error items.0.title
    $errorKey = rtrim(str_replace(['][', '[', ']'], ['.', '.', ''], $name), '.');
@endphp

<div class="space-y-1.5"
     x-data="imagePreview(@js($current))">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm sm:text-base font-semibold text-ink">{{ $label }}</label>
    @endif

    <div class="flex flex-col gap-3 rounded-md border border-dashed bg-gold-50/50 p-3 sm:gap-4 sm:p-4 sm:flex-row sm:items-center {{ $errors->has($errorKey) ? 'border-red-500' : 'border-gold-300' }}">
        {{-- Pratinjau gambar; ikon placeholder saat belum ada gambar --}}
        <div class="flex h-20 w-full shrink-0 items-center justify-center overflow-hidden rounded-md {{ $dark ? 'bg-ink' : 'bg-white' }} ring-1 ring-gold-300/60 sm:h-28 sm:w-44">
            <img x-show="preview" x-cloak :src="preview" alt="Pratinjau" class="size-full {{ $contain ? 'object-contain p-2' : 'object-cover' }}">
            <svg x-show="!preview" class="size-8 text-gold-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.8"/><path d="m21 16-5-5-9 9"/></svg>
        </div>

        <div class="min-w-0 flex-1 space-y-2">
            <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/*"
                   @change="pick($event)"
                   {{ $attributes->merge(['class' => 'block w-full text-sm text-ink file:mr-4 file:cursor-pointer file:rounded-md file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-ink-800']) }}>
            @if ($hint)
                <p class="text-xs text-ink-800/80">{{ $hint }}</p>
            @endif
            @isset($actions)
                {{ $actions }}
            @endisset
        </div>
    </div>

    @error($errorKey)
        <p class="text-sm font-medium text-red-600">{{ $message }}</p>
    @enderror
</div>
