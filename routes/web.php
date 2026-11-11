<?php

use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\GalleryController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/tentang-kami', [AboutController::class, 'index'])->name('about');
Route::post('/tentang-kami/pesan', [AboutController::class, 'store'])->middleware('throttle:5,1')->name('about.contact.store');
Route::get('/layanan', ServiceController::class)->name('services');
Route::get('/galeri', [GalleryController::class, 'index'])->name('galleries.index');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
