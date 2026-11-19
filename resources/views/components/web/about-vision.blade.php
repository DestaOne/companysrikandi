{{-- Props: title, text; atribut tambahan (mis. class) diteruskan ke <article> --}}
@props(['title', 'text'])

<article {{ $attributes->class('flex min-w-0 flex-col border border-gold-300 border-t-4 border-t-gold-500 bg-white p-8 shadow-md sm:p-10') }}>
    <div class="flex items-center gap-4">
        <svg class="size-9 shrink-0 text-gold-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
        <h2 class="min-w-0 text-3xl font-bold tracking-tight text-ink wrap-anywhere">{{ $title }}</h2>
    </div>
    <span class="bg-gold-metal mt-6 block h-0.5 w-16"></span>
    <p class="mt-6 text-lg font-medium leading-8 text-ink wrap-anywhere">{{ $text }}</p>
</article>
