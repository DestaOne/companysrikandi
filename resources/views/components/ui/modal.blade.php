{{-- Props: name (wajib; dibuka lewat $dispatch('open-modal', name)), title --}}
@props(['name', 'title' => null])

<div x-data="{ show: false }"
     x-on:open-modal.window="if ($event.detail === @js($name)) show = true"
     x-on:close-modal.window="if ($event.detail === @js($name)) show = false"
     x-on:keydown.escape.window="show = false"
     x-show="show" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-ink/60" x-on:click="show = false"></div>

    <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
        @if ($title)
            <h2 class="text-lg font-semibold text-ink">{{ $title }}</h2>
        @endif

        <div class="mt-3">{{ $slot }}</div>
    </div>
</div>
