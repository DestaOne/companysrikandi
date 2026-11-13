@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Banner sambutan: panel hitam dengan aksen emas, seperti header proyek pada referensi --}}
    <section class="relative mb-8 overflow-hidden rounded-3xl border-2 border-gold-400 bg-linear-to-br from-ink-950 via-ink to-ink-800 px-8 py-10 text-white shadow-lg sm:px-12">
        <x-web.line-backdrop class="opacity-30" />

        <div class="relative">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-300">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            <h1 class="mt-3 font-serif text-4xl leading-tight sm:text-5xl">Selamat datang, <span class="bg-gold-metal bg-clip-text text-transparent">{{ auth()->user()->name }}</span></h1>
            <p class="mt-4 max-w-xl text-base leading-7 text-white">Kelola konten website {{ config('company.name') }} dari satu tempat.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                {{-- Modul admin Layanan belum ada, jadi tombolnya nonaktif dulu --}}
                <x-ui.button disabled title="Segera hadir" class="cursor-not-allowed">Tambah Layanan</x-ui.button>
                <x-ui.button :href="route('admin.galleries.create')">Tambah Galeri</x-ui.button>
            </div>
        </div>
    </section>

    <div class="grid gap-4 sm:grid-cols-3">
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

    <div class="mt-8 grid gap-6 lg:grid-cols-5">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gold-300/60 lg:col-span-3">
            <h2 class="text-lg font-semibold">Pesan Terbaru</h2>
            <span class="bg-gold-metal mt-2 block h-0.5 w-12"></span>

            <ul class="mt-4 divide-y divide-gold-100">
                @forelse ($messages as $message)
                    <li class="py-3">
                        <div class="flex items-baseline justify-between gap-3">
                            <p class="truncate font-medium">{{ $message->name }} <span class="text-sm font-normal text-ink-800/70">&middot; {{ $message->email }}</span></p>
                            <time class="shrink-0 text-xs text-ink-800/70" datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->locale('id')->diffForHumans() }}</time>
                        </div>
                        <p class="mt-1 truncate text-sm text-ink-800">{{ $message->subject }}</p>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-ink-800">Belum ada pesan masuk.</li>
                @endforelse
            </ul>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gold-300/60 lg:col-span-2">
            <h2 class="text-lg font-semibold">Aktivitas Terakhir</h2>
            <span class="bg-gold-metal mt-2 block h-0.5 w-12"></span>

            <ul class="mt-4 space-y-4">
                @forelse ($activities as $activity)
                    <li class="flex gap-3">
                        <span class="bg-gold-sheen mt-1.5 size-2.5 shrink-0 rounded-full"></span>
                        <div class="min-w-0 text-sm">
                            <p>
                                <span class="font-medium">{{ $activity->user?->name ?? 'Pengunjung' }}</span>
                                {{ $activity->summary }}
                            </p>
                            <time class="text-xs text-ink-800/70" datetime="{{ $activity->created_at->toIso8601String() }}">{{ $activity->created_at->locale('id')->diffForHumans() }}</time>
                        </div>
                    </li>
                @empty
                    <li class="py-8 text-center text-sm text-ink-800">Belum ada aktivitas.</li>
                @endforelse
            </ul>
        </section>
    </div>
@endsection
