<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | {{ config('company.name') }}</title>
    <link rel="icon" href="{{ asset($logos['favicon']) }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gold-50 font-sans text-ink antialiased">
    {{-- Sidebar ink black: hanya ikon di layar kecil, ikon + label di layar lebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 flex w-14 flex-col border-r-4 border-gold-500 bg-linear-to-b from-ink-950 via-ink to-ink-800 px-1.5 py-4 sm:w-20 sm:px-3 sm:py-5 lg:w-64 lg:px-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-3 rounded-xl bg-white p-1.5 sm:p-2 lg:justify-start lg:px-3">
            <img src="{{ asset($logos['navbar_logo']) }}" alt="{{ config('company.name') }}" class="h-6 w-auto sm:h-8">
            <span class="hidden text-xs font-bold leading-tight lg:inline">{{ config('company.name') }}</span>
        </a>

        {{-- Form edit bagian halaman (route home-sections) mengisi @section('menu') agar menu halamannya tetap menyala --}}
        @php($menu = trim($__env->yieldContent('menu')))

        {{-- Menu yang panjang di-scroll sendiri (min-h-0 + overflow) agar blok Keluar di bawah tidak terdorong keluar layar; scrollbar dibuat garis putih tipis tanpa latar; -mr + pr menggesernya ke tepi kanan sidebar tanpa mengubah lebar menu --}}
        <nav class="mt-5 min-h-0 flex-1 space-y-4 overflow-y-auto overscroll-contain scrollbar-hairline -mr-1 pr-1 sm:-mr-2 sm:pr-2 sm:mt-8 sm:space-y-6">
            <x-admin.nav-link :href="route('admin.dashboard')" icon="dashboard" :active="request()->routeIs('admin.dashboard')">Overview</x-admin.nav-link>

            <x-admin.nav-group label="Konten">
                <x-admin.nav-link :href="route('admin.home-sections.index')" icon="home" :active="request()->routeIs('admin.home-sections.index') || $menu === 'home'">Home</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.services.index')" icon="service" :active="request()->routeIs('admin.services.*') || $menu === 'services'">Layanan</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.about.index')" icon="about" :active="request()->routeIs('admin.about.*') || $menu === 'about'">Tentang</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.galleries.index')" icon="gallery" :active="request()->routeIs('admin.galleries.*')">Galeri</x-admin.nav-link>
            </x-admin.nav-group>

            <x-admin.nav-group label="Kontak">
                <x-admin.nav-link :href="route('admin.messages.index')" icon="mail" :badge="$unreadMessageCount" :active="request()->routeIs('admin.messages.*')">Pesan</x-admin.nav-link>
            </x-admin.nav-group>

            <x-admin.nav-group label="Sistem">
                <x-admin.nav-link :href="route('admin.branding.edit')" icon="logo" :active="request()->routeIs('admin.branding.*')">Logo</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.activities.index')" icon="activity" :active="request()->routeIs('admin.activities.*')">Aktivitas</x-admin.nav-link>
            </x-admin.nav-group>
        </nav>

        <div class="mt-3 shrink-0 space-y-2 border-t border-white/10 pt-3">
            {{-- Buka website di tab baru; rel mencegah halaman baru mengakses jendela admin --}}
            <x-admin.nav-link :href="route('home')" icon="site" target="_blank" rel="noopener noreferrer">Lihat Website</x-admin.nav-link>

            <x-admin.nav-link button danger icon="logout" x-data x-on:click="$dispatch('open-modal', 'confirm-logout')">Keluar</x-admin.nav-link>
        </div>
    </aside>

    {{-- Peringatan sebelum keluar --}}
    <x-ui.modal name="confirm-logout" title="Keluar dari panel admin?">
        <p class="text-sm text-ink-800">Anda harus masuk lagi untuk mengelola konten website.</p>

        <form method="POST" action="{{ route('logout') }}" class="mt-6 flex justify-end gap-3">
            @csrf
            <x-ui.button variant="secondary" x-on:click="show = false">Batal</x-ui.button>
            <x-ui.button variant="danger" type="submit">Keluar</x-ui.button>
        </form>
    </x-ui.modal>

    <div class="pl-14 sm:pl-20 lg:pl-64">
        <header class="sticky top-0 z-20 flex h-14 items-center justify-between gap-2 border-b border-gold-300/70 bg-white/90 px-3 backdrop-blur sm:h-16 sm:px-8">
            <p class="truncate text-xs font-semibold uppercase tracking-widest text-gold-700 sm:text-sm">Panel Admin</p>

            <div class="flex items-center gap-2 sm:gap-4">
                <x-admin.notification-bell :count="$newActivityCount" :activities="$newActivities" :href="route('admin.activities.index')" :message-count="$unreadMessageCount" :messages="$unreadMessages" :messages-href="route('admin.messages.index')" />

                <div class="flex items-center gap-3">
                    <span class="bg-gold-sheen flex size-8 shrink-0 sm:size-9 items-center justify-center rounded-full text-sm font-bold">{{ auth()->user()->initial }}</span>
                    <div class="hidden leading-tight sm:block">
                        <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-ink-800/70">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-3 py-5 sm:px-8 sm:py-8">
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
