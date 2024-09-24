<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DbNotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryFilterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BillboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductGroupBySizeController;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/notifications', [DbNotificationController::class, 'index'])->middleware(['auth', 'verified'])->name('notifications');
Route::get('/markasread/{id?}', [DbNotificationController::class, 'markasread'])->middleware(['auth', 'verified'])->name('markasread');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['verified'])->group(function() {
    Route::resources([
        'users' => UserController::class,
        'categories' => CategoryController::class,
        'category-filters' => CategoryFilterController::class,
        'tags' => TagController::class,
        'billboards' => BillboardController::class,
        'product-group-by-sizes' => ProductGroupBySizeController::class,
    ]);

    Route::resource('orders', OrderController::class)->except([
        'create', 'store', 'edit', 'update'
    ]);
    Route::get('/deleted-orders', [OrderController::class, 'deletedOrders'])->name('orders.deleted');
    Route::post('/order-restore/{$id}', [OrderController::class, 'restore'])->name('orders.restore');
    Route::delete('/order-force-delete/{$id}', [OrderController::class, 'forceDelete'])->name('orders.force-delete');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resources([
        'products' => ProductController::class,
    ]);
});

require __DIR__.'/auth.php';
