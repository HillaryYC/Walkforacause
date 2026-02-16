<?php

use App\Http\Controllers\Admin\CauseController as AdminCauseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CauseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/health', function () {
    return response('OK', 200);
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified', 'no-cache'])
    ->name('dashboard');

Route::middleware(['auth', 'no-cache'])->group(function () {
    Route::get('/causes', [CauseController::class, 'index'])->name('causes.index');
    Route::get('/causes/{cause}', [CauseController::class, 'show'])->name('causes.show');
    Route::get('/leaderboards', [CauseController::class, 'leaderboards'])->name('leaderboards.index');
    Route::post('/causes/{cause}/walks', [WalkController::class, 'store'])->name('walks.store');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('causes', AdminCauseController::class)->except(['show']);

        Route::middleware('super_admin')->group(function () {
            Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update']);
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
