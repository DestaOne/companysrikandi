{{-- Props: scroll (true = tinggi dibatasi sekitar 6 baris, sisanya digulir di dalam tabel dengan header tetap), reorder (URL PUT penyimpan urutan; bila diisi, baris ber-atribut data-id bisa diseret), slot head (isi <tr> header), slot default (baris <tr>) --}}
@props(['reorder' => null, 'scroll' => false])

<div @if ($reorder) x-data="sortableRows('y')" @endif>
    <div @class(['overflow-x-auto rounded-lg bg-white ring-1 ring-gold-300/60', 'max-h-[40rem] overflow-y-auto overscroll-contain' => $scroll])>
        <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gold-100 text-left text-sm']) }}>
            <thead @class(['bg-gold-50 text-xs uppercase tracking-wide text-ink-800', 'sticky top-0 z-10 shadow-[0_1px_0_var(--color-gold-300)]' => $scroll])>
                {{ $head }}
            </thead>
            <tbody class="divide-y divide-gold-100"
                   @if ($reorder) x-ref="rows" @dragstart="start($event)" @dragover.prevent="over($event)" @drop.prevent @dragend="end()" @endif>
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if ($reorder)
        <x-admin.reorder-form :action="$reorder" />
    @endif
</div>
