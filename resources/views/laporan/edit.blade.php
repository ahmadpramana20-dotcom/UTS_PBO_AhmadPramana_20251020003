@extends('layouts.app')

@section('title', 'Edit ' . $laporan->kode)

@section('content')
    <p><a href="{{ route('laporan.index') }}">&larr; Kembali ke daftar laporan</a></p>

    <h2>Edit Laporan {{ $laporan->kode }}</h2>
    <p class="subtitle">Nilai awal diambil dari data yang tersimpan. Ubah yang perlu, lalu simpan.</p>

    <div class="card">
        @include('laporan._form', [
            'action'      => route('laporan.update', ['kode' => $laporan->kode]),
            'method'      => 'PUT',
            'submitLabel' => 'Simpan Perubahan',
        ])
    </div>
@endsection
