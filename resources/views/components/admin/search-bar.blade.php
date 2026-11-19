{{-- Props: action (URL halaman daftar), value (kata kunci saat ini), placeholder; kolom pencarian bernama "q" dikirim lewat GET saat Enter (mencari di semua halaman); daftar di halaman disaring langsung saat mengetik (Alpine.data liveSearch) --}}
@props(['action', 'value' => '', 'placeholder' => 'Cari...'])

<form method="GET" action="{{ $action }}" role="search" x-data="liveSearch" {{ $attributes->class('w-full max-w-md') }}>
    <div class="relative">
        <label for="q" class="sr-only">{{ $placeholder }}</label>

        <svg class="pointer-events-none absolute left-3.5 top-1/2 size-5 -translate-y-1/2 text-gold-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>

        <input type="text" id="q" name="q" value="{{ $value }}" placeholder="{{ $placeholder }}" maxlength="100"
               x-ref="input" x-on:input="query = $event.target.value; filter()" autocomplete="off" enterkeyhint="search"
               class="block w-full rounded-lg border-0 bg-white py-2.5 pl-11 sm:py-3 sm:pl-12 pr-11 text-sm text-ink shadow-sm outline-none ring-1 ring-gold-300 transition placeholder:text-ink-800/50 hover:ring-gold-500/70 focus:ring-2 focus:ring-gold-500">

        <button type="button" x-show="query !== ''" x-cloak x-on:click="clear()" aria-label="Hapus kata kunci"
                class="absolute right-2.5 top-1/2 flex size-7 -translate-y-1/2 items-center justify-center rounded-full text-ink-800 transition hover:bg-gold-100 hover:text-ink">
            <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
    </div>

    <p x-show="query.trim() !== ''" x-cloak class="mt-2 text-xs text-ink-800">Menyaring halaman ini. Tekan Enter untuk mencari di semua halaman.</p>
</form>
