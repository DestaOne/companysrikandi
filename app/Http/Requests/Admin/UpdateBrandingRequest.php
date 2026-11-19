<?php

namespace App\Http\Requests\Admin;

use App\Enums\HomeSectionKey;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses sudah dibatasi middleware auth di routes/admin.php
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return array_map(
            fn (): array => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            HomeSectionKey::Branding->logoFields(),
        );
    }
}
