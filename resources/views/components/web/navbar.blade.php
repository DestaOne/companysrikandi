{{-- Navbar tetap; perilakunya (transparan, solid, menu mobile) ada di Alpine.data('navbar') di resources/js/navbar.js. Tanpa props. --}}
@php
    $company = config('company');

    $links = [
        ['label' => 'Beranda', 'href' => route('home'), 'active' => request()->routeIs('home'), 'mega' => false],
        ['label' => 'Layanan', 'href' => url('/layanan'), 'active' => request()->is('layanan*'), 'mega' => true],
        ['label' => 'Tentang', 'href' => url('/tentang-kami'), 'active' => request()->is('tentang-kami*'), 'mega' => false],
        ['label' => 'Galeri', 'href' => route('galleries.index'), 'active' => request()->is('galeri*'), 'mega' => false],
    ];

    $contactUrl = url('/tentang-kami#kontak');

    $focus = 'outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-gold-500';
    $lineBase = 'pointer-events-none absolute h-0.5 origin-left transition-transform duration-300 ease-out';
@endphp

<header x-data="navbar" :data-solid="solid" data-solid="false" @scroll.window.passive="sync()" @resize.window="closeOnDesktop()" @keydown.escape.window="close()" class="group/nav solid:border-gold-300/70 solid:bg-white solid:text-ink fixed inset-x-0 top-0 z-50 border-b border-transparent text-white transition-colors duration-300">

    {{-- Bar utama --}}
    <div>
        <div class="mx-auto grid h-14 max-w-7xl grid-cols-[1fr_auto_1fr] items-center px-4 sm:px-6 md:flex md:h-16 lg:px-8">

            {{-- Tombol menu (mobile) --}}
            <button type="button" @click="toggle()" :aria-expanded="open" :aria-label="open ? 'Tutup menu' : 'Buka menu'" aria-controls="mobile-menu" aria-expanded="false" aria-label="Buka menu"
                    class="group/toggle -ml-2 flex size-11 items-center justify-center justify-self-start {{ $focus }} md:hidden">
                <svg class="size-6 group-aria-expanded/toggle:hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                <svg class="hidden size-6 group-aria-expanded/toggle:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>

            {{-- Logo --}}
            <a href="{{ route('home') }}" aria-label="{{ $company['name'] }}, ke beranda" class="flex items-center {{ $focus }}">
                {{-- Logo teks putih di atas hero (navbar transparan), logo asli saat navbar solid. --}}
                <img src="{{ asset('images/logo-light.png') }}" alt="Logo {{ $company['name'] }}" class="group-solid/nav:hidden h-9 w-auto md:h-10">
                <img src="{{ asset('images/logo.png') }}" alt="" class="group-solid/nav:block hidden h-9 w-auto md:h-10">
            </a>

            {{-- Menu (desktop) --}}
            <nav aria-label="Menu utama" class="hidden h-full md:ml-auto md:block">
                <ul class="flex h-full items-stretch">
                    @foreach ($links as $link)
                        <li class="group/item flex h-full items-center">
                            <a href="{{ $link['href'] }}" @if ($link['active']) aria-current="page" @endif
                               class="relative inline-flex items-center px-4 py-1.5 text-[15px] font-medium {{ $focus }}">
                                {{ $link['label'] }}
                                @if ($link['mega'])
                                    <svg class="ml-1.5 size-4 transition-transform duration-300 group-hover/item:rotate-180 group-focus-within/item:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                                @endif
                                <span @class([$lineBase, 'bg-gold-metal inset-x-4 bottom-0', 'scale-x-100' => $link['active'], 'scale-x-0 group-hover/item:scale-x-100 group-focus-within/item:scale-x-100' => ! $link['active']])></span>
                            </a>

                            @if ($link['mega'])
                                {{-- Mega menu layanan: muncul saat hover atau fokus keyboard --}}
                                <div class="invisible absolute inset-x-0 top-full translate-y-1 border-b border-gold-300/70 bg-white opacity-0 shadow-[0_24px_40px_-24px_rgb(20_17_10/0.35)] transition duration-200 group-hover/item:visible group-hover/item:translate-y-0 group-hover/item:opacity-100 group-focus-within/item:visible group-focus-within/item:translate-y-0 group-focus-within/item:opacity-100">
                                    <div class="mx-auto grid max-w-7xl gap-10 px-6 py-10 lg:grid-cols-[240px_1fr] lg:px-8">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gold-700">Layanan Kami</p>
                                            <p class="mt-3 text-sm leading-6 text-ink-800">Bidang usaha perdagangan besar yang kami layani.</p>
                                            <a href="{{ url('/layanan') }}" class="group/all relative mt-5 inline-flex items-center gap-2 py-1 text-sm font-semibold text-ink {{ $focus }}">
                                                Lihat semua layanan
                                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                                <span class="{{ $lineBase }} bg-gold-metal inset-x-0 bottom-0 scale-x-0 group-hover/all:scale-x-100 group-focus-visible/all:scale-x-100"></span>
                                            </a>
                                        </div>
                                        <ul class="grid gap-x-10 gap-y-1 sm:grid-cols-2 xl:grid-cols-3">
                                            @foreach ($company['services'] as $service)
                                                <li>
                                                    <a href="{{ url('/layanan') }}" class="group/svc relative block py-3 pr-2 text-[15px] font-medium leading-6 text-ink {{ $focus }}">
                                                        {{ $service }}
                                                        <span class="{{ $lineBase }} bg-gold-metal inset-x-0 bottom-0 scale-x-0 group-hover/svc:scale-x-100 group-focus-visible/svc:scale-x-100"></span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>

            {{-- Aksi kanan --}}
            <div class="flex items-center justify-end md:ml-8">
                <a href="{{ $contactUrl }}"
                   class="bg-gold-sheen hidden h-10 items-center px-6 text-sm font-semibold text-ink transition hover:brightness-95 {{ $focus }} md:inline-flex">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>

    {{-- Panel menu (mobile) --}}
    <div id="mobile-menu" x-show="open" x-cloak class="absolute inset-x-0 top-full max-h-[calc(100vh-3.5rem)] overflow-y-auto border-b border-gold-300/70 bg-white shadow-[0_24px_40px_-24px_rgb(20_17_10/0.35)] md:hidden">
        <nav aria-label="Menu utama" class="px-4 pb-6 pt-2 sm:px-6">
            <ul>
                @foreach ($links as $link)
                    <li class="border-b border-gold-100">
                        <a href="{{ $link['href'] }}" @if ($link['active']) aria-current="page" @endif
                           class="group/link relative flex h-14 items-center justify-between text-base font-medium text-ink {{ $focus }}">
                            {{ $link['label'] }}
                            <svg class="size-5 text-gold-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
                            <span @class([$lineBase, 'bg-gold-metal inset-x-0 bottom-0', 'scale-x-100' => $link['active'], 'scale-x-0 group-hover/link:scale-x-100 group-focus-visible/link:scale-x-100' => ! $link['active']])></span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <a href="{{ $contactUrl }}"
               class="bg-gold-sheen mt-6 flex h-14 items-center justify-center text-base font-semibold text-ink transition hover:brightness-95 {{ $focus }}">
                Hubungi Kami
            </a>
        </nav>
    </div>
</header>
