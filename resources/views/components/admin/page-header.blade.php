{{-- Props: title; slot: aksi (tombol di kanan) --}}
@props(['title'])

<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-semibold text-ink">{{ $title }}</h1>
    <div class="flex items-center gap-2">{{ $slot }}</div>
</div>
