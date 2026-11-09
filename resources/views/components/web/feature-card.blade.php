{{-- Props: title, description, image --}}
@props(['title', 'description', 'image'])

<article {{ $attributes->class('group relative isolate flex min-h-72 items-end overflow-hidden rounded-xl bg-ink') }}>
    <img src="{{ asset($image) }}" alt="" loading="lazy" class="absolute inset-0 -z-20 size-full object-cover transition duration-700 group-hover:scale-105">
    <div class="absolute inset-0 -z-10 bg-linear-to-t from-ink/95 via-ink/65 to-ink/10"></div>

    <div class="max-w-xl p-6 text-white sm:p-8">
        <h3 class="text-2xl font-semibold leading-snug">{{ $title }}</h3>
        <p class="mt-3 text-sm leading-6 text-white/85 sm:text-base sm:leading-7">{{ $description }}</p>
    </div>
</article>
