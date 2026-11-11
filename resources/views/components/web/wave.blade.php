{{-- Props: flip (balik vertikal, untuk tepi atas bagian). Warna isi mengikuti text-* dari pemakai. --}}
@props(['flip' => false])

{{-- Dua periode gelombang berdampingan, digeser 50% agar perulangan tanpa sambungan. --}}
<div {{ $attributes->class(['pointer-events-none absolute inset-x-0 overflow-hidden leading-none', $flip ? 'top-0 rotate-180' : 'bottom-0']) }} aria-hidden="true">
    <svg class="animate-wave block h-10 w-[200%] max-w-none sm:h-14 md:h-16" viewBox="0 0 2880 80" preserveAspectRatio="none" fill="currentColor">
        <path d="M0 40 C180 0 540 80 720 40 C900 0 1260 80 1440 40 C1620 0 1980 80 2160 40 C2340 0 2700 80 2880 40 V80 H0 Z"/>
    </svg>
</div>
