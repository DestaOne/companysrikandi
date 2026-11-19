{{-- Props: title, back (URL; bila diisi tampil tautan "Kembali" di kiri atas judul); slot: aksi (tombol di kanan) --}}
@props(['title', 'back' => null])

@if ($back)
    <a href="{{ $back }}" class="mb-3 inline-flex sm:mb-5 items-center gap-2 text-base font-semibold text-ink-800 outline-none transition hover:text-gold-700 focus-visible:ring-2 focus-visible:ring-gold-500">
        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 6l-6 6 6 6"/></svg>
        Kembali
    </a>
@endif

<div class="mb-4 flex flex-wrap items-center justify-between gap-2 sm:mb-6 sm:gap-3">
    <h1 class="text-xl font-semibold text-ink sm:text-2xl">{{ $title }}</h1>
    <div class="flex items-center gap-2">{{ $slot }}</div>
</div>
