<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name'),
        'env' => app()->environment(),
        'time' => now()->toIso8601String(),
    ]);
})->name('health');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/dashboard', [ProjectController::class, 'dashboard'])->name('dashboard');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects/store', [ProjectController::class, 'store'])->middleware('throttle:20,1')->name('projects.store');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}/update', [ProjectController::class, 'update'])->middleware('throttle:30,1')->name('projects.update');
    Route::post('/projects/{project}/asset', [ProjectController::class, 'uploadAsset'])->middleware('throttle:20,1')->name('projects.asset');
    Route::get('/projects/{project}/export', [ProjectController::class, 'export'])->name('projects.export');
    Route::post('/projects/{project}/duplicate', [ProjectController::class, 'duplicate'])->middleware('throttle:20,1')->name('projects.duplicate');
    Route::get('/affiliate', [AffiliateController::class, 'index'])->name('affiliate.index');
    Route::post('/affiliate/withdraw', [AffiliateController::class, 'withdraw'])->middleware('throttle:6,1')->name('affiliate.withdraw');
    Route::get('/checkout/{package}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/checkout/{package}', [PaymentController::class, 'purchase'])->middleware('throttle:6,1')->name('payment.purchase');
});

Route::middleware(['auth', 'approved', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::put('/admin/users/{user}/password', [AdminUserController::class, 'updatePassword'])->name('admin.users.password');
    Route::post('/admin/users/{user}/approve', [AdminUserController::class, 'approve'])->middleware('throttle:20,1')->name('admin.users.approve');
    Route::post('/admin/users/{user}/reject', [AdminUserController::class, 'reject'])->middleware('throttle:20,1')->name('admin.users.reject');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
