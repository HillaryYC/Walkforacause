<?php

use App\Http\Controllers\Admin\CauseController as AdminCauseController;
use App\Http\Controllers\CauseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/causes', [CauseController::class, 'index'])->name('causes.index');
    Route::get('/causes/{cause}', [CauseController::class, 'show'])->name('causes.show');
    Route::get('/leaderboards', [CauseController::class, 'leaderboards'])->name('leaderboards.index');
    Route::post('/causes/{cause}/walks', [WalkController::class, 'store'])->name('walks.store');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('causes', AdminCauseController::class)->except(['show']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
