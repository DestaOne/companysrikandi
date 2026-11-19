{{-- Props: href (halaman edit), label (nama bagian), compact (true = diperkecil, untuk bagian yang tingginya setinggi layar seperti hero); di layar kecil semua pratinjau otomatis diperkecil; slot: isi pratinjau --}}
@props(['href', 'label', 'compact' => false])

{{-- Pratinjau statis tanpa efek hover: isinya inert dan tanpa animasi, satu tautan transparan menutupi seluruh bagian agar bisa diklik untuk diubah --}}
<div {{ $attributes->class('relative') }}>
    <div inert aria-hidden="true" @class(['h-full', 'no-motion pointer-events-none select-none', '[zoom:0.5] max-sm:[zoom:0.4]' => $compact, 'max-sm:[zoom:0.6]' => ! $compact])>
        {{ $slot }}
    </div>

    <a href="{{ $href }}" aria-label="Ubah {{ $label }}" class="absolute inset-0 z-30 outline-none ring-inset focus-visible:ring-4 focus-visible:ring-gold-500"></a>
</div>
