<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mengelola data laporan masalah kampus.
 * Data disimpan di session (SESSION_DRIVER=file), tanpa database.
 */
class LaporanController extends Controller
{
    /** Kunci session tempat seluruh data laporan disimpan. */
    private const SESSION_KEY = 'daftar_laporan';

    private const KATEGORI = [
        'Fasilitas rusak', 'Parkiran', 'Kebersihan', 'Keamanan',
        'Pelecehan', 'Kekerasan', 'Lainnya',
    ];

    private const KONDISI = ['baru', 'diproses', 'selesai'];

    /* ------------------------------------------------------------------
     |  Method utama (sesuai tabel route)
     * ------------------------------------------------------------------ */

    /** GET /laporan - ambil data dari session lalu kirim ke halaman daftar. */
    public function index(Request $request): View
    {
        $semua = collect($this->ambilSemua())->sortKeys();
        $keyword = trim((string) $request->query('q', ''));

        $daftarLaporan = $semua
            ->filter(function (array $item) use ($keyword) {
                if ($keyword === '') {
                    return true;
                }
                $gabungan = strtolower($item['kode'] . ' ' . $item['nama'] . ' ' . $item['kategori'] . ' ' . $item['lokasi']);
                return str_contains($gabungan, strtolower($keyword));
            })
            ->map(fn (array $item) => (object) $item)
            ->values();

        return view('laporan.index', [
            'daftarLaporan' => $daftarLaporan,
            'keyword'       => $keyword,
            'total'         => $semua->count(),
            'totalSensitif' => $semua->where('sensitif', true)->count(),
            'totalSelesai'  => $semua->where('kondisi', 'selesai')->count(),
        ]);
    }

    /** GET /laporan/create - tampilkan form tambah. */
    public function create(): View
    {
        return view('laporan.create', $this->opsiForm());
    }

    /** POST /laporan - validasi input, simpan ke session, lalu redirect. */
    public function store(Request $request): RedirectResponse
    {
        $input = $request->validate($this->aturan(), $this->pesan(), $this->namaAtribut());

        $data = $this->ambilSemua();
        $kode = $this->buatKodeBaru($data);

        $data[$kode] = $this->susunData(
            $kode,
            $input,
            $request->boolean('sensitif'),
            now()->format('d-m-Y H:i')
        );
        $this->simpan($data);

        return redirect()
            ->route('laporan.index')
            ->with('success', "Laporan {$kode} berhasil ditambahkan.");
    }

    /** GET /laporan/{kode} - tampilkan detail satu laporan. */
    public function show(string $kode): View|RedirectResponse
    {
        $laporan = $this->cari($kode);
        if ($laporan === null) {
            return $this->tidakDitemukan($kode);
        }

        return view('laporan.show', ['laporan' => (object) $laporan]);
    }

    /** GET /laporan/{kode}/edit - tampilkan form edit berisi nilai lama. */
    public function edit(string $kode): View|RedirectResponse
    {
        $laporan = $this->cari($kode);
        if ($laporan === null) {
            return $this->tidakDitemukan($kode);
        }

        return view('laporan.edit', $this->opsiForm() + ['laporan' => (object) $laporan]);
    }

    /** PUT/PATCH /laporan/{kode} - perbarui data di session. */
    public function update(Request $request, string $kode): RedirectResponse
    {
        $data = $this->ambilSemua();
        if (!isset($data[$kode])) {
            return $this->tidakDitemukan($kode);
        }

        $input = $request->validate($this->aturan(), $this->pesan(), $this->namaAtribut());

        // Kode dan waktu lapor tidak berubah, field lain diganti dengan input baru.
        $data[$kode] = $this->susunData(
            $kode,
            $input,
            $request->boolean('sensitif'),
            $data[$kode]['waktu']
        );
        $this->simpan($data);

        return redirect()
            ->route('laporan.index')
            ->with('success', "Laporan {$kode} berhasil diperbarui.");
    }

    /** DELETE /laporan/{kode} - hapus data dari session. */
    public function destroy(string $kode): RedirectResponse
    {
        $data = $this->ambilSemua();
        if (!isset($data[$kode])) {
            return $this->tidakDitemukan($kode);
        }

        unset($data[$kode]);
        $this->simpan($data);

        return redirect()
            ->route('laporan.index')
            ->with('success', "Laporan {$kode} berhasil dihapus.");
    }

    /* ------------------------------------------------------------------
     |  Helper privat: session
     * ------------------------------------------------------------------ */

    /** Ambil semua laporan; saat pertama kali dibuka, isi dengan data awal. */
    private function ambilSemua(): array
    {
        if (!session()->has(self::SESSION_KEY)) {
            session()->put(self::SESSION_KEY, $this->dataAwal());
        }

        return session()->get(self::SESSION_KEY);
    }

    private function simpan(array $data): void
    {
        session()->put(self::SESSION_KEY, $data);
    }

    private function cari(string $kode): ?array
    {
        return $this->ambilSemua()[$kode] ?? null;
    }

    private function tidakDitemukan(string $kode): RedirectResponse
    {
        return redirect()
            ->route('laporan.index')
            ->with('error', "Laporan dengan kode {$kode} tidak ditemukan.");
    }

