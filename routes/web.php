<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/daily-reports', function () {
        return view('daily-reports');
    })->middleware('role:manager');;

    Route::get('/admin-reports', function () {
        return view('admin-reports');
    })->middleware('role:admin');

    Route::get('/news', function () {
        return view('news');
    })->middleware('role:admin');
});

require __DIR__.'/auth.php';