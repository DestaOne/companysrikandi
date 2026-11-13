{{-- Props: href (wajib), icon (dashboard|gallery|site), active; slot: label menu --}}
@props(['href', 'icon' => 'dashboard', 'active' => false])

@php
    $paths = [
        'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
        'gallery' => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.8"/><path d="m21 16-5-5-9 9"/>',
        'site' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3.2 3 14.8 0 18M12 3c-3 3.2-3 14.8 0 18"/>',
    ];
@endphp

<a href="{{ $href }}" title="{{ strip_tags($slot) }}" @if ($active) aria-current="page" @endif
   {{ $attributes->class([
       'group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold transition',
       'bg-gold-sheen text-ink shadow-md' => $active,
       'text-white/70 hover:bg-white/10 hover:text-gold-300' => ! $active,
   ]) }}>
    <svg class="size-6 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$icon] ?? $paths['dashboard'] !!}</svg>
    <span class="hidden lg:inline">{{ $slot }}</span>
</a>
