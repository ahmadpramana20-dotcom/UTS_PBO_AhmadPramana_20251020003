@extends('layouts.app')

@section('title', 'Tambah Laporan')

@section('content')
    <p><a href="{{ route('laporan.index') }}">&larr; Kembali ke daftar laporan</a></p>

    <h2>Tambah Laporan Baru</h2>
    <p class="subtitle">Isi semua kolom, lalu klik Simpan. Kode laporan dibuat otomatis.</p>

    <div class="card">
        @include('laporan._form', [
            'action'      => route('laporan.store'),
            'method'      => 'POST',
            'submitLabel' => 'Simpan Laporan',
        ])
    </div>
@endsection
