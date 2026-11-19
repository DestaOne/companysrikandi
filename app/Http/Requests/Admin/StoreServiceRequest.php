<?php

namespace App\Http\Requests\Admin;

use App\Enums\ServiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses sudah dibatasi middleware auth di routes/admin.php
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'products' => ['required', 'string', 'max:2000'],
            'status' => ['required', Rule::enum(ServiceStatus::class)],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Pesan dalam bahasa sehari-hari agar mudah dipahami admin non-teknis.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul layanan wajib diisi.',
            'title.max' => 'Judul layanan terlalu panjang (maksimal 255 karakter).',
            'description.required' => 'Deskripsi layanan wajib diisi.',
            'products.required' => 'Daftar barang wajib diisi, minimal satu barang.',
            'products.max' => 'Daftar barang terlalu panjang (maksimal 2000 karakter).',
            'status.required' => 'Pilih status layanan: Terbit atau Draft.',
            'status.enum' => 'Pilih status layanan: Terbit atau Draft.',
            'image.required' => 'Gambar layanan wajib diunggah.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat JPG, PNG, atau WEBP.',
            'image.max' => 'Ukuran gambar terlalu besar (maksimal 2 MB).',
            'image.uploaded' => 'Gambar gagal diunggah. Pastikan ukurannya maksimal 2 MB.',
        ];
    }
}
