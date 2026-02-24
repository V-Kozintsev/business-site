<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\NewsController;
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

// 📊 ВИДЫ СТРАНИЦ
Route::middleware('auth')->group(function () {
    Route::get('/daily-reports', function () {
        return view('daily-reports');
    })->middleware('role:manager|admin')->name('daily-reports');

    Route::get('/admin-reports', function () {
        return view('admin-reports');
    })->middleware('role:admin')->name('admin-reports');

    Route::get('/news', function () {
        return view('news');
    })->name('news');
});

// API (твои блоки остаются как есть — они рабочие)
Route::middleware(['auth', 'role:manager'])->prefix('api')->group(function () {
    Route::get('/reports', [ReportController::class, 'index']);
    Route::post('/reports', [ReportController::class, 'store']);
    Route::put('/reports/{report}', [ReportController::class, 'update']);
    Route::delete('/reports/{report}', [ReportController::class, 'destroy']);
});

Route::middleware(['auth', 'role:admin'])->prefix('api')->group(function () {
    Route::get('/reports', [ReportController::class, 'index']);
});

Route::middleware('auth')->prefix('api')->group(function () {
    Route::apiResource('reports', ReportController::class);
    Route::apiResource('news', NewsController::class);
});

Route::middleware(['auth', 'role:admin'])->prefix('api')->group(function () {
    Route::put('/news/{news}', [NewsController::class, 'update']);
    Route::delete('/news/{news}', [NewsController::class, 'destroy']);
});

require __DIR__.'/auth.php';
