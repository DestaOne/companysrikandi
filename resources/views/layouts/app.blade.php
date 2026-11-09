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
    <header class="border-b border-gold-300/70 bg-white">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <div class="flex items-center gap-8">
                <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/logo.png') }}" alt="{{ config('company.name') }}" class="h-9 w-auto"></a>
                <nav class="flex gap-5 text-sm font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gold-700">Dashboard</a>
                    <a href="{{ route('admin.galleries.index') }}" class="hover:text-gold-700">Galeri</a>
                </nav>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-ui.button variant="secondary" type="submit">Keluar</x-ui.button>
            </form>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
        <x-ui.flash-message />

        @yield('content')
    </main>
</body>
</html>