    /* ------------------------------------------------------------------
     |  Helper privat: data & validasi
     * ------------------------------------------------------------------ */

    /** Kode berikutnya, misalnya LP-005 bila kode terbesar saat ini LP-004. */
    private function buatKodeBaru(array $data): string
    {
        $terbesar = 0;
        foreach (array_keys($data) as $kode) {
            $terbesar = max($terbesar, (int) substr($kode, 3));
        }

        return sprintf('LP-%03d', $terbesar + 1);
    }

    /** Bentuk satu record laporan yang seragam untuk store() dan update(). */
    private function susunData(string $kode, array $input, bool $sensitif, string $waktu): array
    {
        return [
            'kode'      => $kode,
            'nama'      => $input['nama'],
            'kategori'  => $input['kategori'],
            'lokasi'    => $input['lokasi'],
            'jumlah'    => (int) $input['jumlah'],
            'kondisi'   => $input['kondisi'],
            'pelapor'   => $input['pelapor'],
            'deskripsi' => $input['deskripsi'],
            'sensitif'  => $sensitif,
            'prioritas' => $sensitif ? 'tinggi' : 'normal',
            'waktu'     => $waktu,
        ];
    }

    private function opsiForm(): array
    {
        return [
            'kategoriList' => self::KATEGORI,
            'kondisiList'  => self::KONDISI,
        ];
    }

    private function aturan(): array
    {
        return [
            'nama'      => ['required', 'string', 'max:100'],
            'kategori'  => ['required', 'in:' . implode(',', self::KATEGORI)],
            'lokasi'    => ['required', 'string', 'max:100'],
            'jumlah'    => ['required', 'integer', 'min:0', 'max:99'],
            'kondisi'   => ['required', 'in:' . implode(',', self::KONDISI)],
            'pelapor'   => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'max:500'],
            'sensitif'  => ['nullable', 'boolean'],
        ];
    }

    private function pesan(): array
    {
        return [
            'required'    => ':attribute wajib diisi.',
            'integer'     => ':attribute harus berupa angka bulat.',
            'in'          => ':attribute yang dipilih tidak valid.',
            'min.numeric' => ':attribute tidak boleh kurang dari :min.',
            'max.numeric' => ':attribute tidak boleh lebih dari :max.',
            'max.string'  => ':attribute maksimal :max karakter.',
        ];
    }

    private function namaAtribut(): array
    {
        return [
            'nama'      => 'Nama laporan',
            'kategori'  => 'Kategori',
            'lokasi'    => 'Lokasi',
            'jumlah'    => 'Jumlah bukti',
            'kondisi'   => 'Kondisi',
            'pelapor'   => 'Nama pelapor',
            'deskripsi' => 'Deskripsi',
        ];
    }

    /** Data contoh awal (melanjutkan LP-001 s.d. LP-004 dari tugas Minggu 7). */
    private function dataAwal(): array
    {
        return [
            'LP-001' => [
                'kode' => 'LP-001', 'nama' => 'AC ruang kelas A201 mati', 'kategori' => 'Fasilitas rusak',
                'lokasi' => 'Gedung A Lantai 2', 'jumlah' => 1, 'kondisi' => 'diproses',
                'pelapor' => 'Ahmad Pramana', 'deskripsi' => 'AC ruang kelas A201 tidak berfungsi.',
                'sensitif' => false, 'prioritas' => 'normal', 'waktu' => '03-11-2026 08:15',
            ],
            'LP-002' => [
                'kode' => 'LP-002', 'nama' => 'Motor parkir menghalangi jalur keluar', 'kategori' => 'Parkiran',
                'lokasi' => 'Parkiran Depan', 'jumlah' => 2, 'kondisi' => 'baru',
                'pelapor' => 'Budi Santoso', 'deskripsi' => 'Beberapa motor parkir di jalur keluar sehingga jalan tersendat.',
                'sensitif' => false, 'prioritas' => 'normal', 'waktu' => '04-11-2026 10:05',
            ],
            'LP-003' => [
                'kode' => 'LP-003', 'nama' => 'Pelecehan verbal pada malam hari', 'kategori' => 'Pelecehan',
                'lokasi' => 'Parkiran Belakang', 'jumlah' => 2, 'kondisi' => 'selesai',
                'pelapor' => 'Siti Aisyah', 'deskripsi' => 'Terjadi pelecehan verbal pada malam hari.',
                'sensitif' => true, 'prioritas' => 'tinggi', 'waktu' => '04-11-2026 19:20',
            ],
            'LP-004' => [
                'kode' => 'LP-004', 'nama' => 'Perkelahian di lapangan olahraga', 'kategori' => 'Kekerasan',
                'lokasi' => 'Lapangan Olahraga', 'jumlah' => 1, 'kondisi' => 'baru',
                'pelapor' => 'Rina Marlina', 'deskripsi' => 'Terjadi perkelahian antar mahasiswa sore hari.',
                'sensitif' => true, 'prioritas' => 'tinggi', 'waktu' => '05-11-2026 16:40',
            ],
        ];
    }
}
