<?php

namespace App\Http\Controllers;

class LaporanController extends Controller
{
    /**
     * Data contoh (tanpa database), diambil dari tema tugas Pertemuan 5:
     * Mini Application OOP - Pelaporan Masalah Kampus.
     */
    private function dataLaporan(): array
    {
        return [
            [
                'id'        => 'LP-001',
                'kategori'  => 'fasilitas rusak',
                'jenis'     => 'umum',
                'lokasi'    => 'Gedung A Lantai 2',
                'waktu'     => '03-11-2026 08:15',
                'deskripsi' => 'AC ruang kelas A201 tidak berfungsi.',
                'status'    => 'diproses',
                'pelapor'   => 'Ahmad Pramana',
                'nim'       => '20251020003',
                'riwayat'   => [
                    ['status' => 'baru',     'waktu' => '08:15'],
                    ['status' => 'diproses', 'waktu' => '09:40'],
                ],
                'bukti'     => [
                    ['file' => 'ac_rusak.jpg', 'keterangan' => 'Foto AC tidak menyala'],
                ],
            ],
            [
                'id'        => 'LP-002',
                'kategori'  => 'parkiran',
                'jenis'     => 'umum',
                'lokasi'    => 'Parkiran Depan',
                'waktu'     => '03-11-2026 10:30',
                'deskripsi' => 'Motor parkir menutupi jalan keluar.',
                'status'    => 'baru',
                'pelapor'   => 'Sinta Dewi',
                'nim'       => '20251020010',
                'riwayat'   => [
                    ['status' => 'baru', 'waktu' => '10:30'],
                ],
                'bukti'     => [],
            ],
            [
                'id'        => 'LP-003',
                'kategori'  => 'pelecehan',
                'jenis'     => 'sensitif',
                'lokasi'    => 'Parkiran Belakang',
                'waktu'     => '04-11-2026 19:20',
                'deskripsi' => 'Terjadi pelecehan verbal pada malam hari.',
                'status'    => 'selesai',
                'pelapor'   => 'Sinta Dewi',
                'nim'       => '20251020010',
                'riwayat'   => [
                    ['status' => 'baru',     'waktu' => '19:20'],
                    ['status' => 'diproses', 'waktu' => '19:35'],
                    ['status' => 'selesai',  'waktu' => '21:10'],
                ],
                'bukti'     => [
                    ['file' => 'kronologi.pdf', 'keterangan' => 'Catatan kronologi kejadian'],
                    ['file' => 'lokasi.png',    'keterangan' => 'Foto lokasi kejadian'],
                ],
            ],
            [
                'id'        => 'LP-004',
                'kategori'  => 'kekerasan',
                'jenis'     => 'sensitif',
                'lokasi'    => 'Lapangan Olahraga',
                'waktu'     => '05-11-2026 07:05',
                'deskripsi' => 'Perkelahian antar mahasiswa di area lapangan.',
                'status'    => 'baru',
                'pelapor'   => 'Ahmad Pramana',
                'nim'       => '20251020003',
                'riwayat'   => [
                    ['status' => 'baru', 'waktu' => '07:05'],
                ],
                'bukti'     => [],
            ],
        ];
    }

    /**
     * Prioritas ditentukan dari jenis laporan
     * (sama seperti hitungPrioritas() pada LaporanUmum / LaporanSensitif).
     */
    private function tambahPrioritas(array $laporan): array
    {
        $laporan['prioritas'] = $laporan['jenis'] === 'sensitif' ? 'tinggi' : 'normal';

        return $laporan;
    }

    /** GET /laporan : daftar semua laporan. */
    public function index()
    {
        $daftar = array_map(
            fn ($item) => $this->tambahPrioritas($item),
            $this->dataLaporan()
        );

        $rekap = [
            'total'    => count($daftar),
            'sensitif' => count(array_filter($daftar, fn ($l) => $l['jenis'] === 'sensitif')),
            'selesai'  => count(array_filter($daftar, fn ($l) => $l['status'] === 'selesai')),
        ];

        return view('laporan.index', [
            'judul'  => 'Daftar Laporan Kampus',
            'daftar' => $daftar,
            'rekap'  => $rekap,
        ]);
    }

    /** GET /laporan/{id} : detail satu laporan (route dengan parameter). */
    public function show(string $id)
    {
        $ditemukan = null;

        foreach ($this->dataLaporan() as $item) {
            if ($item['id'] === $id) {
                $ditemukan = $this->tambahPrioritas($item);
                break;
            }
        }

        if ($ditemukan === null) {
            abort(404, "Laporan dengan ID {$id} tidak ditemukan.");
        }

        return view('laporan.show', [
            'judul'   => 'Detail Laporan ' . $id,
            'laporan' => $ditemukan,
        ]);
    }
}