<?php

namespace App\Services;

use App\Helpers\UploadHelper;
use App\Models\Gallery;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

class GalleryService
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Gallery::latest()->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data, UploadedFile $image): Gallery
    {
        $path = UploadHelper::store($image, 'galleries');

        try {
            return Gallery::create([...$data, 'image' => $path]);
        } catch (Throwable $e) {
            UploadHelper::delete($path); // bersihkan file yatim, lalu lempar ulang
            throw $e;
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Gallery $gallery, array $data, ?UploadedFile $image = null): Gallery
    {
        $oldPath = $gallery->image;
        $newPath = $image ? UploadHelper::store($image, 'galleries') : null;

        try {
            $gallery->update($newPath ? [...$data, 'image' => $newPath] : $data);
        } catch (Throwable $e) {
            UploadHelper::delete($newPath);
            throw $e;
        }

        if ($newPath) {
            UploadHelper::delete($oldPath); // hapus file lama hanya setelah DB berhasil
        }

        return $gallery;
    }

    public function delete(Gallery $gallery): void
    {
        $gallery->delete();
        UploadHelper::delete($gallery->image);
    }
}
