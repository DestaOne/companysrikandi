<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | {{ config('company.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gold-50 font-sans text-ink antialiased">
    {{-- Sidebar ink black: hanya ikon di layar kecil, ikon + label di layar lebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 flex w-20 flex-col border-r-4 border-gold-500 bg-linear-to-b from-ink-950 via-ink to-ink-800 px-3 py-5 lg:w-64 lg:px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-3 rounded-xl bg-white p-2 lg:justify-start lg:px-3">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('company.name') }}" class="h-8 w-auto">
            <span class="hidden text-xs font-bold leading-tight lg:inline">{{ config('company.name') }}</span>
        </a>

        <nav class="mt-8 space-y-2">
            <x-admin.nav-link :href="route('admin.dashboard')" icon="dashboard" :active="request()->routeIs('admin.dashboard')">Overview</x-admin.nav-link>
            <x-admin.nav-link :href="route('admin.galleries.index')" icon="gallery" :active="request()->routeIs('admin.galleries.*')">Galeri</x-admin.nav-link>
        </nav>

        <div class="mt-auto border-t border-white/10 pt-4">
            <x-admin.nav-link :href="route('home')" icon="site">Lihat Website</x-admin.nav-link>
        </div>
    </aside>

    <div class="pl-20 lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-gold-300/70 bg-white/90 px-4 backdrop-blur sm:px-8">
            <p class="text-sm font-semibold uppercase tracking-widest text-gold-700">Panel Admin</p>

            <div class="flex items-center gap-4">
                {{-- Buka website publik di tab baru; rel mencegah halaman baru mengakses jendela admin --}}
                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-sm font-semibold text-ink hover:text-gold-700">
                    Halaman Utama
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5"/></svg>
                </a>

                <div class="flex items-center gap-3">
                    <span class="bg-gold-sheen flex size-9 items-center justify-center rounded-full text-sm font-bold">{{ auth()->user()->initial }}</span>
                    <div class="hidden leading-tight sm:block">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-ink-800/70">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    {{-- Hitam ink dengan teks putih; hover maroon --}}
                    <x-ui.button type="submit" variant="dark">Keluar</x-ui.button>
                </form>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-8">
            <x-ui.flash-message />

            @yield('content')
        </main>
    </div>
    {{-- Admin yang memilih "Ingat saya" tidak kena logout otomatis --}}
    @unless (session('remember_me') || auth()->viaRemember())
        <x-admin.idle-logout :seconds="config('auth.idle_timeout')" />
    @endunless
</body>
</html>
