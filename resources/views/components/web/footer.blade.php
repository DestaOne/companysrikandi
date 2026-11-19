{{-- Tanpa props; layanan dari menu Layanan ($footerServices lewat view composer); profil dari config('company'), alamat/telepon/email dari menu Tentang (variabel $contact lewat view composer di AppServiceProvider) --}}
@php
    use App\Support\Company;

    $company = config('company');

    $contacts = [
        [
            'label' => 'Alamat',
            'value' => $contact['address'],
            'href' => null,
            'icon' => '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21z"/><circle cx="12" cy="9.5" r="2.5"/>',
        ],
        [
            'label' => 'Telepon',
            'value' => $contact['phone'],
            'href' => Company::phoneHref($contact['phone']),
            'icon' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/>',
        ],
        [
            'label' => 'Email',
            'value' => $contact['email'],
            'href' => Company::emailHref($contact['email']),
            'icon' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        ],
        [
            'label' => 'Jam operasional',
            'value' => $company['hours'],
            'href' => null,
            'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        ],
    ];

    $contacts = array_filter($contacts, fn (array $contact) => filled($contact['value']));

    $linkClass ='group/link relative inline-block py-0.5 text-[15px] font-medium text-white outline-none focus-visible:ring-2 focus-visible:ring-gold-300';
    $lineClass = 'pointer-events-none absolute inset-x-0 bottom-0 h-0.5 origin-left scale-x-0 bg-gold-metal transition-transform duration-300 ease-out group-hover/link:scale-x-100 group-focus-visible/link:scale-x-100';
@endphp

<footer class="mt-auto bg-ink text-white">
    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 md:grid-cols-[1fr_1fr_auto] md:gap-12 lg:gap-20 lg:px-8 lg:py-20">

        {{-- Kiri: logo dan deskripsi --}}
        <div class="flex flex-col items-start gap-6">
            <a href="{{ route('home') }}" aria-label="{{ $company['name'] }}, ke beranda"
               class="flex h-16 items-center rounded-2xl bg-white px-4 outline-none focus-visible:ring-2 focus-visible:ring-gold-300">
                <img src="{{ asset($logos['footer_logo']) }}" alt="Logo {{ $company['name'] }}" class="h-12 w-auto">
            </a>
            <p class="max-w-sm text-[15px] font-medium leading-7">{{ $company['description'] }}</p>
        </div>

        {{-- Tengah: kontak --}}
        <div>
            <h2 class="mb-6 text-xs font-bold uppercase tracking-[0.18em] text-gold-300">Kontak</h2>
            <ul class="flex flex-col gap-5 text-[15px] font-medium leading-6">
                @foreach ($contacts as $contact)
                    <li class="flex items-start gap-3.5">
                        <svg class="mt-0.5 size-5 flex-none text-gold-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $contact['icon'] !!}</svg>
                        <span>
                            <span class="sr-only">{{ $contact['label'] }}: </span>
                            @if ($contact['href'])
                                <a href="{{ $contact['href'] }}" class="{{ $linkClass }}">{{ $contact['value'] }}<span class="{{ $lineClass }}"></span></a>
                            @else
                                {{ $contact['value'] }}
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Kanan: layanan --}}
        <div>
            <h2 class="mb-6 text-xs font-bold uppercase tracking-[0.18em] text-gold-300">Layanan</h2>
            {{-- Lima judul per kolom; layanan berikutnya lanjut ke kolom di kanannya (di ponsel tetap satu daftar) --}}
            <ul class="grid gap-3.5 md:auto-cols-[minmax(0,15rem)] md:grid-flow-col md:grid-rows-5 md:gap-x-10">
                @forelse ($footerServices as $service)
                    <li><a href="{{ route('services.show', $service->slug) }}" class="{{ $linkClass }}">{{ $service->title }}<span class="{{ $lineClass }}"></span></a></li>
                @empty
                    <li class="text-[15px] font-medium">Belum ada layanan.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10 bg-ink-950 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-5 text-[13px] font-medium sm:px-6 lg:px-8">
            <p>&copy; {{ now()->year }} {{ $company['name'] }}. Hak cipta dilindungi.</p>
        </div>
    </div>
</footer>
