{{-- Tanpa props; menampilkan session 'success' atau 'error' sebagai popup kartu di tengah layar (centang hijau / silang merah), ditutup lewat tombol, klik di luar kartu, atau Esc. Pesan sukses menutup sendiri. --}}
@php
    $type = session('error') ? 'error' : (session('success') ? 'success' : null);
@endphp

@if ($type)
    <div x-data="{ show: true }"
         @if ($type === 'success') x-init="setTimeout(() => show = false, 3500)" @endif
         x-show="show" x-transition.opacity.duration.200ms x-cloak
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-ink/60" x-on:click="show = false"></div>

        <div role="alertdialog" aria-modal="true" aria-live="assertive"
             class="animate-pop relative w-full max-w-sm border-t-4 bg-white px-8 pb-7 pt-9 text-center shadow-2xl {{ $type === 'success' ? 'border-green-500' : 'border-red-500' }}">
            <div @class([
                'mx-auto flex size-20 items-center justify-center rounded-full',
                'bg-green-100 text-green-600' => $type === 'success',
                'bg-red-100 text-red-600' => $type === 'error',
            ])>
                <svg class="size-12" viewBox="0 0 52 52" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="26" cy="26" r="22" pathLength="1" class="draw-stroke" />
                    @if ($type === 'success')
                        <path d="M15 27l8 8 14-16" pathLength="1" class="draw-stroke" style="animation-delay: 0.35s" />
                    @else
                        <path d="M18 18l16 16" pathLength="1" class="draw-stroke" style="animation-delay: 0.35s" />
                        <path d="M34 18L18 34" pathLength="1" class="draw-stroke" style="animation-delay: 0.6s" />
                    @endif
                </svg>
            </div>

            <h2 class="mt-5 text-xl font-bold text-ink">{{ $type === 'success' ? 'Berhasil' : 'Gagal' }}</h2>
            <p class="mt-2 text-base leading-7 text-ink-800">{{ session($type) }}</p>

            <x-ui.button :variant="$type === 'success' ? 'primary' : 'danger'" x-on:click="show = false" class="mt-6 w-full">Tutup</x-ui.button>
        </div>
    </div>
@endif
