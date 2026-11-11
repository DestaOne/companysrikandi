{{-- Props: items (title, description, image) --}}
@props(['items'])

{{-- Panel foto yang saling berbagi lebar (foto dan teks berukuran tetap, hanya jendelanya yang berubah, supaya animasi tidak memaksa render ulang berat): yang aktif (hover, fokus, atau ketuk) melebar dan menampilkan deskripsi, yang lain menyempit dengan judul tegak. Di mobile panel bertumpuk ke bawah. Hover hanya berlaku untuk mouse dan fokus hanya untuk keyboard; ketukan di layar sentuh tidak membuka panel lebih dulu, sehingga ketukan pertama melebarkan panel dan ketukan kedua membuka pratinjau gambar. --}}
<div x-data="{ active: 0 }" {{ $attributes->class('flex flex-col gap-3 md:h-[30rem] md:flex-row') }}>
    @foreach ($items as $item)
        <article tabindex="0" role="button" aria-label="{{ $item['title'] }}"
                 @pointerenter="if ($event.pointerType === 'mouse') active = {{ $loop->index }}" @focus="if ($el.matches(':focus-visible')) active = {{ $loop->index }}"
                 @click="active === {{ $loop->index }} ? $dispatch('open-image', { src: @js(asset($item['image'])), title: @js($item['title']) }) : active = {{ $loop->index }}"
                 @keydown.enter.prevent="$dispatch('open-image', { src: @js(asset($item['image'])), title: @js($item['title']) })"
                 :class="active === {{ $loop->index }} ? 'h-80 cursor-zoom-in md:h-auto md:flex-[5]' : 'h-20 cursor-pointer md:h-auto md:flex-[1]'"
                 class="relative isolate overflow-hidden rounded-3xl bg-ink outline-none [contain:layout_paint] transition-[flex-grow,height] duration-500 ease-out focus-visible:ring-2 focus-visible:ring-gold-500">
            <img src="{{ asset($item['image']) }}" alt="" loading="lazy" draggable="false" class="absolute inset-y-0 left-1/2 -z-20 h-full w-full max-w-none -translate-x-1/2 transform-gpu object-cover md:w-[56rem]">
            {{-- Dua lapisan gelap yang hanya berganti opacity: murah bagi GPU, tanpa transisi gradien --}}
            <div class="absolute inset-0 -z-10 bg-ink/60 transition-opacity duration-500" :class="active === {{ $loop->index }} ? 'opacity-0' : 'opacity-100'"></div>
            <div class="absolute inset-0 -z-10 bg-linear-to-t from-ink/90 via-ink/45 to-ink/5 transition-opacity duration-500" :class="active === {{ $loop->index }} ? 'opacity-100' : 'opacity-0'"></div>

            {{-- Judul saat menyempit: tegak di layar lebar, mendatar di mobile --}}
            <h3 class="absolute bottom-0 left-0 p-5 text-lg font-semibold text-white transition-opacity duration-300 md:p-0 md:pb-6 md:left-1/2 md:-translate-x-1/2 md:[text-shadow:0_1px_8px_rgb(0_0_0/0.6)] md:[writing-mode:vertical-rl] md:rotate-180"
                :class="active === {{ $loop->index }} ? 'opacity-0' : 'opacity-100 delay-300'">{{ $item['title'] }}</h3>

            {{-- Isi saat melebar --}}
            <div class="absolute bottom-0 left-0 w-full max-w-xl p-6 text-white md:w-[28rem] transition-opacity duration-500 sm:p-8"
                 :class="active === {{ $loop->index }} ? 'opacity-100 delay-300' : 'pointer-events-none opacity-0'">
                <h3 class="text-2xl font-semibold leading-snug">{{ $item['title'] }}</h3>
                <p class="mt-3 text-sm leading-6 text-white/90 sm:text-base sm:leading-7">{{ $item['description'] }}</p>
            </div>
        </article>
    @endforeach
</div>
