{{-- Props: action (URL DELETE), name (nama unik modal), message --}}
@props(['action', 'name', 'message' => 'Data yang dihapus tidak bisa dikembalikan.'])

<button type="button" x-data x-on:click="$dispatch('open-modal', @js($name))"
        class="text-sm font-medium text-red-600 hover:underline">
    {{ $slot->isEmpty() ? 'Hapus' : $slot }}
</button>

<x-ui.modal :name="$name" title="Hapus data?">
    <p class="text-sm text-ink-800">{{ $message }}</p>

    <form method="POST" action="{{ $action }}" class="mt-6 flex justify-end gap-3">
        @csrf
        @method('DELETE')
        <x-ui.button variant="secondary" x-on:click="$dispatch('close-modal', @js($name))">Batal</x-ui.button>
        <x-ui.button variant="danger" type="submit">Hapus</x-ui.button>
    </form>
</x-ui.modal>
