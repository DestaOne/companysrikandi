<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\GalleryService;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __construct(private GalleryService $galleryService) {}

    public function index(): View
    {
        return view('web.galleries.index', [
            'galleries' => $this->galleryService->paginate(14), // kelipatan 7, sesuai pola petak
            'gallery' => config('gallery'),
        ]);
    }
}
