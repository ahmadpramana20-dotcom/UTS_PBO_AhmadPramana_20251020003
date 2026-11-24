<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laporan Kampus') - Pelaporan Masalah Kampus</title>
    <style>
        :root {
            --teal: #0f766e; --teal-dark: #115e59; --bg: #f1f5f9; --card: #ffffff;
            --text: #1e293b; --muted: #64748b; --line: #e2e8f0;
            --red: #dc2626; --amber: #b45309; --green: #15803d; --blue: #1d4ed8;
        }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", system-ui, sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; min-height: 100vh; display: flex; flex-direction: column; }
        a { color: var(--teal); text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* Header & navigasi */
        .site-header { background: var(--teal); color: #fff; }
        .site-header .inner, .site-nav .inner, .container, .site-footer .inner { max-width: 1000px; margin: 0 auto; padding: 0 20px; }
        .site-header .inner { padding-top: 20px; padding-bottom: 16px; }
        .site-header h1 { margin: 0; font-size: 1.5rem; }
        .site-header p { margin: 2px 0 0; opacity: .85; font-size: .9rem; }
        .site-nav { background: var(--teal-dark); }
        .site-nav .inner { display: flex; gap: 4px; }
        .site-nav a { color: #fff; padding: 10px 14px; font-size: .9rem; display: inline-block; }
        .site-nav a.active { background: rgba(255,255,255,.18); }
        .site-nav a:hover { background: rgba(255,255,255,.12); text-decoration: none; }

        /* Konten */
        .container { flex: 1; width: 100%; padding-top: 24px; padding-bottom: 32px; }
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 10px; padding: 20px; margin-bottom: 16px; }
        h2 { margin: 0 0 4px; font-size: 1.3rem; }
        .subtitle { color: var(--muted); font-size: .9rem; margin: 0 0 16px; }

        /* Pesan status */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: .92rem; border: 1px solid; }
        .alert-success { background: #f0fdf4; border-color: #86efac; color: #166534; }
        .alert-error   { background: #fef2f2; border-color: #fca5a5; color: #991b1b; }
        .alert-info    { background: #eff6ff; border-color: #93c5fd; color: #1e40af; }
        .alert-warning { background: #fffbeb; border-color: #fcd34d; color: #92400e; }
        .alert ul { margin: 6px 0 0; padding-left: 20px; }

        /* Tombol */
        .btn { display: inline-block; padding: 7px 14px; border-radius: 6px; border: 1px solid transparent; font-size: .85rem; cursor: pointer; font-family: inherit; background: var(--teal); color: #fff; }
        .btn:hover { opacity: .9; text-decoration: none; }
        .btn-outline { background: #fff; color: var(--teal); border-color: var(--teal); }
        .btn-warning { background: #f59e0b; color: #fff; }
        .btn-danger  { background: var(--red); color: #fff; }
        .btn-sm { padding: 4px 10px; font-size: .8rem; }
        .inline { display: inline; }

        /* Rekap & toolbar */
        .stats { display: flex; gap: 12px; margin-bottom: 16px; flex-wrap: wrap; }
        .stat { flex: 1; min-width: 140px; background: var(--card); border: 1px solid var(--line); border-radius: 10px; padding: 14px; text-align: center; }
        .stat strong { display: block; font-size: 1.6rem; color: var(--teal); }
        .stat span { font-size: .8rem; color: var(--muted); }
        .toolbar { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
        .toolbar form { display: flex; gap: 6px; }

        /* Tabel */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: .9rem; }
        th, td { text-align: left; padding: 10px 12px; border-bottom: 1px solid var(--line); vertical-align: middle; }
        th { background: #f8fafc; font-size: .8rem; color: var(--muted); text-transform: uppercase; letter-spacing: .03em; }
        td.aksi { white-space: nowrap; }

        /* Badge */
        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: .75rem; font-weight: 600; }
        .badge-baru { background: #dbeafe; color: var(--blue); }
        .badge-diproses { background: #fef3c7; color: var(--amber); }
        .badge-selesai { background: #dcfce7; color: var(--green); }
        .badge-tinggi { background: #fee2e2; color: var(--red); }
        .badge-normal { background: #f1f5f9; color: var(--muted); }

        /* Form */
        .form-group { margin-bottom: 14px; }
        label { display: block; font-weight: 600; font-size: .88rem; margin-bottom: 4px; }
        input[type=text], input[type=number], select, textarea { width: 100%; padding: 9px 11px; border: 1px solid #cbd5e1; border-radius: 6px; font: inherit; background: #fff; }
        input:focus, select:focus, textarea:focus { outline: 2px solid rgba(15,118,110,.35); border-color: var(--teal); }
        .is-invalid { border-color: var(--red) !important; }
        .field-error { color: var(--red); font-size: .8rem; margin-top: 3px; }
        .row { display: flex; gap: 14px; flex-wrap: wrap; }
        .row > .form-group { flex: 1; min-width: 200px; }
        .check { display: flex; align-items: center; gap: 8px; font-weight: 400; }
        .check input { width: auto; }
        .actions { display: flex; gap: 8px; margin-top: 8px; }

        /* Detail */
        dl.detail { display: grid; grid-template-columns: 160px 1fr; gap: 10px 16px; margin: 0; }
        dl.detail dt { color: var(--muted); font-size: .88rem; }
        dl.detail dd { margin: 0; font-weight: 600; }
        .empty { text-align: center; color: var(--muted); padding: 32px 12px; }

        /* Footer */
        .site-footer { background: #fff; border-top: 1px solid var(--line); color: var(--muted); font-size: .82rem; }
        .site-footer .inner { padding-top: 14px; padding-bottom: 14px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 6px; }
    </style>
</head>
<body>

    {{-- Area judul --}}
    <header class="site-header">
        <div class="inner">
            <h1>Pelaporan Masalah Kampus</h1>
            <p>UTS Pemrograman Berorientasi Objek &bull; Laravel Route, Controller, Blade</p>
        </div>
    </header>

    {{-- Navigasi --}}
    <nav class="site-nav">
        <div class="inner">
            <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.index') ? 'active' : '' }}">Daftar Laporan</a>
            <a href="{{ route('laporan.create') }}" class="{{ request()->routeIs('laporan.create') ? 'active' : '' }}">Tambah Laporan</a>
        </div>
    </nav>

    {{-- Area konten --}}
    <main class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="site-footer">
        <div class="inner">
            <span>UTS PBO T.A. 2026/2027 &bull; Universitas Catur Insan Cendekia</span>
            <span>Ahmad Pramana &bull; NIM 20251020003</span>
        </div>
    </footer>

</body>
</html>
