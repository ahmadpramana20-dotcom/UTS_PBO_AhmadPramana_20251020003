@extends('layouts.app')

@section('title', 'Daftar Laporan')

@section('content')
    <h2>Daftar Laporan Kampus</h2>
    <p class="subtitle">Data laporan disimpan sementara di session (tanpa database).</p>

    {{-- Rekap singkat --}}
    <div class="stats">
        <div class="stat"><strong>{{ $total }}</strong><span>Total laporan</span></div>
        <div class="stat"><strong>{{ $totalSensitif }}</strong><span>Laporan sensitif</span></div>
        <div class="stat"><strong>{{ $totalSelesai }}</strong><span>Sudah selesai</span></div>
    </div>

    <div class="card">
        {{-- Pencarian sederhana + tombol tambah --}}
        <div class="toolbar">
            <form action="{{ route('laporan.index') }}" method="GET">
                <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari kode, nama, kategori, lokasi...">
                <button type="submit" class="btn btn-outline">Cari</button>
                @if ($keyword !== '')
                    <a href="{{ route('laporan.index') }}" class="btn btn-outline">Reset</a>
                @endif
            </form>
            <a href="{{ route('laporan.create') }}" class="btn">+ Tambah Laporan</a>
        </div>

        @if ($daftarLaporan->isEmpty())
            <div class="empty">
                @if ($keyword !== '')
                    Tidak ada laporan yang cocok dengan pencarian "{{ $keyword }}".
                @else
                    Belum ada data laporan. Klik tombol <strong>Tambah Laporan</strong> untuk membuat yang pertama.
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Laporan</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Jumlah (Bukti)</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarLaporan as $item)
                            <tr>
                                <td><strong>{{ $item->kode }}</strong></td>
                                <td>
                                    {{ $item->nama }}
                                    @if ($item->sensitif)
                                        <span class="badge badge-tinggi">Sensitif</span>
                                    @endif
                                </td>
                                <td>{{ $item->kategori }}</td>
                                <td>{{ $item->lokasi }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td><span class="badge badge-{{ $item->kondisi }}">{{ $item->kondisi }}</span></td>
                                <td class="aksi">
                                    <a href="{{ route('laporan.show', ['kode' => $item->kode]) }}" class="btn btn-outline btn-sm">Detail</a>
                                    <a href="{{ route('laporan.edit', ['kode' => $item->kode]) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('laporan.destroy', ['kode' => $item->kode]) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus laporan {{ $item->kode }}? Data tidak bisa dikembalikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
