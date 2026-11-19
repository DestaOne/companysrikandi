<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /** Tautan balas lewat WhatsApp dengan sapaan awal; nomor lokal (08..) diubah ke format internasional (62..). */
    protected function whatsappUrl(): Attribute
    {
        return Attribute::get(function (): string {
            $digits = preg_replace('/\D+/', '', $this->phone);
            $number = str_starts_with($digits, '0') ? '62'.substr($digits, 1) : $digits;
            $text = "Halo {$this->name}, terima kasih telah menghubungi ".config('company.name').". Terkait pesan Anda “{$this->subject}”: ";

            return 'https://wa.me/'.$number.'?'.http_build_query(['text' => $text], '', '&', PHP_QUERY_RFC3986);
        });
    }

    /** Tautan balas lewat email (membuka aplikasi email pengguna) dengan subjek "Re: ..." dan sapaan awal. */
    protected function replyMailUrl(): Attribute
    {
        return Attribute::get(function (): string {
            $query = http_build_query([
                'subject' => 'Re: '.$this->subject,
                'body' => "Halo {$this->name},\n\nTerima kasih telah menghubungi ".config('company.name').".\n\n",
            ], '', '&', PHP_QUERY_RFC3986);

            return 'mailto:'.$this->email.'?'.$query;
        });
    }

    public function activityLabel(): ?string
    {
        return $this->name;
    }
}
