<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VluchtController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('vluchten.index');
});

// Vluchten
Route::get('/vluchten', [VluchtController::class, 'index'])->name('vluchten.index');
Route::get('/vluchten/zoek', [VluchtController::class, 'zoek'])->name('vluchten.zoek');
Route::get('/vluchten/{vlucht}', [VluchtController::class, 'show'])->name('vluchten.show');

// Bestaande gebruiker-authenticatie routes
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
