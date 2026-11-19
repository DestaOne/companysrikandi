<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SortService
{
    /**
     * Susun ulang urutan sesuai daftar id. Nilai sort_order yang sudah dipakai oleh id-id tersebut
     * dibagikan ulang, jadi baris lain (mis. di halaman pagination berbeda) tidak ikut bergeser.
     *
     * @param  class-string<Model>  $model
     * @param  array<int, int|string>  $ids  id berurutan sesuai posisi baru
     */
    public function reorder(string $model, array $ids): void
    {
        $slots = $model::whereIn('id', $ids)->orderBy('sort_order')->pluck('sort_order')->values();

        DB::transaction(function () use ($model, $ids, $slots) {
            foreach (array_values($ids) as $position => $id) {
                $model::whereKey($id)->update(['sort_order' => $slots[$position]]);
            }
        });
    }
}
