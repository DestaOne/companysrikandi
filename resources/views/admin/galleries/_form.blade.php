@csrf
<div class="space-y-5">
    <x-ui.input name="title" label="Judul" :value="old('title', $gallery->title ?? '')" required />
    <x-ui.textarea name="description" label="Deskripsi" :value="old('description', $gallery->description ?? '')" />
    <x-ui.file-upload name="image" label="Gambar"
                      :current="isset($gallery) ? asset('storage/'.$gallery->image) : null" />
    <div class="flex gap-3">
        <x-ui.button type="submit">Simpan</x-ui.button>
        <x-ui.button variant="secondary" :href="route('admin.galleries.index')">Batal</x-ui.button>
    </div>
</div>
