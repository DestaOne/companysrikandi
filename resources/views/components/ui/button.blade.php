{{-- Props: variant (primary|secondary|danger|dark), size (md|lg), type, href (jika diisi, tampil sebagai link) --}}
@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'href' => null])

@php
    $classes = match ($variant) {
        'secondary' => 'bg-white text-ink ring-1 ring-gold-300 hover:bg-gold-50',
        'dark' => 'bg-ink text-white hover:bg-maroon',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        default => 'bg-gold-sheen text-ink hover:brightness-95',
    };
    $shape = $size === 'lg' ? 'h-11 rounded-lg' : 'h-10 rounded-md';
    $classes = $shape.' inline-flex items-center justify-center gap-2 px-5 text-sm font-semibold outline-none transition focus-visible:ring-2 focus-visible:ring-gold-500 disabled:opacity-50 '.$classes;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
