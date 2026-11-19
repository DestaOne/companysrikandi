{{-- Props: action (URL PUT penyimpan urutan). Dipakai di dalam komponen ber-x-data="sortableRows": menyediakan x-ref "form" dan "ids".
     Form terpisah dari daftar karena item bisa berisi form lain, mis. konfirmasi hapus. --}}
@props(['action'])

<form x-ref="form" method="POST" action="{{ $action }}" class="hidden">
    @csrf
    @method('PUT')
    <div x-ref="ids"></div>
</form>
