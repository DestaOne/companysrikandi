<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PT. Dwi Srikandi Nusantara')</title>
    <link rel="icon" href="{{ asset($logos['favicon']) }}">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-white font-sans text-ink antialiased selection:bg-gold-300 selection:text-ink">

    <x-web.navbar />

    <x-ui.flash-message />

    @yield('content')

    <x-web.footer />

    <x-ui.image-viewer />

</body>
</html>
