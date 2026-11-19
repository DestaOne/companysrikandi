{{-- Props: href (wajib kecuali submit), icon (dashboard|home|service|gallery|about|activity|mail|site|logo|logout), active, badge (jumlah merah di ikon; 0 = tanpa), button (true = tampil sebagai tombol, bukan tautan; aksinya lewat atribut Alpine), danger (true = hover merah, untuk aksi seperti keluar); atribut tambahan (mis. target) diteruskan; slot: label menu --}}
@props(['href' => null, 'icon' => 'dashboard', 'active' => false, 'button' => false, 'danger' => false, 'badge' => 0])

@php
    $paths = [
        'dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
        'home' => '<path d="m3 11 9-8 9 8"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
        'service' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
        'gallery' => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.8"/><path d="m21 16-5-5-9 9"/>',
        'about' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6"/><path d="M12 7.5h.01"/>',
        'activity' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>',
        'logo' => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.8"/><path d="m21 16-5-5-9 9"/>',
        'site' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3.2 3 14.8 0 18M12 3c-3 3.2-3 14.8 0 18"/>',
    ];
@endphp

@php
    $classes = [
        'group flex w-full items-center justify-center gap-3 rounded-xl px-2 py-2.5 text-sm lg:justify-start lg:px-3 lg:py-3 font-semibold transition',
        'bg-gold-sheen text-ink shadow-md' => $active,
        'text-white/70 hover:bg-white/10 hover:text-gold-300' => ! $active && ! $danger,
        'text-white/70 hover:bg-red-600 hover:text-white' => $danger,
    ];
@endphp

@if ($button)
    <button type="button" title="{{ strip_tags($slot) }}" {{ $attributes->class($classes) }}>
@else
    <a href="{{ $href }}" title="{{ strip_tags($slot) }}" @if ($active) aria-current="page" @endif {{ $attributes->class($classes) }}>
@endif
    <span class="relative shrink-0">
        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$icon] ?? $paths['dashboard'] !!}</svg>
        @if ($badge > 0)
            <span class="absolute -right-2 -top-2 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold leading-none text-white ring-2 ring-ink-950">{{ $badge > 99 ? '99+' : $badge }}</span>
        @endif
    </span>
    <span class="hidden lg:inline">{{ $slot }}</span>
@if ($button)
    </button>
@else
    </a>
@endif
