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
<body class="flex min-h-screen items-center justify-center bg-gold-50 px-4 font-sans text-ink antialiased">
    <div class="w-full max-w-sm">
        <a href="{{ route('home') }}" class="mb-6 block"><img src="{{ asset('images/logo.png') }}" alt="{{ config('company.name') }}" class="mx-auto h-14 w-auto"></a>

        <div class="rounded-lg bg-white p-6 shadow ring-1 ring-gold-300/60">
            <x-ui.flash-message />

            @yield('content')
        </div>
    </div>
</body>
</html>
