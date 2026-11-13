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
<body class="relative flex min-h-svh flex-col overflow-hidden bg-white px-4 py-6 font-sans text-ink antialiased sm:px-8">
    {{-- Hiasan latar halaman: panel emas transparan di kanan dan garis diagonal tipis --}}
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <span class="absolute right-0 top-0 h-[78%] w-[42%] rounded-bl-[6rem] bg-linear-to-b from-gold-300/50 to-gold-500/30"></span>
        <span class="absolute -inset-y-10 left-[62%] w-px rotate-[9deg] bg-gold-500/40"></span>
        <span class="absolute -inset-y-10 left-[86%] w-px rotate-[9deg] bg-gold-500/30"></span>
    </div>

    <a href="{{ route('home') }}" class="relative flex items-center gap-3 text-sm font-bold tracking-tight">
        <img src="{{ asset('images/logo.png') }}" alt="" class="h-8 w-auto">
        {{ config('company.name') }}
    </a>

    <div class="relative flex flex-1 items-center justify-center py-10">
        {{-- Kartu putih: panel hitam berisi teks sambutan di kiri (@yield aside, hanya layar lebar), form di kanan (@yield content) --}}
        <main class="grid w-full max-w-5xl overflow-hidden rounded-3xl border-2 border-gold-400 bg-white shadow-2xl md:min-h-[36rem] md:grid-cols-2">
            <aside class="relative hidden flex-col overflow-hidden border-r-4 border-gold-500 bg-linear-to-br from-ink-950 via-ink to-ink-800 px-14 pb-14 pt-16 md:flex md:pt-20">
                <x-web.line-backdrop class="opacity-40" />

                <div class="relative">
                    @yield('aside')
                </div>
            </aside>

            <div class="flex flex-col px-6 pb-8 pt-16 sm:px-12 md:pt-20">
                <div class="flex flex-1 flex-col justify-center">
                    <x-ui.flash-message />

                    @yield('content')
                </div>

                <a href="{{ route('home') }}" class="mt-10 block border-t border-gold-300 pt-6 text-center text-base font-semibold text-ink hover:text-gold-700">&larr; Kembali ke website</a>
            </div>
        </main>
    </div>
</body>
</html>
