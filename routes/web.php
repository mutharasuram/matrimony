<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Default login route (redirects to admin login)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Admin Routes
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

// Protected Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/images/{imageId}', [AdminController::class, 'deleteUserImage'])->name('admin.users.images.delete');
    Route::post('/admin/users/{id}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');
    Route::post('/admin/users/{id}/activate', [AdminController::class, 'activateUser'])->name('admin.users.activate');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
});
