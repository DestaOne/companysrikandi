<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. Dwi Srikandi Nusantara')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-white font-sans text-ink antialiased selection:bg-gold-300 selection:text-ink">

    <x-web.navbar />

    @if (session('success') || session('error'))
        <div class="fixed inset-x-0 top-20 z-40 mx-auto max-w-xl px-4"><x-ui.flash-message /></div>
    @endif

    @yield('content')

    <x-web.footer />

    <x-ui.image-viewer />

</body>
</html>
