@extends('layouts.app')

@section('title', 'Galeri')

@section('content')
    <x-admin.page-header title="Galeri">
        <x-ui.button :href="route('admin.galleries.create')">Tambah Galeri</x-ui.button>
    </x-admin.page-header>

    <x-admin.table>
        <x-slot:head>
            <tr>
                <th class="px-4 py-3">Gambar</th>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </x-slot:head>

        @forelse ($galleries as $gallery)
            <tr>
                <td class="px-4 py-3"><img src="{{ asset('storage/'.$gallery->image) }}" alt="" class="h-12 w-20 rounded object-cover"></td>
                <td class="px-4 py-3 font-medium">{{ $gallery->title }}</td>
                <td class="flex justify-end gap-4 px-4 py-3">
                    <a href="{{ route('admin.galleries.edit', $gallery) }}" class="text-sm font-medium text-gold-700 hover:underline">Ubah</a>
                    <x-ui.confirm-delete :action="route('admin.galleries.destroy', $gallery)" :name="'delete-gallery-'.$gallery->id" />
                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="px-4 py-8 text-center text-ink-800">Belum ada galeri.</td></tr>
        @endforelse
    </x-admin.table>

    <div class="mt-4">{{ $galleries->links() }}</div>
@endsection
