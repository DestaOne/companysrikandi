{{-- Props: variant (default|success|danger) --}}
@props(['variant' => 'default'])

@php
    $classes = match ($variant) {
        'success' => 'bg-green-50 text-green-800',
        'danger' => 'bg-red-50 text-red-800',
        default => 'bg-gold-100 text-gold-900',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold '.$classes]) }}>{{ $slot }}</span>
