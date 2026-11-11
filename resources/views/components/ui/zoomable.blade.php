{{-- Props: src (URL gambar penuh), title; slot: isi yang bisa diklik (biasanya <img>) --}}
@props(['src', 'title' => ''])

<div x-data tabindex="0" role="button" aria-label="Perbesar gambar: {{ $title }}"
     @click="$dispatch('open-image', { src: @js($src), title: @js($title) })"
     @keydown.enter.prevent="$dispatch('open-image', { src: @js($src), title: @js($title) })"
     {{ $attributes->class('cursor-zoom-in outline-none focus-visible:ring-2 focus-visible:ring-gold-500') }}>
    {{ $slot }}
</div>
