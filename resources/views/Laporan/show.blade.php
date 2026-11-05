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
    <a class="kembali" href="{{ route('laporan.index') }}">&larr; Kembali ke daftar laporan</a>

    <h1>{{ $judul }}</h1>
    <p class="sub">{{ ucfirst($laporan['kategori']) }} &bull; {{ $laporan['lokasi'] }}</p>

    {{-- @if: laporan sensitif menampilkan peringatan kerahasiaan --}}
    @if ($laporan['jenis'] === 'sensitif')
        <div class="rahasia">
            <strong>Laporan sensitif.</strong> Prioritas tinggi dan identitas pelapor dirahasiakan.
        </div>
    @endif

    <div class="kartu">
        <dl>
            <dt>ID Laporan</dt>
            <dd>{{ $laporan['id'] }}</dd>

            <dt>Waktu</dt>
            <dd>{{ $laporan['waktu'] }}</dd>

            <dt>Status</dt>
            <dd><span class="badge {{ $laporan['status'] }}">{{ $laporan['status'] }}</span></dd>

            <dt>Prioritas</dt>
            <dd><span class="badge {{ $laporan['prioritas'] }}">{{ $laporan['prioritas'] }}</span></dd>

            <dt>Pelapor</dt>
            <dd>
                {{-- @if: sama seperti override getInfoPelapor() di LaporanSensitif --}}
                @if ($laporan['jenis'] === 'sensitif')
                    DIRAHASIAKAN
                @else
                    {{ $laporan['pelapor'] }} ({{ $laporan['nim'] }})
                @endif
            </dd>

            <dt>Deskripsi</dt>
            <dd>{{ $laporan['deskripsi'] }}</dd>
        </dl>
    </div>

    <div class="kartu">
        <h3 style="margin-top:0">Riwayat Status</h3>
        {{-- @foreach: perulangan riwayat status --}}
        <ul>
            @foreach ($laporan['riwayat'] as $r)
                <li>{{ $r['waktu'] }} &mdash; {{ $r['status'] }}</li>
            @endforeach
        </ul>
    </div>

    <div class="kartu">
        <h3 style="margin-top:0">Bukti Lampiran</h3>
        {{-- @if: cek apakah laporan punya bukti --}}
        @if (count($laporan['bukti']) > 0)
            <ul>
                @foreach ($laporan['bukti'] as $b)
                    <li><strong>{{ $b['file'] }}</strong> &ndash; {{ $b['keterangan'] }}</li>
                @endforeach
            </ul>
        @else
            <p class="kosong">Belum ada bukti yang dilampirkan.</p>
        @endif
    </div>
</div>
</body>
</html>
