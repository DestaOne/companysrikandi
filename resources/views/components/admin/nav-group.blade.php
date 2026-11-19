{{-- Props: label (nama kategori); slot: daftar x-admin.nav-link. Di layar kecil (hanya ikon) judul diganti garis pemisah. --}}
@props(['label'])

<div {{ $attributes->class('space-y-2') }}>
    <p class="hidden px-3 text-xs font-bold uppercase tracking-widest text-gold-300/80 lg:block">{{ $label }}</p>
    <div class="mx-2 border-t border-white/10 lg:hidden"></div>
    {{ $slot }}
</div>
