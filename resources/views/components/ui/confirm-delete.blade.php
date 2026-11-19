{{-- Props: action (URL DELETE), name (nama unik modal), message, solid (true = tombol merah penuh tanpa sudut membulat; default tautan teks), small (tombol solid lebih kecil, untuk ruang sempit) --}}
@props(['action', 'name', 'message' => 'Data yang dihapus tidak bisa dikembalikan.', 'solid' => false, 'small' => false])

<button type="button" x-data x-on:click="$dispatch('open-modal', @js($name))"
        @class([
            'text-sm font-medium text-red-600 hover:underline' => ! $solid,
            'bg-red-600 font-semibold text-white transition hover:bg-red-700' => $solid,
            'px-4 py-1.5 text-sm' => $solid && ! $small,
            'px-2.5 py-1 text-xs' => $solid && $small,
        ])>
    {{ $slot->isEmpty() ? 'Hapus' : $slot }}
</button>

<x-ui.modal :name="$name" title="Hapus data?">
    <p class="text-sm text-ink-800">{{ $message }}</p>

    <form method="POST" action="{{ $action }}" class="mt-6 flex justify-end gap-3">
        @csrf
        @method('DELETE')
        {{-- Tombol berada di dalam x-data modal, jadi cukup menutup lewat "show" (direktif @js tidak diproses di atribut tag komponen) --}}
        <x-ui.button variant="secondary" x-on:click="show = false">Batal</x-ui.button>
        <x-ui.button variant="danger" type="submit">Hapus</x-ui.button>
    </form>
</x-ui.modal>
