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
Route::get('/dashboard', function () {return view('dashboard.index');})->middleware('auth')->name('dashboard');

// Register
Route::prefix('auth')->middleware('auth')->group(function () {
    Route::livewire('/register', 'pages::auth.register')->name('auth.register.create');
    Route::livewire('/register-list', 'pages::auth.register-list')->name('auth.register.list');
    Route::livewire('/register/{id}/edit', 'pages::auth.register-edit')->name('auth.register.edit');
    Route::livewire('/permission-matrix', 'pages::auth.permission-matrix')->name('auth.permission.matrix');
});

// Barang
Route::prefix('master')->middleware('auth')->group(function () {
    Route::livewire('/barang', 'pages::master.barang-list')->name('master.barang.list');
    Route::livewire('/barang/create', 'pages::master.barang-create')->name('master.barang.create');
    Route::livewire('/barang/{id}/edit', 'pages::master.barang-edit')->name('master.barang.edit');
});

//
Route::prefix('transaksi')->middleware('auth')->group(function () {
    Route::livewire('/', 'pages::transaksi.transaksi-list')->name('transaksi.penjualan.list');
    Route::livewire('/create', 'pages::transaksi.transaksi-create')->name('transaksi.penjualan.create');
    Route::livewire('/{id}', 'pages::transaksi.transaksi-show')->name('transaksi.penjualan.show');
    Route::livewire('/{id}/edit', 'pages::transaksi.transaksi-edit')->name('transaksi.penjualan.edit');
});

Route::prefix('menu')->middleware('auth')->group(function () {
    Route::livewire('/','pages::master.menu-list')->name('master.menu.list');
    Route::livewire('/create', 'pages::master.menu-create')->name('master.menu.create');
    Route::livewire('/{menu}/edit', 'pages::master.menu-edit')->name('master.menu.edit');
});

Route::prefix('system')->name('system.')->group(function () {
    Route::livewire('/roles', 'pages::system.role-list')->name('role.list');
});

Route::prefix('system')->middleware('auth')->name('system.')->group(function () {
    Route::livewire('/', 'pages::system.system-management')->name('index');
});

// php artisan make:livewire pages::master.MenuEdit --mfc
// gpt-5.6-luna, gpt-5.6-terra, gpt-5.6-sol, gpt-5-mini, grok-4.3, DeepSeek-V4-Pro, DeepSeek-V4-Flash
// php artisan route:sync
// php artisan framework:permission-sync
// memakai permisison scanner
// protected array $additionalPermissions = [
//     'system.role.delete',
//     'system.role.assign',
// ];
// php artisan make:model Permission -m
// | Action    | Arti                  |
// | --------- | --------------------- |
// | list      | Daftar                |
// | show      | Detail                |
// | create    | Form tambah           |
// | store     | Simpan                |
// | edit      | Form ubah             |
// | update    | Proses update         |
// | delete    | Hapus                 |
// | destroy   | Proses hapus permanen |
// | print     | Cetak                 |
// | export    | Export                |
// | import    | Import                |
// | approve   | Approve               |
// | reject    | Tolak                 |
// | cancel    | Batal                 |
// | duplicate | Salin                 |
// | restore   | Restore               |
