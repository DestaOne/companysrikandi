@extends('layouts.app')

@section('title', $section->label())
@section('menu', $section->page())

@section('content')
    <x-admin.page-header :title="$section->label()" :back="route($section->indexRoute())" />

    {{-- Judul bagian: ringkasan teks + tombol ubah --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4 border border-gold-300/70 bg-white px-6 py-4 shadow-sm">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-gold-700">{{ $content['eyebrow'] }}</p>
            <p class="mt-1 text-lg font-semibold text-ink">{{ $content['title'] }}</p>
        </div>
        <x-ui.button variant="dark" :href="route('admin.home-sections.edit', [$section, 'judul' => 1])">Ubah Judul Bagian</x-ui.button>
    </div>

    <p class="mb-4 text-sm text-ink-800">Pilih sebuah kartu untuk mengubah judul, deskripsi, dan gambarnya.</p>

    <div class="grid items-start gap-4 sm:grid-cols-2">
        @foreach ($content['items'] as $index => $item)
            <a href="{{ route('admin.home-sections.items.edit', [$section, $index]) }}"
               class="group block overflow-hidden border border-gold-300/70 bg-white shadow-sm outline-none transition hover:border-gold-500 hover:shadow-md focus-visible:ring-2 focus-visible:ring-gold-500">
                <div class="relative aspect-[8/5] overflow-hidden bg-ink">
                    <img src="{{ asset($item['image']) }}" alt="" class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-linear-to-t from-ink/90 via-ink/30 to-transparent"></div>
                    <span class="absolute left-3 top-3 bg-ink px-2.5 py-1 text-xs font-bold uppercase tracking-widest text-gold-300">Kartu {{ $index + 1 }}</span>
                    <h3 class="absolute inset-x-0 bottom-0 p-4 text-lg font-semibold leading-snug text-white">{{ $item['title'] }}</h3>
                </div>
                <div class="flex items-start justify-between gap-4 p-4">
                    <p class="line-clamp-3 text-sm leading-6 text-ink-800">{{ $item['description'] }}</p>
                    <span class="shrink-0 bg-ink px-4 py-1.5 text-sm font-semibold text-white transition group-hover:bg-ink-800">Ubah</span>
                </div>
            </a>
        @endforeach
    </div>
@endsection
