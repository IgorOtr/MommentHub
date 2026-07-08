<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::redirect('/dashboard', '/admin')->middleware('auth')->name('dashboard');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/customers');

    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('customer/{customer:slug}', [CustomerController::class, 'show'])->name('customer.show');

    Route::post('stores', [StoreController::class, 'store'])->name('stores.store');
    Route::put('stores/{store}', [StoreController::class, 'update'])->name('stores.update');
    Route::delete('stores/{store}', [StoreController::class, 'destroy'])->name('stores.destroy');
    Route::get('store/{store:slug}', [StoreController::class, 'show'])->name('store.show');

    Route::post('events', [EventController::class, 'store'])->name('events.store');
    Route::put('events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('event/{event:slug}', [EventController::class, 'show'])->name('event.show');

    Route::post('folders', [FolderController::class, 'store'])->name('folders.store');
    Route::put('folders/{folder}', [FolderController::class, 'update'])->name('folders.update');
    Route::delete('folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');
    Route::get('folder/{folder:slug}', [FolderController::class, 'show'])->name('folder.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Kept last: these single-segment routes would otherwise swallow any
// earlier top-level path (e.g. /login) as a customer slug.
Route::scopeBindings()->name('gallery.')->group(function () {
    Route::get('{customer:slug}', [GalleryController::class, 'customer'])->name('customer');
    Route::get('{customer:slug}/{store:slug}', [GalleryController::class, 'store'])->name('store');
    Route::get('{customer:slug}/{store:slug}/{event:slug}', [GalleryController::class, 'event'])->name('event');
    Route::get('{customer:slug}/{store:slug}/{event:slug}/{folder:slug}', [GalleryController::class, 'folder'])->name('folder');
});
