{{-- Props: image (URL), title, description; ukuran petak diatur oleh x-web.gallery-grid --}}
@props(['image', 'title', 'description' => null])

<figure x-data tabindex="0" role="button" aria-label="Perbesar gambar: {{ $title }}"
        @click="$dispatch('open-image', { src: @js($image), title: @js($title) })"
        @keydown.enter.prevent="$dispatch('open-image', { src: @js($image), title: @js($title) })"
        {{ $attributes->class(['group relative isolate cursor-zoom-in overflow-hidden bg-ink outline-none focus-visible:ring-2 focus-visible:ring-gold-500']) }}>
    <img src="{{ $image }}" alt="{{ $title }}" loading="lazy"
         class="absolute inset-0 size-full object-cover transition duration-700 ease-out group-hover:scale-105">

    <div class="absolute inset-0 bg-linear-to-t from-ink/85 via-ink/10 to-transparent opacity-80 transition duration-500 group-hover:opacity-100"></div>
    {{-- Gelap merata saat hover agar judul dan deskripsi terbaca di foto yang terang --}}
    <div class="absolute inset-0 bg-ink/50 opacity-0 transition duration-500 group-hover:opacity-100"></div>

    {{-- Bingkai emas tipis muncul saat hover --}}
    <div class="pointer-events-none absolute inset-3 border border-gold-300 opacity-0 transition duration-500 group-hover:opacity-100"></div>

    <figcaption class="absolute inset-x-0 bottom-0 p-4 text-white sm:p-5">
        <p class="text-sm font-semibold leading-snug [text-shadow:0_1px_8px_rgb(0_0_0/0.6)] sm:text-base">{{ $title }}</p>
        @if ($description)
            <p class="mt-1 line-clamp-2 max-h-0 overflow-hidden text-sm leading-6 text-white/85 opacity-0 transition-all duration-500 group-hover:max-h-16 group-hover:opacity-100">{{ $description }}</p>
        @endif
    </figcaption>
</figure>
