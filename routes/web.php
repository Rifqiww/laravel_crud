<?php

use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $products = \App\Models\Product::latest('updated_at')->take(12)->get();
    return view('dashboard', compact('products'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Product CRUD routes (Accessible only by 'super-admin' and 'admin')
Route::middleware(['auth', 'role:super-admin|admin'])->group(function () {
    Route::resource('products', ProductController::class)->except(['show']);
});

// Product detail route (Accessible by all authenticated users: Member, Admin, Super-Admin)
Route::middleware('auth')->group(function () {
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});

// Admin Role Management routes (Accessible ONLY by 'super-admin')
Route::middleware(['auth', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/roles', [RoleManagementController::class, 'index'])->name('roles.index');
    Route::post('/users', [RoleManagementController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/role', [RoleManagementController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [RoleManagementController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
