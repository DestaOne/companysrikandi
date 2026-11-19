@extends('layouts.app')

@section('title', 'Layanan')

@section('content')
    <x-admin.page-header title="Layanan">
        <x-ui.button variant="secondary" :href="route('services')" target="_blank" rel="noopener noreferrer">Lihat Halaman</x-ui.button>
    </x-admin.page-header>

    <p class="-mt-3 mb-6 text-sm text-ink-800">Klik hero atau ajakan untuk mengubahnya. Daftar layanan dikelola lewat tabel.</p>

    <x-admin.section-preview :href="route('admin.home-sections.edit', \App\Enums\HomeSectionKey::ServiceBanner)" label="Hero Section" compact class="overflow-hidden">
        <x-web.hero-banner split :title="$banner['title']" :description="$banner['description']" :image="$banner['image']" />
    </x-admin.section-preview>

    <div class="mb-4 mt-10">
        <h2 class="text-lg font-semibold">Daftar Layanan</h2>
        <p class="text-sm text-ink-800">Seret baris untuk mengubah urutan. Urutan yang sama dipakai di website.</p>
    </div>

    {{-- Pencarian di kiri, tombol tambah di kanan, sejajar di atas tabel --}}
    <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
        <div class="flex min-w-0 flex-1 flex-wrap items-center gap-3">
            <x-admin.search-bar :action="route('admin.services.index')" :value="$search" placeholder="Cari judul atau deskripsi layanan" />
            @if ($search !== '')
                <a href="{{ route('admin.services.index') }}" class="text-sm font-semibold text-gold-700 hover:underline">Hapus pencarian</a>
            @endif
        </div>

        <x-ui.button size="lg" :href="route('admin.services.create')">Tambah Layanan</x-ui.button>
    </div>

    <x-admin.table scroll :reorder="route('admin.services.reorder')">
        <x-slot:head>
            <tr>
                <th class="w-16 px-4 py-3 text-center">No</th>
                <th class="w-32 px-4 py-3">Gambar</th>
                <th class="px-4 py-3">Layanan</th>
                <th class="px-4 py-3">Deskripsi</th>
                <th class="w-28 px-4 py-3 text-center">Status</th>
                <th class="w-40 px-4 py-3 text-right">Aksi</th>
            </tr>
        </x-slot:head>

        @forelse ($services as $service)
            <tr data-id="{{ $service->id }}" data-search="{{ Str::lower($service->title.' '.$service->description.' '.$service->slug) }}" class="align-middle transition hover:bg-gold-50/60">
                <td class="cursor-grab px-4 py-4 text-center font-semibold text-ink-800" title="Seret untuk mengubah urutan">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="size-4 text-gold-700" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="9" cy="6" r="1.6"/><circle cx="15" cy="6" r="1.6"/><circle cx="9" cy="12" r="1.6"/><circle cx="15" cy="12" r="1.6"/><circle cx="9" cy="18" r="1.6"/><circle cx="15" cy="18" r="1.6"/></svg>
                        {{ $loop->iteration }}
                    </span>
                </td>
                <td class="px-4 py-4"><img src="{{ asset($service->image) }}" alt="{{ $service->title }}" class="h-16 w-24 rounded-md object-cover ring-1 ring-gold-300/60"></td>
                <td class="max-w-xs px-4 py-4">
                    <p class="font-semibold leading-snug text-ink">{{ $service->title }}</p>
                    <a href="{{ route('services.show', $service->slug) }}" target="_blank" rel="noopener noreferrer" class="mt-1 inline-block text-xs text-gold-700 hover:underline">/layanan/{{ $service->slug }}</a>
                </td>
                <td class="max-w-sm px-4 py-4 leading-6 text-ink-800">{{ Str::limit($service->description, 110) }}</td>
                <td class="px-4 py-4 text-center"><x-ui.badge :variant="$service->status === \App\Enums\ServiceStatus::Published ? 'success' : 'default'">{{ $service->status->label() }}</x-ui.badge></td>
                <td class="px-4 py-4">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.services.edit', $service) }}" class="bg-ink px-4 py-1.5 text-sm font-semibold text-white transition hover:bg-ink-800">Ubah</a>
                        <x-ui.confirm-delete :action="route('admin.services.destroy', $service)" :name="'delete-service-'.$service->id" solid />
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="px-4 py-10 text-center text-ink-800">{{ $search !== '' ? 'Tidak ada layanan yang cocok dengan “'.$search.'”.' : 'Belum ada layanan.' }}</td></tr>
        @endforelse

        <tr data-search-empty hidden><td colspan="6" class="px-4 py-10 text-center text-ink-800">Tidak ada layanan yang cocok. Tekan Enter untuk mencari lebih lanjut.</td></tr>
    </x-admin.table>

    <x-admin.section-preview :href="route('admin.home-sections.edit', \App\Enums\HomeSectionKey::ServiceCta)" label="Ajakan Menghubungi" class="mt-10 overflow-hidden">
        <x-web.cta-banner :title="$cta['title']" :description="$cta['description']" :image="$cta['image']" href="#" :label="$cta['label']" />
    </x-admin.section-preview>
@endsection
