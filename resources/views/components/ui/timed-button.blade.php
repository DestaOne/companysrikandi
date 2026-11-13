{{-- Props: seconds (sisa detik tombol terkunci; 0 = langsung aktif). Slot: label tombol submit --}}
@props(['seconds' => 0])

<x-ui.button type="submit"
             x-data="countdown({{ (int) $seconds }})"
             x-bind:disabled="left > 0"
             {{ $attributes->merge(['class' => 'disabled:cursor-not-allowed']) }}>
    <span x-show="left <= 0">{{ $slot }}</span>
    <span x-show="left > 0" x-cloak>Coba lagi dalam <span x-text="left"></span> detik</span>
</x-ui.button>
