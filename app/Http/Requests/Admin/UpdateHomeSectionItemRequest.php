<?php

namespace App\Http\Requests\Admin;

use App\Enums\HomeSectionKey;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeSectionItemRequest extends FormRequest
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
        /** @var HomeSectionKey $section */
        $section = $this->route('home_section');
        $rules = ['image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']];

        foreach ($section->itemFields() as $name => $field) {
            $rules[$name] = ['required', 'string', 'max:'.$field['max']];
        }

        return $rules;
    }
}
