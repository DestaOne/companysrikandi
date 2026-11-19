{{-- Props: index (nomor kartu, mulai 0), items (id => [title, image] dari ServiceCatalogService::pickerItems), selected (id layanan terpilih) --}}
@props(['index', 'items', 'selected' => null])

{{-- Kartu pilihan layanan: gambar dan judul berubah langsung saat pilihan diganti --}}
<div x-data="{ items: @js($items), selected: @js((string) $selected) }"
     {{ $attributes->class('flex flex-col overflow-hidden border border-gold-300/70 bg-white shadow-sm') }}>
    <div class="relative aspect-[8/5] overflow-hidden bg-ink">
        <img :src="items[selected]?.image" alt="" class="absolute inset-0 size-full object-cover">
        <div class="absolute inset-0 bg-linear-to-t from-ink/90 via-ink/30 to-transparent"></div>
        <span class="absolute left-3 top-3 bg-ink px-2.5 py-1 text-xs font-bold uppercase tracking-widest text-gold-300">Kartu {{ $index + 1 }}</span>
        <h3 class="absolute inset-x-0 bottom-0 p-4 text-lg font-semibold leading-snug text-white" x-text="items[selected]?.title"></h3>
    </div>

    <div class="p-4">
        <x-ui.select :name="'service_ids['.$index.']'" label="Layanan" :options="collect($items)->pluck('title')->all()" :value="$selected" x-model="selected" />
    </div>
</div>
