<div class="grid gap-6 sm:grid-cols-3">
    <div class="sm:col-span-2">
        <x-ui.input name="title" label="Judul" :value="old('title', $service->title ?? '')" required />
    </div>
    <x-ui.select name="status" label="Status" :options="\App\Enums\ServiceStatus::options()"
                 :value="old('status', isset($service) ? $service->status->value : 'published')" />
</div>

<x-ui.textarea name="description" label="Deskripsi" :value="old('description', $service->description ?? '')" required />

<x-ui.textarea name="products" label="Daftar Barang" rows="5" required hint="Satu barang per baris."
               :value="old('products', $service->products_text ?? '')" />

<x-ui.file-upload name="image" label="Gambar" :current="isset($service) ? asset($service->image) : null"
                  :hint="isset($service) ? 'Kosongkan jika tidak ingin mengganti gambar.' : 'JPG, PNG, atau WEBP, maksimal 2 MB.'" />
