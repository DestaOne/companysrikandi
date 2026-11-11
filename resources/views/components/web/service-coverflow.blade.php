{{-- Props: items (title, summary, image), href, moreLabel --}}
@props(['items', 'href', 'moreLabel' => 'Lihat Layanan Selengkapnya'])

{{-- Carousel coverflow (diatur Alpine.data('coverflow') di resources/js/coverflow.js). Berputar sendiri, berhenti saat kursor atau fokus ada di dalamnya, bisa digeser dengan tombol, panah keyboard, atau klik kartu samping. --}}
<div x-data="coverflow({{ count($items) + 1 }})" @pointerenter="stop()" @pointerleave="play()" @focusin="stop()" @focusout="play()"
     @keydown.left.prevent="go(-1)" @keydown.right.prevent="go(1)"
     role="region" aria-roledescription="carousel" aria-label="Layanan kami" {{ $attributes->class('relative') }}>
    <div class="relative mx-auto h-[calc(var(--card-w)*var(--card-r)+4rem)] [--card-r:1.35] [--card-w:16rem] sm:[--card-r:0.625] sm:[--card-w:clamp(18rem,30vw,36rem)] overflow-hidden [perspective:1400px]">
        @foreach ($items as $item)
            <x-web.service-card
                :title="$item['title']"
                :summary="$item['summary']"
                :image="$item['image']"
                :href="$href"
                @click="select($event, {{ $loop->index }})"
                ::style="style({{ $loop->index }})"
                ::tabindex="offset({{ $loop->index }}) === 0 ? 0 : -1"
                ::aria-hidden="offset({{ $loop->index }}) !== 0"
                class="absolute left-1/2 top-8 transition-[transform,opacity,filter] duration-700 ease-out" />
        @endforeach

        {{-- Kartu terakhir mengajak melihat semua layanan --}}
        <x-web.service-more-card
            :href="$href" :label="$moreLabel"
            @click="select($event, {{ count($items) }})"
            ::style="style({{ count($items) }})"
            ::tabindex="offset({{ count($items) }}) === 0 ? 0 : -1"
            ::aria-hidden="offset({{ count($items) }}) !== 0"
            class="absolute left-1/2 top-8 transition-[transform,opacity,filter] duration-700 ease-out" />
    </div>

    <button type="button" @click="go(-1)" aria-label="Layanan sebelumnya"
            class="absolute left-3 top-1/2 z-20 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-ink shadow-[0_8px_24px_-6px_rgb(20_17_10/0.45)] ring-1 ring-gold-300/70 outline-none transition hover:bg-ink hover:text-gold-300 focus-visible:ring-2 focus-visible:ring-gold-500 sm:left-6 sm:size-12">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
    </button>
    <button type="button" @click="go(1)" aria-label="Layanan berikutnya"
            class="absolute right-3 top-1/2 z-20 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-ink shadow-[0_8px_24px_-6px_rgb(20_17_10/0.45)] ring-1 ring-gold-300/70 outline-none transition hover:bg-ink hover:text-gold-300 focus-visible:ring-2 focus-visible:ring-gold-500 sm:right-6 sm:size-12">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
    </button>
</div>
