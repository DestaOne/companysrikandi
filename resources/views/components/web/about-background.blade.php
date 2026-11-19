{{-- Props: data (eyebrow, title, image, paragraphs); atribut tambahan diteruskan ke <section> --}}
@props(['data'])

<section {{ $attributes->class('relative overflow-hidden bg-white py-16 md:py-24') }}>
    <x-web.line-backdrop />

    <div class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-16 lg:px-8">
        <div class="relative">
            <div class="absolute -bottom-4 -right-4 size-full border-2 border-gold-300"></div>
            <x-ui.zoomable :src="asset($data['image'])" title="Latar belakang perusahaan" class="relative">
                <img src="{{ asset($data['image']) }}" alt="Latar belakang perusahaan" loading="lazy" class="aspect-[4/3] w-full object-cover shadow-lg">
            </x-ui.zoomable>
        </div>

        <div>
            <p class="text-sm font-semibold uppercase tracking-widest text-gold-700">{{ $data['eyebrow'] }}</p>
            <h2 class="mt-3 text-balance wrap-anywhere text-3xl font-semibold tracking-tight sm:text-4xl">{{ $data['title'] }}</h2>
            <span class="bg-gold-metal mt-5 block h-0.5 w-16"></span>
            <div class="mt-6 space-y-4 text-base leading-8 text-ink-800 wrap-anywhere">
                @foreach ($data['paragraphs'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </div>
</section>
