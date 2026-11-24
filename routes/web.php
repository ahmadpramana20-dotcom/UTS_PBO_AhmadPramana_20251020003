<?php

use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - UTS PBO (Studi kasus: Pelaporan Masalah Kampus)
|--------------------------------------------------------------------------
| Seluruh route mengarah ke LaporanController (pola Route -> Controller -> Blade).
| URI statis (/laporan/create) sengaja ditulis SEBELUM URI dinamis (/laporan/{kode})
| supaya kata "create" tidak tertangkap sebagai nilai parameter {kode}.
*/

// Halaman awal diarahkan ke daftar laporan
Route::redirect('/', '/laporan');

// 1. Daftar laporan
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

// 2. Form tambah (statis, harus sebelum /{kode})
Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');

// 3. Simpan data baru
Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');

// 4. Detail satu laporan
Route::get('/laporan/{kode}', [LaporanController::class, 'show'])
    ->where('kode', 'LP-[0-9]{3}')
    ->name('laporan.show');

// 5. Form edit
Route::get('/laporan/{kode}/edit', [LaporanController::class, 'edit'])
    ->where('kode', 'LP-[0-9]{3}')
    ->name('laporan.edit');

// 6. Perbarui data (PUT/PATCH)
Route::match(['put', 'patch'], '/laporan/{kode}', [LaporanController::class, 'update'])
    ->where('kode', 'LP-[0-9]{3}')
    ->name('laporan.update');

// 7. Hapus data
Route::delete('/laporan/{kode}', [LaporanController::class, 'destroy'])
    ->where('kode', 'LP-[0-9]{3}')
    ->name('laporan.destroy');
