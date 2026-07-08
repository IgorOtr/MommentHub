<?php

use App\Http\Controllers\Api\GalleryController;
use Illuminate\Support\Facades\Route;

Route::scopeBindings()->name('api.gallery.')->group(function () {
    Route::get('{customer:slug}', [GalleryController::class, 'customer'])->name('customer');
    Route::get('{customer:slug}/{store:slug}', [GalleryController::class, 'store'])->name('store');
    Route::get('{customer:slug}/{store:slug}/{event:slug}', [GalleryController::class, 'event'])->name('event');
    Route::get('{customer:slug}/{store:slug}/{event:slug}/{folder:slug}', [GalleryController::class, 'folder'])->name('folder');
});
