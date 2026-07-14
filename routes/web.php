<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home: reindirizza al login
Route::get('/', function () {
    return redirect()->route('login');
});

// --- Guest routes (login e registrazione) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.attempt');
});

// --- Auth routes (area protetta) ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// --- Rotta per reset password (solo utenti autenticati) ---
Route::middleware('auth')->group(function () {
    Route::get('/reset-password', [AuthController::class, 'showPasswordResetForm'])
        ->name('password.change');

    Route::post('/reset-password', [AuthController::class, 'updatePassword'])
        ->name('password.update');
});

// --- Rotte profilo utente (solo utenti autenticati) ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
