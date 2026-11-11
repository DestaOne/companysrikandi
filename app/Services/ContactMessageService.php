<?php

namespace App\Services;

use App\Models\ContactMessage;

class ContactMessageService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): ContactMessage
    {
        return ContactMessage::create($data);
    }
}
