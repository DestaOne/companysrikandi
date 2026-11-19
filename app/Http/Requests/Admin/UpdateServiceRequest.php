<?php

namespace App\Http\Requests\Admin;

// Aturan dan pesan sama dengan tambah layanan; hanya gambar yang boleh dikosongkan.
class UpdateServiceRequest extends StoreServiceRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [...parent::rules(), 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']];
    }
}
