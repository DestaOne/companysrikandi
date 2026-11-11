<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message'];
}
