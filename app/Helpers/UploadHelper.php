<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class UploadHelper
{
    public static function store(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public')
            ?: throw new RuntimeException('Gagal menyimpan file.');
    }

    public static function delete(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Hapus file dari path berawalan "storage/" (hasil upload). Gambar bawaan di
     * public/images tidak disentuh.
     */
    public static function deleteUrl(?string $url): void
    {
        if ($url && str_starts_with($url, 'storage/')) {
            self::delete(substr($url, strlen('storage/')));
        }
    }
}
