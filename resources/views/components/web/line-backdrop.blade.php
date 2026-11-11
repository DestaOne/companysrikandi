{{-- Props: tidak ada. Hiasan latar ringan (garis dan ranting emas tipis); letakkan di dalam section "relative overflow-hidden". --}}
<div {{ $attributes->class('pointer-events-none absolute inset-0 -z-0 text-gold-300') }} aria-hidden="true">
    {{-- Akar utama yang berkelok turun sepanjang halaman; stroke tidak ikut melar --}}
    <svg class="absolute inset-0 size-full opacity-60" viewBox="0 0 1000 2000" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="1.2">
        <path vector-effect="non-scaling-stroke" d="M500 0 C380 180 620 320 500 520 C380 720 620 860 500 1060 C380 1260 620 1400 500 1600 C420 1760 560 1880 500 2000"/>
        <path vector-effect="non-scaling-stroke" opacity=".6" d="M500 0 C620 200 380 340 500 540 C620 740 380 880 500 1080 C620 1280 380 1420 500 1620 C580 1780 440 1900 500 2000"/>
    </svg>

    {{-- Ranting kecil di tepi kiri dan kanan (hanya layar lebar) --}}
    <svg class="absolute -left-10 top-[18%] hidden w-64 opacity-70 lg:block" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
        <path d="M0 190 C50 150 70 90 130 60 M60 135 C60 105 78 90 100 88 M88 100 C92 78 110 66 132 66 M30 165 C28 142 40 128 60 125"/>
    </svg>
    <svg class="absolute -right-10 top-[48%] hidden w-64 -scale-x-100 opacity-70 lg:block" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
        <path d="M0 190 C50 150 70 90 130 60 M60 135 C60 105 78 90 100 88 M88 100 C92 78 110 66 132 66 M30 165 C28 142 40 128 60 125"/>
    </svg>
    <svg class="absolute -left-10 top-[78%] hidden w-64 opacity-70 lg:block" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round">
        <path d="M0 190 C50 150 70 90 130 60 M60 135 C60 105 78 90 100 88 M88 100 C92 78 110 66 132 66 M30 165 C28 142 40 128 60 125"/>
    </svg>

    {{-- Lingkaran tipis sebagai aksen --}}
    <div class="absolute -right-24 top-24 size-72 rounded-full border border-gold-300/50"></div>
    <div class="absolute -left-20 bottom-24 size-60 rounded-full border border-gold-300/40"></div>
</div>
