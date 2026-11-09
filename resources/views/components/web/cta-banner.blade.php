{{-- Props: title, image, href, label, description --}}
@props(['title', 'image', 'href', 'label', 'description' => null])

{{-- Selebar layar, tinggi minimal 400px; teks dan tombol berada di tengah. --}}
<section {{ $attributes->class('relative isolate flex min-h-[400px] w-full items-center overflow-hidden bg-ink') }}>
    <img src="{{ asset($image) }}" alt="" loading="lazy" class="absolute inset-0 -z-20 size-full object-cover">
    <div class="absolute inset-0 -z-10 bg-linear-to-r from-ink/95 via-gold-900/85 to-gold-500/70"></div>

    <div class="mx-auto flex w-full max-w-4xl flex-col items-center px-4 py-12 text-center text-white sm:px-6 md:py-16">
        <span class="bg-gold-metal mb-4 block h-0.5 w-12"></span>
        <h2 class="text-balance text-2xl font-semibold leading-tight tracking-tight sm:text-3xl md:text-4xl">{{ $title }}</h2>
        @if ($description)
            <p class="mt-3 max-w-2xl text-pretty text-base leading-7 text-white/85">{{ $description }}</p>
        @endif

        <a href="{{ $href }}"
           class="bg-gold-sheen mt-6 inline-flex h-12 items-center gap-2 px-8 text-[15px] font-semibold text-ink outline-none transition hover:brightness-95 focus-visible:ring-2 focus-visible:ring-white">
            {{ $label }}
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
    </div>
</section>
