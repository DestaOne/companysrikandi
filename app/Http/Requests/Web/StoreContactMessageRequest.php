<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // form kontak terbuka untuk pengunjung
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'regex:/^\+?[0-9\s\-]{8,}$/'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:3000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'phone' => 'nomor telepon/WhatsApp',
            'subject' => 'subjek',
            'message' => 'isi pesan',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return ['phone.regex' => 'Nomor telepon/WhatsApp tidak valid (hanya angka, minimal 8 digit).'];
    }
}
