{{-- Props: title, summary, image, href --}}
@props(['title', 'summary', 'image', 'href'])

{{-- Kartu potret 288x420 px. Sebelum hover: foto penuh dengan teks di kiri bawah. Saat hover atau fokus: foto menyusut ke atas, panel putih berisi informasi muncul dari bawah. --}}
<a href="{{ $href }}" {{ $attributes->class('group/card relative block h-[420px] w-[288px] flex-none overflow-hidden rounded-xl bg-ink shadow-[0_12px_30px_-12px_rgb(20_17_10/0.45)] outline-none focus-visible:ring-2 focus-visible:ring-gold-500') }}>
    <div class="absolute inset-x-0 top-0 h-full overflow-hidden transition-[height] duration-500 ease-out group-hover/card:h-[45%] group-focus-visible/card:h-[45%]">
        <img src="{{ asset($image) }}" alt="" loading="lazy" class="size-full object-cover">
        <div class="absolute inset-0 bg-linear-to-t from-ink/90 via-ink/40 to-transparent"></div>

        <div class="absolute inset-x-0 bottom-0 p-6 text-white transition-opacity duration-200 group-hover/card:opacity-0 group-focus-visible/card:opacity-0">
            <h3 class="text-xl font-semibold leading-snug">{{ $title }}</h3>
            <p class="mt-2 line-clamp-2 text-sm leading-6 text-white/85">{{ $summary }}</p>
        </div>
    </div>

    <div class="absolute inset-x-0 bottom-0 flex h-[55%] translate-y-full flex-col items-center justify-center bg-white px-6 text-center transition-transform duration-500 ease-out group-hover/card:translate-y-0 group-focus-visible/card:translate-y-0">
        <h3 class="text-lg font-semibold leading-snug text-ink">{{ $title }}</h3>
        <p class="mt-2 line-clamp-3 text-sm leading-6 text-ink-800">{{ $summary }}</p>
        <span class="bg-gold-sheen mt-4 inline-flex h-10 items-center gap-2 px-5 text-sm font-semibold text-ink">
            Selengkapnya
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </span>
    </div>
</a>
