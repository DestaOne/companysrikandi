<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Database\Factories\GalleryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    /** @use HasFactory<GalleryFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = ['title', 'description', 'image'];
}
