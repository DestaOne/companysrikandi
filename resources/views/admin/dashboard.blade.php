@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Banner sambutan: panel hitam dengan aksen emas, seperti header proyek pada referensi --}}
    <section class="relative mb-5 overflow-hidden rounded-2xl border-2 border-gold-400 bg-linear-to-br from-ink-950 via-ink to-ink-800 px-4 py-6 text-white shadow-lg sm:mb-8 sm:rounded-3xl sm:px-12 sm:py-10">
        <x-web.line-backdrop class="opacity-30" />

        <div class="relative">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-300">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            <h1 class="mt-2 font-serif text-2xl leading-tight sm:mt-3 sm:text-5xl">Selamat datang, <span class="bg-gold-metal bg-clip-text text-transparent">{{ auth()->user()->name }}</span></h1>
            <p class="mt-3 max-w-xl text-sm leading-6 sm:mt-4 sm:text-base sm:leading-7 text-white">Kelola konten website {{ config('company.name') }} dari satu tempat.</p>
            <div class="mt-4 flex flex-wrap gap-2 sm:mt-6 sm:gap-3">
                <x-ui.button :href="route('admin.services.create')">Tambah Layanan</x-ui.button>
                <x-ui.button :href="route('admin.galleries.create')">Tambah Galeri</x-ui.button>
            </div>
        </div>
    </section>

    <div class="grid gap-3 sm:grid-cols-3 sm:gap-4">
        <x-admin.stat-card label="Total Layanan" :value="$counts['services']" hint="Bidang usaha yang ditampilkan">
            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="Total Gambar" :value="$counts['galleries']" hint="Foto di halaman galeri">
            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.8"/><path d="m21 16-5-5-9 9"/></svg>
        </x-admin.stat-card>
        <x-admin.stat-card label="Pesan Belum Terbaca" :value="$counts['unread_messages']" hint="Dari form kontak">
            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
        </x-admin.stat-card>
    </div>

    <div class="mt-5 grid gap-4 sm:mt-8 sm:gap-6 lg:grid-cols-5">
        <section class="rounded-2xl bg-white p-4 shadow-sm sm:p-6 ring-1 ring-gold-300/60 lg:col-span-3">
            <h2 class="text-lg font-semibold">Pesan Terbaru</h2>
            <span class="bg-gold-metal mt-2 block h-0.5 w-12"></span>

            {{-- Tinggi dibatasi sekitar 6 baris; sisanya digulir di dalam kartu --}}
            <ul class="mt-4 max-h-[26rem] divide-y divide-gold-100 overflow-y-auto overscroll-contain">
                @forelse ($messages as $message)
                    <li>
                        <a href="{{ route('admin.messages.show', $message) }}" class="block py-3 outline-none hover:text-gold-700 focus-visible:ring-2 focus-visible:ring-gold-500">
                            <div class="flex items-baseline justify-between gap-3">
                                <p class="truncate font-medium">{{ $message->name }} <span class="text-sm font-normal text-ink-800/70">&middot; {{ $message->email }}</span></p>
                                <time class="shrink-0 text-xs text-ink-800/70" datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->locale('id')->diffForHumans() }}</time>
                            </div>
                            <p class="mt-1 truncate text-sm text-ink-800">{{ $message->subject }}</p>
                        </a>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-ink-800">Belum ada pesan masuk.</li>
                @endforelse
            </ul>

            <a href="{{ route('admin.messages.index') }}" class="mt-4 inline-block text-sm font-semibold text-ink outline-none transition hover:text-gold-700 focus-visible:ring-2 focus-visible:ring-gold-500">Buka semua pesan</a>
        </section>

        <section class="rounded-2xl bg-white p-4 shadow-sm sm:p-6 ring-1 ring-gold-300/60 lg:col-span-2">
            <h2 class="text-lg font-semibold">Layanan Belum Terbit</h2>
            <span class="bg-gold-metal mt-2 block h-0.5 w-12"></span>

            <ul class="mt-4 max-h-[26rem] divide-y divide-gold-100 overflow-y-auto overscroll-contain">
                @forelse ($draftServices as $service)
                    <li>
                        <a href="{{ route('admin.services.edit', $service) }}" class="group block py-3 outline-none focus-visible:ring-2 focus-visible:ring-gold-500">
                            <p class="wrap-anywhere font-medium transition group-hover:text-gold-700">{{ $service->title }}</p>
                            <p class="mt-0.5 text-xs text-ink-800/70">Diubah <time datetime="{{ $service->updated_at->toIso8601String() }}">{{ $service->updated_at->locale('id')->diffForHumans() }}</time></p>
                        </a>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-ink-800">Semua layanan sudah terbit.</li>
                @endforelse
            </ul>

            <a href="{{ route('admin.services.index') }}" class="mt-4 inline-block text-sm font-semibold text-ink outline-none transition hover:text-gold-700 focus-visible:ring-2 focus-visible:ring-gold-500">Buka daftar layanan</a>
        </section>
    </div>
@endsection
