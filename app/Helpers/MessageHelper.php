<?php

namespace App\Helpers;

class MessageHelper
{
    /**
     * Pesan sukses untuk popup. Kalimatnya ditulis langsung di controller dan ditampilkan apa adanya.
     *
     * @return array{success: string}
     */
    public static function success(string $message): array
    {
        return ['success' => $message];
    }

    /**
     * Pesan gagal untuk popup. Tulis kalimat umum; detail error masuk log lewat report(), bukan ke user.
     *
     * @return array{error: string}
     */
    public static function error(string $message): array
    {
        return ['error' => $message];
    }
}
