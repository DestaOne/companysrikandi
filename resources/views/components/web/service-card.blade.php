{{-- Props: title, summary, image, href --}}
@props(['title', 'summary', 'image', 'href'])

{{-- Kartu landscape rasio 8:5 (posisi absolute diberikan pemakai, misal coverflow), lebarnya 30% layar (18-36rem) sehingga sekitar tiga kartu terlihat sekaligus. Foto penuh dengan judul di kiri bawah; saat hover atau fokus foto digelapkan dan ringkasan muncul. --}}
<a href="{{ $href }}" {{ $attributes->class('group/card block h-[calc(var(--card-w)*var(--card-r))] w-(--card-w) flex-none overflow-hidden rounded-xl bg-ink shadow-[0_12px_30px_-12px_rgb(20_17_10/0.45)] outline-none focus-visible:ring-2 focus-visible:ring-gold-500') }}>
    <img src="{{ asset($image) }}" alt="" loading="lazy" draggable="false" class="size-full object-cover transition-transform duration-500 group-hover/card:scale-105 group-focus-visible/card:scale-105">
    <div class="absolute inset-0 bg-linear-to-t from-ink/90 via-ink/30 to-transparent transition-colors duration-300 group-hover/card:bg-ink/75 group-focus-visible/card:bg-ink/75"></div>

    <div class="absolute inset-x-0 bottom-0 p-6 text-white">
        <h3 class="text-2xl font-semibold leading-snug [text-shadow:0_1px_8px_rgb(0_0_0/0.6)]">{{ $title }}</h3>
        <p class="mt-2 line-clamp-3 max-h-20 overflow-hidden text-sm leading-6 text-white transition-all sm:mt-0 sm:max-h-0 sm:opacity-0 duration-300 group-hover/card:mt-2 group-hover/card:max-h-20 group-hover/card:opacity-100 group-focus-visible/card:mt-2 group-focus-visible/card:max-h-20 group-focus-visible/card:opacity-100">{{ $summary }}</p>
    </div>
</a>
