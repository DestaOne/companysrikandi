<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'read_at'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    /** Pesan yang belum dibuka admin. */
    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }
}
