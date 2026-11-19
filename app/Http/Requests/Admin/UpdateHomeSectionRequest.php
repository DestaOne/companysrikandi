<?php

namespace App\Http\Requests\Admin;

use App\Enums\HomeSectionKey;
use App\Enums\ServiceStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHomeSectionRequest extends FormRequest
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
        /** @var HomeSectionKey $section */
        $section = $this->route('home_section');
        $imageRule = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        $rules = [];

        foreach ($section->fields() as $name => $field) {
            $rules[$name] = ['required', 'string', 'max:'.$field['max'], ...($field['rules'] ?? [])];
        }

        if ($section->hasImage()) {
            $rules['image'] = $imageRule;
        }

        if ($section->picksServices()) {
            // Empat kartu, masing-masing layanan terbit yang berbeda
            $rules['service_ids'] = ['required', 'array', 'size:4'];
            $rules['service_ids.*'] = ['required', 'integer', 'distinct', Rule::exists('services', 'id')->where('status', ServiceStatus::Published->value)];
        }

        return $rules;
    }
}
