{{-- Props: name (wajib), label, current (URL gambar saat ini, untuk halaman edit) --}}
@props(['name', 'label' => null, 'current' => null])

<div class="space-y-2"
     x-data="{
         preview: @js($current),
         pick(e) {
             const file = e.target.files[0];
             if (file) this.preview = URL.createObjectURL(file);
         },
     }">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink">{{ $label }}</label>
    @endif

    <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/*"
           @change="pick($event)"
           {{ $attributes->merge(['class' => 'block w-full text-sm']) }}>

    <img x-show="preview" x-cloak :src="preview" alt="Pratinjau" class="h-32 rounded-md object-cover">

    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
