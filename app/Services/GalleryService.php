<?php

namespace App\Services;

use App\Helpers\UploadHelper;
use App\Models\Gallery;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Throwable;

class GalleryService
{
    public function __construct(private SortService $sortService) {}

    /**
     * Semua gambar dalam satu halaman, urut sesuai pengaturan admin; gambar baru terus bertambah ke bawah.
     *
     * @return Collection<int, Gallery>
     */
    public function all(): Collection
    {
        return Gallery::ordered()->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data, UploadedFile $image): Gallery
    {
        $path = UploadHelper::store($image, 'galleries');

        try {
            return Gallery::create([...$data, 'image' => $path, 'sort_order' => (Gallery::max('sort_order') ?? 0) + 1]);
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

    /**
     * Simpan urutan baru: id berurutan sesuai posisi yang diinginkan.
     *
     * @param  array<int, int|string>  $ids
     */
    public function reorder(array $ids): void
    {
        $this->sortService->reorder(Gallery::class, $ids);
    }

    public function delete(Gallery $gallery): void
    {
        $gallery->delete();
        UploadHelper::delete($gallery->image);
    }
}
