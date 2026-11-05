<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\BukuController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/buku');

// Route::get('/buku', [BukuController::class, 'index'])
// ->name('buku.index');

// Route::get('/buku/{id}', [BukuController::class, 'show'])
// ->name('buku.show');
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

Route::get('/laporan/{id}', [LaporanController::class, 'show'])
    ->where('id', 'LP-[0-9]{3}')
    ->name('laporan.show');
