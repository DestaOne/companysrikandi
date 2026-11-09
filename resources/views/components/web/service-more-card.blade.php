{{-- Props: href, label --}}
@props(['href', 'label' => 'Lihat Selengkapnya'])

{{-- Kartu penutup carousel, 288x420 px seperti kartu layanan: emas, berubah hitam saat hover. --}}
<a href="{{ $href }}" {{ $attributes->class('bg-gold-sheen group/card relative isolate flex h-[420px] w-[288px] flex-none items-center justify-center overflow-hidden rounded-xl text-center shadow-[0_12px_30px_-12px_rgb(20_17_10/0.45)] outline-none focus-visible:ring-2 focus-visible:ring-ink') }}>
    <div class="absolute inset-0 -z-10 bg-ink opacity-0 transition-opacity duration-500 group-hover/card:opacity-100 group-focus-visible/card:opacity-100"></div>

    <div class="px-6 text-ink transition-colors duration-500 group-hover/card:text-white group-focus-visible/card:text-white">
        <span class="mx-auto flex size-12 items-center justify-center rounded-full bg-ink text-gold-300 transition duration-500 group-hover/card:bg-gold-300 group-hover/card:text-ink group-focus-visible/card:bg-gold-300 group-focus-visible/card:text-ink">
            <svg class="size-5 transition-transform duration-500 group-hover/card:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </span>
        <h3 class="mt-5 text-xl font-semibold leading-snug">{{ $label }}</h3>
        <p class="mt-1.5 text-sm leading-6 opacity-80">Lihat semua layanan kami</p>
    </div>
</a>
