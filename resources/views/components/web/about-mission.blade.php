{{-- Props: title, items (daftar poin misi); atribut tambahan (mis. class) diteruskan ke <article> --}}
@props(['title', 'items'])

<article {{ $attributes->class('flex min-w-0 flex-col border border-gold-300 border-t-4 border-t-gold-500 bg-white p-8 shadow-md sm:p-10') }}>
    <div class="flex items-center gap-4">
        <svg class="size-9 shrink-0 text-gold-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 21V4M4 4h12l-2 4 2 4H4"/></svg>
        <h2 class="min-w-0 text-3xl font-bold tracking-tight text-ink wrap-anywhere">{{ $title }}</h2>
    </div>
    <span class="bg-gold-metal mt-6 block h-0.5 w-16"></span>
    <ul class="mt-6 space-y-4 text-lg leading-8 text-ink">
        @foreach ($items as $item)
            <li class="flex gap-4">
                <span class="bg-gold-metal mt-3 block size-2.5 shrink-0 rounded-full"></span>
                <span class="min-w-0 font-medium wrap-anywhere">{{ $item }}</span>
            </li>
        @endforeach
    </ul>
</article>
