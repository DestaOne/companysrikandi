{{-- Slot: petak galeri (anak langsung). Ukuran petak mengikuti urutan: pola 7 petak mengisi 3 baris x 4 kolom tanpa celah (besar, tinggi, lebar, biasa).
     Lewat nth-child, jadi ukurannya ikut berubah otomatis saat urutan petak digeser. Atribut tambahan diteruskan ke grid. --}}
<div {{ $attributes->class([
    'grid auto-rows-[9rem] grid-flow-dense grid-cols-2 gap-3 sm:auto-rows-[12rem] sm:gap-4 lg:grid-cols-4',
    '[&>:nth-child(7n+1)]:col-span-2 [&>:nth-child(7n+1)]:row-span-2',
    '[&>:nth-child(7n+4)]:row-span-2',
    '[&>:nth-child(7n+6)]:col-span-2',
]) }}>
    {{ $slot }}
</div>
