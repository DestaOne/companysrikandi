{{-- Props: items (title, summary, image), href, moreLabel, copies --}}
@props(['items', 'href', 'moreLabel' => 'Lihat Selengkapnya', 'copies' => 4])

{{-- Carousel berjalan sendiri dari kanan ke kiri (diatur Alpine.data('carousel') di resources/js/carousel.js). Bisa digeser dengan tombol atau drag, dan berhenti saat kursor atau fokus ada di dalamnya. Isi digandakan agar putaran menyambung tanpa jeda. --}}
<div x-data="carousel" @pointerenter="enter($event)" @pointerleave="leave()" @focusin="focusIn($event)" @focusout="focusOut()" role="region" aria-roledescription="carousel" aria-label="Layanan kami" {{ $attributes->class('relative') }}>
    <div x-ref="viewport" @pointerdown="down($event)" @pointermove="move($event)" @pointerup="up($event)" @pointercancel="up($event)" class="cursor-grab touch-pan-y select-none overflow-hidden py-6 [mask-image:linear-gradient(to_right,transparent,black_5%,black_95%,transparent)] data-[dragging=true]:cursor-grabbing motion-reduce:overflow-x-auto">
        <div x-ref="track" class="flex w-max">
            @foreach (range(1, $copies * 2) as $copy)
                <div class="flex flex-none gap-6 pr-6" @if ($copy > 1) aria-hidden="true" @endif>
                    @foreach ($items as $item)
                        <x-web.service-card
                            :title="$item['title']"
                            :summary="$item['summary']"
                            :image="$item['image']"
                            :href="$href"
                            :tabindex="$copy > 1 ? -1 : null" />
                    @endforeach
                    <x-web.service-more-card :href="$href" :label="$moreLabel" :tabindex="$copy > 1 ? -1 : null" />
                </div>
            @endforeach
        </div>
    </div>

    <button type="button" @click="go(-1)" aria-label="Layanan sebelumnya"
            class="absolute left-3 top-1/2 z-10 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-ink shadow-[0_8px_24px_-6px_rgb(20_17_10/0.45)] ring-1 ring-gold-300/70 outline-none transition hover:bg-ink hover:text-gold-300 focus-visible:ring-2 focus-visible:ring-gold-500 sm:left-6 sm:size-12">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
    </button>
    <button type="button" @click="go(1)" aria-label="Layanan berikutnya"
            class="absolute right-3 top-1/2 z-10 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-ink shadow-[0_8px_24px_-6px_rgb(20_17_10/0.45)] ring-1 ring-gold-300/70 outline-none transition hover:bg-ink hover:text-gold-300 focus-visible:ring-2 focus-visible:ring-gold-500 sm:right-6 sm:size-12">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
    </button>
</div>
