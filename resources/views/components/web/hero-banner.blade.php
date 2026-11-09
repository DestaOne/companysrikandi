{{-- Props: title, image, description; slot: actions --}}
@props(['title', 'image', 'description' => null])

{{-- Setinggi layar; isi berada tepat di tengah. Navbar transparan menumpuk di atasnya, jadi py-24 menjaga isi tetap jauh dari navbar di layar pendek. --}}
<section {{ $attributes->class('relative isolate flex min-h-svh items-center justify-center overflow-hidden bg-ink') }}>
    <img src="{{ asset($image) }}" alt="" fetchpriority="high" class="absolute inset-0 -z-20 size-full object-cover">
    <div class="absolute inset-0 -z-10 bg-linear-to-b from-ink/70 via-ink/55 to-ink/80"></div>

    <div class="relative z-10 mx-auto max-w-5xl px-4 py-24 text-center text-white sm:px-6 lg:px-8">
        <h1 class="text-balance text-4xl font-semibold leading-tight tracking-tight sm:text-5xl md:text-6xl">{{ $title }}</h1>

        @if ($description)
            <p class="mx-auto mt-6 max-w-3xl text-pretty text-base leading-8 text-white/85 md:text-lg">{{ $description }}</p>
        @endif

        @isset($actions)
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">{{ $actions }}</div>
        @endisset
    </div>

    <div class="bg-gold-metal absolute inset-x-0 bottom-0 z-10 h-1"></div>
</section>
