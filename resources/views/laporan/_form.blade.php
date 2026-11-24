{{--
    Form bersama untuk tambah dan edit.
    Variabel yang diterima:
      $action       URL tujuan form (hasil route())
      $method       'POST' untuk tambah, 'PUT' untuk edit
      $submitLabel  teks tombol kirim
      $kategoriList, $kondisiList  pilihan dropdown dari Controller
      $laporan      (opsional) data lama saat mode edit
--}}
@php
    $lama = $laporan ?? null;
    // Jika validasi gagal, pakai input terakhir pengguna; jika tidak, pakai data lama (edit) atau kosong (tambah).
    $sensitifAktif = count(old()) ? (bool) old('sensitif') : (bool) ($lama->sensitif ?? false);
@endphp

@if ($errors->any())
    <div class="alert alert-error">
        <strong>Data belum bisa disimpan, periksa kembali:</strong>
        <ul>
            @foreach ($errors->all() as $pesanError)
                <li>{{ $pesanError }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" novalidate>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="row">
        <div class="form-group">
            <label for="kode">Kode</label>
            <input type="text" id="kode" value="{{ $lama->kode ?? 'Dibuat otomatis (LP-xxx)' }}" disabled>
        </div>
        <div class="form-group">
            <label for="pelapor">Nama pelapor</label>
            <input type="text" id="pelapor" name="pelapor" value="{{ old('pelapor', $lama->pelapor ?? '') }}"
                   class="@error('pelapor') is-invalid @enderror" placeholder="Contoh: Ahmad Pramana">
            @error('pelapor') <div class="field-error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="nama">Nama laporan</label>
        <input type="text" id="nama" name="nama" value="{{ old('nama', $lama->nama ?? '') }}"
               class="@error('nama') is-invalid @enderror" placeholder="Contoh: AC ruang kelas A201 mati">
        @error('nama') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="row">
        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori" class="@error('kategori') is-invalid @enderror">
                <option value="">-- Pilih kategori --</option>
                @foreach ($kategoriList as $kategori)
                    <option value="{{ $kategori }}" @selected(old('kategori', $lama->kategori ?? '') === $kategori)>{{ $kategori }}</option>
                @endforeach
            </select>
            @error('kategori') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="lokasi">Lokasi</label>
            <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $lama->lokasi ?? '') }}"
                   class="@error('lokasi') is-invalid @enderror" placeholder="Contoh: Gedung A Lantai 2">
            @error('lokasi') <div class="field-error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="jumlah">Jumlah bukti</label>
            <input type="number" id="jumlah" name="jumlah" min="0" max="99" value="{{ old('jumlah', $lama->jumlah ?? 0) }}"
                   class="@error('jumlah') is-invalid @enderror">
            @error('jumlah') <div class="field-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
            <label for="kondisi">Kondisi</label>
            <select id="kondisi" name="kondisi" class="@error('kondisi') is-invalid @enderror">
                <option value="">-- Pilih kondisi --</option>
                @foreach ($kondisiList as $kondisi)
                    <option value="{{ $kondisi }}" @selected(old('kondisi', $lama->kondisi ?? '') === $kondisi)>{{ ucfirst($kondisi) }}</option>
                @endforeach
            </select>
            @error('kondisi') <div class="field-error">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4" class="@error('deskripsi') is-invalid @enderror"
                  placeholder="Jelaskan masalah yang dilaporkan">{{ old('deskripsi', $lama->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <div class="field-error">{{ $message }}</div> @enderror
    </div>

    <div class="form-group">
        <label class="check" for="sensitif">
            <input type="checkbox" id="sensitif" name="sensitif" value="1" @checked($sensitifAktif)>
            Laporan sensitif (prioritas tinggi, identitas pelapor dirahasiakan)
        </label>
    </div>

    <div class="actions">
        <button type="submit" class="btn">{{ $submitLabel }}</button>
        <a href="{{ route('laporan.index') }}" class="btn btn-outline">Batal</a>
    </div>
</form>
