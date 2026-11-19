{{-- Props: id, image (URL), title, edit (URL halaman ubah: nama, deskripsi, gambar), delete (URL DELETE). Petak galeri yang bisa diseret; ukurannya diatur oleh x-web.gallery-grid. --}}
@props(['id', 'image', 'title', 'edit', 'delete'])

<figure data-id="{{ $id }}" {{ $attributes->class('relative cursor-grab overflow-hidden bg-ink') }}>
    <img src="{{ $image }}" alt="{{ $title }}" draggable="false" loading="lazy" class="absolute inset-0 size-full object-cover">
    <div class="absolute inset-0 bg-linear-to-t from-ink/85 via-ink/10 to-transparent"></div>

    <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 p-3 text-white sm:p-4">
        <p class="min-w-0 text-sm font-semibold leading-snug [text-shadow:0_1px_8px_rgb(0_0_0/0.6)] sm:text-base">{{ $title }}</p>

        {{-- Tombol selalu tampak (tanpa efek hover); persegi dan tegas seperti tombol tabel --}}
        <div class="flex shrink-0 gap-1.5">
            <a href="{{ $edit }}" class="bg-white px-2.5 py-1 text-xs font-semibold text-ink">Ubah</a>
            <x-ui.confirm-delete :action="$delete" :name="'delete-gallery-'.$id" solid small>Hapus</x-ui.confirm-delete>
        </div>
    </div>
</figure>
