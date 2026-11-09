<?php

use App\Http\Controllers\Web\GalleryController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
