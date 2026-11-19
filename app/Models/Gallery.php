<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Database\Factories\GalleryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    /** @use HasFactory<GalleryFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['title', 'description', 'image', 'sort_order'];

    /** Urutan tampil di galeri: sesuai sort_order yang diatur admin. */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }

    public function activityLabel(): ?string
    {
        return $this->title;
    }
}
