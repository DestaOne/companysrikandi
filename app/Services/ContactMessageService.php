<?php

namespace App\Services;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactMessageService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): ContactMessage
    {
        return ContactMessage::create($data);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return ContactMessage::latest()->orderByDesc('id')->paginate($perPage);
    }

    public function unreadCount(): int
    {
        return ContactMessage::unread()->count();
    }

    /**
     * Pesan belum dibaca terbaru untuk pratinjau di lonceng.
     *
     * @return Collection<int, ContactMessage>
     */
    public function newestUnread(int $limit = 5): Collection
    {
        return ContactMessage::unread()->latest()->orderByDesc('id')->limit($limit)->get();
    }

    /** Tandai dibaca tanpa mencatat aktivitas, karena membuka pesan bukan perubahan data. */
    public function markAsRead(ContactMessage $message): void
    {
        if ($message->read_at === null) {
            $message->forceFill(['read_at' => now()])->saveQuietly();
        }
    }

    public function delete(ContactMessage $message): void
    {
        $message->delete();
    }

    /**
     * Tautan email siap kirim ke perusahaan, berisi data yang diisi pengunjung di form.
     *
     * @return array{gmail: string, mailto: string}
     */
    public function mailDraft(ContactMessage $message, string $to): array
    {
        $subject = $message->subject;
        $body = $message->message
            ."\n\n--\nNama: {$message->name}\nEmail: {$message->email}\nTelepon/WhatsApp: {$message->phone}";

        $gmail = http_build_query(['view' => 'cm', 'fs' => 1, 'to' => $to, 'su' => $subject, 'body' => $body], '', '&', PHP_QUERY_RFC3986);
        $mailto = http_build_query(['subject' => $subject, 'body' => $body], '', '&', PHP_QUERY_RFC3986);

        return [
            'gmail' => 'https://mail.google.com/mail/?'.$gmail,
            'mailto' => 'mailto:'.$to.'?'.$mailto,
        ];
    }
}
