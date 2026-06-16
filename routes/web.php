<?php

use App\Http\Controllers\BoekingController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\DirecteurController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VluchtController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// ─── Vluchten (public) ────────────────────────────────────────────────────────

Route::get('/vluchten', [VluchtController::class, 'index'])->name('vluchten.index');
Route::get('/vluchten/zoek', [VluchtController::class, 'zoek'])->name('vluchten.zoek');
Route::get('/vluchten/{vlucht}', [VluchtController::class, 'show'])->name('vluchten.show');

// ─── Boekingen (public) ───────────────────────────────────────────────────────

Route::get('/boekingen/nieuw/{vlucht}', [BoekingController::class, 'create'])->name('boekingen.create');
Route::post('/boekingen/bevestigen', [BoekingController::class, 'bevestigen'])->name('boekingen.bevestigen');
Route::post('/boekingen/betalen', [BoekingController::class, 'betalen'])->name('boekingen.betalen');
Route::post('/boekingen', [BoekingController::class, 'store'])->name('boekingen.store');
Route::get('/boekingen/{boekingsNummer}', [BoekingController::class, 'show'])->name('boekingen.show');

// ─── Coordinator portaal ──────────────────────────────────────────────────────

Route::get('/coordinator/inloggen', [CoordinatorController::class, 'loginForm'])->name('coordinator.login');
Route::post('/coordinator/inloggen', [CoordinatorController::class, 'login'])->name('coordinator.login.post');
Route::post('/coordinator/uitloggen', [CoordinatorController::class, 'logout'])->name('coordinator.logout');

Route::middleware('coordinator')->group(function () {
    Route::get('/coordinator/dashboard', [CoordinatorController::class, 'dashboard'])->name('coordinator.dashboard');

    // Vluchten beheer
    Route::get('/coordinator/vluchten', [CoordinatorController::class, 'vluchtenIndex']);
    Route::get('/coordinator/vluchten/toevoegen', [CoordinatorController::class, 'vluchtenCreate']);
    Route::post('/coordinator/vluchten', [CoordinatorController::class, 'vluchtenStore']);
    Route::get('/coordinator/vluchten/{vlucht}/wijzigen', [CoordinatorController::class, 'vluchtenEdit']);
    Route::put('/coordinator/vluchten/{vlucht}', [CoordinatorController::class, 'vluchtenUpdate']);
    Route::delete('/coordinator/vluchten/{vlucht}', [CoordinatorController::class, 'vluchtenDelete']);

    // Gates beheer
    Route::get('/coordinator/gates', [CoordinatorController::class, 'gatesIndex']);
    Route::post('/coordinator/gates', [CoordinatorController::class, 'gatesStore']);
    Route::post('/coordinator/gates/{gate}/toewijzen', [CoordinatorController::class, 'gatesToewijzen']);

    // Maatschappijen beheer
    Route::get('/coordinator/maatschappijen', [CoordinatorController::class, 'maatschappijenIndex']);
    Route::post('/coordinator/maatschappijen', [CoordinatorController::class, 'maatschappijenStore']);
    Route::put('/coordinator/maatschappijen/{maatschappij}', [CoordinatorController::class, 'maatschappijenUpdate']);
    Route::delete('/coordinator/maatschappijen/{maatschappij}', [CoordinatorController::class, 'maatschappijenDelete']);

    // Verlanglijst
    Route::get('/coordinator/verlanglijst', [CoordinatorController::class, 'verlanglijstIndex']);
    Route::post('/coordinator/verlanglijst', [CoordinatorController::class, 'verlanglijstStore']);
    Route::delete('/coordinator/verlanglijst/{verlanglijst}', [CoordinatorController::class, 'verlanglijstDelete']);
});

// ─── Directeur portaal ────────────────────────────────────────────────────────

Route::get('/directeur/inloggen', [DirecteurController::class, 'loginForm']);
Route::post('/directeur/inloggen', [DirecteurController::class, 'login']);
Route::post('/directeur/uitloggen', [DirecteurController::class, 'logout']);

Route::middleware('directeur')->group(function () {
    Route::get('/directeur/dashboard', [DirecteurController::class, 'dashboard']);
    Route::get('/directeur/boekingen', [DirecteurController::class, 'boekingen']);
    Route::get('/directeur/coordinatoren', [DirecteurController::class, 'coordinatoren']);
    Route::get('/directeur/coordinatoren/toevoegen', [DirecteurController::class, 'coordinatorCreate']);
    Route::post('/directeur/coordinatoren', [DirecteurController::class, 'coordinatorStore']);
    Route::get('/directeur/coordinatoren/{coordinator}/wijzigen', [DirecteurController::class, 'coordinatorEdit']);
    Route::put('/directeur/coordinatoren/{coordinator}', [DirecteurController::class, 'coordinatorUpdate']);
    Route::delete('/directeur/coordinatoren/{coordinator}', [DirecteurController::class, 'coordinatorDelete']);
});

// ─── Gebruiker (Laravel Breeze) ───────────────────────────────────────────────

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
