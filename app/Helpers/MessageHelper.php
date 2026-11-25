<?php

namespace App\Helpers;

class MessageHelper
{
    /**
     * Kata kerja per aksi: [bentuk sukses (pasif), bentuk gagal (aktif)].
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const ACTIONS = [
        'created' => ['ditambahkan', 'menyimpan'],
        'updated' => ['diperbarui', 'memperbarui'],
        'deleted' => ['dihapus', 'menghapus'],
    ];

    /**
     * Pesan sukses siap pakai untuk `->with(...)`. Aksi di luar daftar dipakai apa adanya
     * sebagai kata kerja pasif; $message menimpa seluruh kalimat bila perlu pesan khusus.
     *
     * @return array{success: string}
     */
    public static function success(string $subject, string $action = 'created', ?string $message = null): array
    {
        $verb = self::ACTIONS[$action][0] ?? $action;

        return ['success' => $message ?? "{$subject} berhasil {$verb}."];
    }

    /**
     * Pesan gagal yang umum (detail error masuk log lewat report(), bukan ke user).
     *
     * @return array{error: string}
     */
    public static function error(string $subject, string $action = 'created', ?string $message = null): array
    {
        $verb = self::ACTIONS[$action][1] ?? $action;

        return ['error' => $message ?? 'Gagal '.$verb.' '.mb_strtolower($subject).'.'];
    }
}
