<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

// guest
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

// logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// dashboard (login wajib)
Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth')->name('dashboard');

// Register
Route::prefix('auth')->group(function () {
    Route::livewire('/register', 'pages::auth.register')->middleware('auth')->name('register');
    Route::livewire('/register-list', 'pages::auth.register-list')->middleware('auth')->name('register-list');
    Route::livewire('/register/{id}/edit', 'pages::auth.register-edit')->name('register-edit');
});

// Barang
Route::prefix('master')->middleware('auth')->group(function () {
    Route::livewire('/barang', 'pages::master.barang-list')->name('barang-list');
    Route::livewire('/barang/create', 'pages::master.barang-create')->name('barang-create');
    Route::livewire('/barang/{id}/edit', 'pages::master.barang-edit')->name('barang-edit');
});
