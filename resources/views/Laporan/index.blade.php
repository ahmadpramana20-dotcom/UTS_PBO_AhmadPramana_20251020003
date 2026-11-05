<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $judul }}</title>
    @include('laporan._gaya')
</head>
<body>
<div class="wrap">
    <h1>{{ $judul }}</h1>
    <p class="sub">Pelaporan Masalah Kampus &bull; Tugas Rumah PBO Minggu 7 (data contoh dari Controller)</p>

    {{-- Rekap singkat --}}
    <div class="rekap">
        <div class="kartu"><strong>{{ $rekap['total'] }}</strong>Total laporan</div>
        <div class="kartu"><strong>{{ $rekap['sensitif'] }}</strong>Laporan sensitif</div>
        <div class="kartu"><strong>{{ $rekap['selesai'] }}</strong>Sudah selesai</div>
    </div>

    {{-- @if: cek apakah ada data --}}
    @if (count($daftar) > 0)
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Prioritas</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            {{-- @foreach: tampilkan setiap laporan --}}
            @foreach ($daftar as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ ucfirst($item['kategori']) }}</td>
                    <td>{{ $item['lokasi'] }}</td>
                    <td><span class="badge {{ $item['status'] }}">{{ $item['status'] }}</span></td>
                    <td><span class="badge {{ $item['prioritas'] }}">{{ $item['prioritas'] }}</span></td>
                    <td><a href="{{ route('laporan.show', $item['id']) }}">Lihat detail</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="kartu kosong">Belum ada laporan yang masuk.</div>
    @endif
</div>
</body>
</html>
