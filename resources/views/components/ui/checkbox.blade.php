{{-- Props: name (wajib), label, checked --}}
@props(['name', 'label' => null, 'checked' => false])

<label for="{{ $name }}" class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-ink">
    <input id="{{ $name }}" name="{{ $name }}" type="checkbox" value="1" @checked($checked)
           {{ $attributes->merge(['class' => 'size-4 rounded border-gold-300 text-ink accent-gold-500 focus:ring-gold-500']) }}>
    {{ $label ?? $slot }}
</label>
