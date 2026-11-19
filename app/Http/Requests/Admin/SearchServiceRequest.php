<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SearchServiceRequest extends FormRequest
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
        return ['q' => ['nullable', 'string', 'max:100']];
    }
}
