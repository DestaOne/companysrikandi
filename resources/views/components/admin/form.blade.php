{{-- Props: action (wajib), method (POST|PUT), cancel (URL tombol Batal), submit (teks tombol simpan); slot: isi kolom form --}}
@props(['action', 'method' => 'POST', 'cancel', 'submit' => 'Simpan'])

{{-- Panel form standar admin: kolom di atas, tombol aksi di bilah bawah. enctype multipart selalu aktif agar bisa dipakai form dengan upload. --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data"
      {{ $attributes->class('max-w-3xl border border-gold-300/70 border-t-4 border-t-gold-500 bg-white shadow-sm') }}>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-4 p-4 sm:space-y-6 sm:p-8">
        {{ $slot }}
    </div>

    <div class="flex flex-wrap items-center justify-end gap-2 border-t border-gold-300/70 bg-gold-50/60 px-4 py-3 sm:gap-3 sm:px-8 sm:py-4">
        <x-ui.button variant="secondary" :href="$cancel">Batal</x-ui.button>
        <x-ui.button type="submit">{{ $submit }}</x-ui.button>
    </div>
</form>
