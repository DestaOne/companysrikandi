{{-- Tanpa props. Cukup satu di layout; buka dengan $dispatch('open-image', { src, title }) atau <x-ui.zoomable>. --}}
<div x-data="imageViewer" x-cloak x-show="open" @open-image.window="show($event.detail)" @keydown.escape.window="open && close()"
     class="fixed inset-0 z-[70] flex flex-col bg-ink-950/95" role="dialog" aria-modal="true" aria-label="Pratinjau gambar"
     x-transition.opacity>
    <div class="flex items-center justify-between gap-4 px-4 py-3 text-white sm:px-6">
        <p class="min-w-0 truncate text-sm font-medium" x-text="title"></p>

        <div class="flex shrink-0 items-center gap-1">
            <button type="button" @click="zoom(-0.5)" :disabled="scale <= 1" aria-label="Perkecil"
                    class="flex size-9 items-center justify-center rounded-md outline-none hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-gold-300 disabled:opacity-30">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M5 12h14"/></svg>
            </button>
            <button type="button" @click="reset()" aria-label="Kembalikan ukuran" class="min-w-14 rounded-md px-2 py-1.5 text-sm tabular-nums outline-none hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-gold-300" x-text="percent"></button>
            <button type="button" @click="zoom(0.5)" :disabled="scale >= 3" aria-label="Perbesar"
                    class="flex size-9 items-center justify-center rounded-md outline-none hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-gold-300 disabled:opacity-30">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
            </button>
            <button type="button" x-ref="close" @click="close()" aria-label="Tutup"
                    class="ml-2 flex size-9 items-center justify-center rounded-md outline-none hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-gold-300">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
        </div>
    </div>

    <div x-ref="stage" class="flex min-h-0 flex-1 touch-none items-center justify-center overflow-hidden px-4 pb-6 sm:px-8"
         :class="scale > 1 ? (dragging ? 'cursor-grabbing' : 'cursor-grab') : ''"
         @click.self="backdrop()" @wheel.prevent="wheel($event)"
         @pointerdown="down($event)" @pointermove="move($event)" @pointerup="up()" @pointercancel="up()">
        <img x-ref="image" :src="src" :alt="title" draggable="false" @dblclick="toggle()"
             class="max-h-full max-w-full select-none object-contain"
             :class="dragging ? '' : 'transition-transform duration-200'" :style="style">
    </div>
</div>
