{{-- Props: action (URL PUT penyimpan urutan). Dipakai di dalam komponen ber-x-data="sortableRows": menyediakan x-ref "form" dan "ids".
     Form terpisah dari daftar karena item bisa berisi form lain, mis. konfirmasi hapus. Form dikirim ke iframe tersembunyi
     (bukan fetch) agar halaman tidak dimuat ulang dan posisi gulir tetap. --}}
@props(['action'])

<form x-ref="form" method="POST" action="{{ $action }}" target="reorder-frame" class="hidden">
    @csrf
    @method('PUT')
    <div x-ref="ids"></div>
</form>
<iframe name="reorder-frame" title="Penyimpanan urutan" class="hidden" x-on:load="saved($event.target)"></iframe>
