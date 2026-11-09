{{-- Props: variant (primary|secondary|danger), type, href (jika diisi, tampil sebagai link) --}}
@props(['variant' => 'primary', 'type' => 'button', 'href' => null])

@php
    $classes = match ($variant) {
        'secondary' => 'bg-white text-ink ring-1 ring-gold-300 hover:bg-gold-50',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        default => 'bg-gold-sheen text-ink hover:brightness-95',
    };
    $classes = 'inline-flex h-10 items-center justify-center gap-2 rounded-md px-5 text-sm font-semibold outline-none transition focus-visible:ring-2 focus-visible:ring-gold-500 disabled:opacity-50 '.$classes;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
