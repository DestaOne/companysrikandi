{{-- Props: reorder (URL PUT penyimpan urutan); slot: petak ber-atribut data-id (mis. x-admin.gallery-tile). Petak diseret untuk berpindah posisi; ukuran petak mengikuti posisi. --}}
@props(['reorder'])

<div x-data="sortableRows('x')">
    <x-web.gallery-grid x-ref="rows" @dragstart="start($event)" @dragover.prevent="over($event)" @drop.prevent @dragend="end()" {{ $attributes }}>
        {{ $slot }}
    </x-web.gallery-grid>

    <x-admin.reorder-form :action="$reorder" />
</div>
