<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\BrandingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\ServiceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('home-sections/{home_section}/items/{index}/edit', [HomeSectionController::class, 'editItem'])->whereNumber('index')->name('home-sections.items.edit');
    Route::put('home-sections/{home_section}/items/{index}', [HomeSectionController::class, 'updateItem'])->whereNumber('index')->name('home-sections.items.update');
    Route::resource('home-sections', HomeSectionController::class)->only(['index', 'edit', 'update']);
    Route::put('services/reorder', [ServiceController::class, 'reorder'])->name('services.reorder');
    Route::resource('services', ServiceController::class)->except('show');
    Route::post('activities/seen', [ActivityController::class, 'seen'])->name('activities.seen');
    Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('logo', [BrandingController::class, 'edit'])->name('branding.edit');
    Route::put('logo', [BrandingController::class, 'update'])->name('branding.update');
    Route::delete('logo/{field}', [BrandingController::class, 'reset'])->name('branding.reset');
    Route::resource('messages', ContactMessageController::class)->only(['index', 'show', 'destroy'])->parameters(['messages' => 'contactMessage']);
    Route::get('about', AboutController::class)->name('about.index');
    Route::put('galleries/reorder', [GalleryController::class, 'reorder'])->name('galleries.reorder');
    Route::resource('galleries', GalleryController::class)->except('show');
});
