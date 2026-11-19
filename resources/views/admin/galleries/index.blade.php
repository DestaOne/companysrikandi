@extends('layouts.app')

@section('title', 'Galeri')

@section('content')
    <x-admin.page-header title="Galeri">
        <x-ui.button variant="secondary" :href="route('galleries.index')" target="_blank" rel="noopener noreferrer">Lihat Halaman</x-ui.button>
        <x-ui.button :href="route('admin.galleries.create')">Tambah Gambar</x-ui.button>
    </x-admin.page-header>

    <p class="-mt-3 mb-6 text-sm text-ink-800">Seret gambar untuk memindahkan posisinya. Urutan dan ukuran petak sama seperti di halaman Galeri website.</p>

    @if ($galleries->isEmpty())
        <p class="bg-white px-4 py-10 text-center text-ink-800 ring-1 ring-gold-300/60">Belum ada gambar. Klik Tambah Gambar untuk memulai.</p>
    @else
        <x-admin.sortable-grid :reorder="route('admin.galleries.reorder')">
            @foreach ($galleries as $gallery)
                <x-admin.gallery-tile
                    :id="$gallery->id"
                    :image="asset('storage/'.$gallery->image)"
                    :title="$gallery->title"
                    :edit="route('admin.galleries.edit', $gallery)"
                    :delete="route('admin.galleries.destroy', $gallery)" />
            @endforeach
        </x-admin.sortable-grid>

    @endif
@endsection
