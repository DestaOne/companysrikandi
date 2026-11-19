{{-- Props: label, value, hint; slot: ikon (svg) --}}
@props(['label', 'value', 'hint' => null])

<div class="flex items-center gap-3 rounded-2xl bg-white p-3.5 sm:gap-4 sm:p-5 shadow-sm ring-1 ring-gold-300/60">
    <span class="bg-gold-sheen flex size-10 shrink-0 items-center justify-center rounded-xl sm:size-12 text-ink">{{ $slot }}</span>
    <div class="min-w-0">
        <p class="text-sm text-ink-800">{{ $label }}</p>
        <p class="text-2xl font-semibold sm:text-3xl leading-tight">{{ $value }}</p>
        @if ($hint)
            <p class="truncate text-xs text-ink-800/70">{{ $hint }}</p>
        @endif
    </div>
</div>
