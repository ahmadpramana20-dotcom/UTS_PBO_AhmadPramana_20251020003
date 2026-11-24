# Tugas Rumah PBO Minggu 7 - Halaman Laporan (Laravel)

**Nama:** Ahmad Pramana  **NIM:** 20251020003

Halaman baru **Laporan Kampus** ini melanjutkan tema tugas Pertemuan 5
(Mini Application OOP - Pelaporan Masalah Kampus). Data memakai array contoh di Controller (tanpa database).

## Halaman yang ditambahkan
| URL | Fungsi |
|-----|--------|
| `/laporan` | Daftar semua laporan + rekap singkat |
| `/laporan/{id}` | Detail satu laporan, contoh `/laporan/LP-001` |

## Kesesuaian dengan ketentuan tugas
| Ketentuan | Pemenuhan |
|-----------|-----------|
| Tambahkan satu halaman | Halaman Laporan (`/laporan`) |
| Minimal satu route dengan parameter | `/laporan/{id}` dengan batasan format `LP-001` |
| Controller menyiapkan data | `LaporanController` (`dataLaporan()`, `index()`, `show()`) |
| Blade `@if` | Cek data kosong, laporan sensitif, pelapor dirahasiakan, bukti lampiran |
| Blade `@foreach` | Daftar laporan, riwayat status, bukti lampiran |

## Alur Route -> Controller -> Blade
1. **Route** (`routes/web.php`) menerima URL `/laporan` atau `/laporan/{id}`.
2. **Controller** (`LaporanController`) menyiapkan data contoh, menghitung prioritas
   (sensitif = tinggi, umum = normal), lalu mengirimnya lewat `view()`.
   Pada `show()`, ID yang tidak ditemukan menghasilkan `abort(404)`.
3. **Blade** (`resources/views/laporan/`) menampilkan data dengan `@if` dan `@foreach`.

Aturan OOP Pertemuan 5 ikut terbawa: laporan sensitif otomatis berprioritas tinggi dan identitas pelapornya
tampil **DIRAHASIAKAN** (padanan override `getInfoPelapor()` pada `LaporanSensitif`).

## File yang ditambahkan
```
app/Http/Controllers/LaporanController.php
resources/views/laporan/_gaya.blade.php
resources/views/laporan/index.blade.php
resources/views/laporan/show.blade.php
routes/web.php   (ditambah 2 route)
```

## Cara menjalankan
```
php artisan serve
```
Lalu buka `http://127.0.0.1:8000/laporan` dan `http://127.0.0.1:8000/laporan/LP-001`.

## Screenshot hasil
- Halaman daftar: `docs/laporan-index.png`
- Halaman detail umum (LP-001): `docs/laporan-detail-umum.png`
- Halaman detail sensitif (LP-003): `docs/laporan-detail-sensitif.png`
