<?php

use App\Http\Controllers\BoekingController;
use App\Http\Controllers\CoordinatorController;
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

// Boekingen
Route::get('/boekingen/nieuw/{vlucht}', [BoekingController::class, 'create'])->name('boekingen.create');
Route::post('/boekingen/bevestigen', [BoekingController::class, 'bevestigen'])->name('boekingen.bevestigen');
Route::post('/boekingen', [BoekingController::class, 'store'])->name('boekingen.store');
Route::get('/boekingen/{boekingsNummer}', [BoekingController::class, 'show'])->name('boekingen.show');

// Coordinator — inloggen en uitloggen
Route::get('/coordinator/inloggen', [CoordinatorController::class, 'loginForm'])->name('coordinator.login');
Route::post('/coordinator/inloggen', [CoordinatorController::class, 'login'])->name('coordinator.login.post');
Route::post('/coordinator/uitloggen', [CoordinatorController::class, 'logout'])->name('coordinator.logout');

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
