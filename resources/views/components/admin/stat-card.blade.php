{{-- Props: label, value, hint; slot: ikon (svg) --}}
@props(['label', 'value', 'hint' => null])

<div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-gold-300/60">
    <span class="bg-gold-sheen flex size-12 shrink-0 items-center justify-center rounded-xl text-ink">{{ $slot }}</span>
    <div class="min-w-0">
        <p class="text-sm text-ink-800">{{ $label }}</p>
        <p class="text-3xl font-semibold leading-tight">{{ $value }}</p>
        @if ($hint)
            <p class="truncate text-xs text-ink-800/70">{{ $hint }}</p>
        @endif
    </div>
</div>
