@extends('layouts.app')

@section('title', 'Detail ' . $laporan->kode)

@section('content')
    <p><a href="{{ route('laporan.index') }}">&larr; Kembali ke daftar laporan</a></p>

    <h2>Detail Laporan {{ $laporan->kode }}</h2>
    <p class="subtitle">{{ $laporan->kategori }} &bull; {{ $laporan->lokasi }}</p>

    @if ($laporan->sensitif)
        <div class="alert alert-warning">
            <strong>Laporan sensitif.</strong> Prioritas tinggi dan identitas pelapor dirahasiakan.
        </div>
    @endif

    <div class="card">
        <dl class="detail">
            <dt>Kode</dt>
            <dd>{{ $laporan->kode }}</dd>

            <dt>Nama laporan</dt>
            <dd>{{ $laporan->nama }}</dd>

            <dt>Kategori</dt>
            <dd>{{ $laporan->kategori }}</dd>

            <dt>Lokasi</dt>
            <dd>{{ $laporan->lokasi }}</dd>

            <dt>Jumlah bukti</dt>
            <dd>{{ $laporan->jumlah }}</dd>

            <dt>Kondisi</dt>
            <dd><span class="badge badge-{{ $laporan->kondisi }}">{{ $laporan->kondisi }}</span></dd>

            <dt>Prioritas</dt>
            <dd><span class="badge badge-{{ $laporan->prioritas }}">{{ $laporan->prioritas }}</span></dd>

            <dt>Waktu lapor</dt>
            <dd>{{ $laporan->waktu }}</dd>

            <dt>Pelapor</dt>
            <dd>
                @if ($laporan->sensitif)
                    DIRAHASIAKAN
                @else
                    {{ $laporan->pelapor }}
                @endif
            </dd>

            <dt>Deskripsi</dt>
            <dd>{{ $laporan->deskripsi }}</dd>
        </dl>
    </div>

    <div class="actions">
        <a href="{{ route('laporan.edit', ['kode' => $laporan->kode]) }}" class="btn btn-warning">Edit</a>

        <form action="{{ route('laporan.destroy', ['kode' => $laporan->kode]) }}" method="POST" class="inline"
              onsubmit="return confirm('Hapus laporan {{ $laporan->kode }}? Data tidak bisa dikembalikan.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
        </form>

        <a href="{{ route('laporan.index') }}" class="btn btn-outline">Kembali</a>
    </div>
@endsection
