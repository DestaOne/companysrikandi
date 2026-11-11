{{-- Props: title, image, description, split (true = teks di kiri dengan panel gelap miring, foto terlihat di kanan); slot: actions --}}
@props(['title', 'image', 'description' => null, 'split' => false])

@if ($split)
    {{-- Panel gelap transparan menutup layar lalu membuka (clip-path, satu elemen saja) sampai foto di kanan terlihat. Di bawah lg panel menutup penuh agar teks tetap terbaca. Teks naik pelan setelah panel mulai membuka. --}}
    <section {{ $attributes->class('relative isolate flex min-h-svh items-center overflow-hidden bg-ink') }}>
        <img src="{{ asset($image) }}" alt="" fetchpriority="high" class="absolute inset-0 -z-20 size-full object-cover object-[70%_center]">
        <div class="hero-panel absolute inset-0 -z-10 bg-ink/75"></div>

        <div class="mx-auto w-full max-w-7xl px-4 py-24 text-white sm:px-6 lg:px-8">
            <div class="hero-rise max-w-xl lg:max-w-[44%]">
                <h1 class="text-balance text-4xl font-semibold leading-tight tracking-tight [text-shadow:0_2px_16px_rgb(0_0_0/0.55)] sm:text-5xl">{{ $title }}</h1>

                @if ($description)
                    <p class="mt-6 text-pretty text-base font-medium leading-8 text-white [text-shadow:0_1px_10px_rgb(0_0_0/0.6)] md:text-lg">{{ $description }}</p>
                @endif

                @isset($actions)
                    <div class="mt-10 flex flex-wrap items-center gap-4">{{ $actions }}</div>
                @endisset
            </div>
        </div>

        <div class="bg-gold-metal absolute inset-x-0 bottom-0 z-10 h-1"></div>
    </section>
@else
    {{-- Setinggi layar; isi berada tepat di tengah. Navbar transparan menumpuk di atasnya, jadi py-24 menjaga isi tetap jauh dari navbar di layar pendek. --}}
    <section {{ $attributes->class('relative isolate flex min-h-svh items-center justify-center overflow-hidden bg-ink') }}>
        <img src="{{ asset($image) }}" alt="" fetchpriority="high" class="absolute inset-0 -z-20 size-full object-cover">
        <div class="absolute inset-0 -z-10 bg-linear-to-b from-ink/80 via-ink/70 to-ink/85"></div>

        <div class="relative z-10 mx-auto max-w-5xl px-4 py-24 text-center text-white sm:px-6 lg:px-8">
            <h1 class="text-balance text-4xl font-semibold leading-tight tracking-tight [text-shadow:0_2px_16px_rgb(0_0_0/0.55)] sm:text-5xl md:text-6xl">{{ $title }}</h1>

            @if ($description)
                <p class="mx-auto mt-6 max-w-3xl text-pretty text-base font-medium leading-8 text-white [text-shadow:0_1px_10px_rgb(0_0_0/0.6)] md:text-lg">{{ $description }}</p>
            @endif

            @isset($actions)
                <div class="mt-10 flex flex-wrap items-center justify-center gap-4">{{ $actions }}</div>
            @endisset
        </div>

        <div class="bg-gold-metal absolute inset-x-0 bottom-0 z-10 h-1"></div>
    </section>
@endif
