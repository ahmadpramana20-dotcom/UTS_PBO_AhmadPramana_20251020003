<style>
    :root { --teal:#0f9d8a; --navy:#14304f; --abu:#5b6b7b; --garis:#dde5ec; --bg:#f4f7fa; }
    * { box-sizing: border-box; }
    body { margin:0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background:var(--bg); color:var(--navy); }
    .wrap { max-width: 920px; margin: 0 auto; padding: 32px 20px 48px; }
    h1 { margin: 0 0 4px; font-size: 1.7rem; }
    .sub { color: var(--abu); margin: 0 0 24px; }
    .kartu { background:#fff; border:1px solid var(--garis); border-radius:10px; padding:20px; box-shadow:0 1px 3px rgba(20,48,79,.06); margin-bottom:18px; }
    .rekap { display:flex; gap:14px; flex-wrap:wrap; margin-bottom:18px; }
    .rekap .kartu { flex:1; min-width:140px; margin:0; text-align:center; }
    .rekap strong { display:block; font-size:1.8rem; color:var(--teal); }
    table { width:100%; border-collapse:collapse; background:#fff; }
    th, td { text-align:left; padding:10px 12px; border-bottom:1px solid var(--garis); font-size:.93rem; }
    th { background:#eaf1f6; }
    a { color:var(--teal); font-weight:600; text-decoration:none; }
    a:hover { text-decoration:underline; }
    .badge { display:inline-block; padding:2px 10px; border-radius:999px; font-size:.78rem; font-weight:600; }
    .baru { background:#e3eefc; color:#1d5fb4; }
    .diproses { background:#fff1dc; color:#b36b00; }
    .selesai { background:#dcf5e9; color:#177a4a; }
    .tinggi { background:#fde4e4; color:#b3261e; }
    .normal { background:#eceff2; color:#4a5967; }
    .rahasia { background:#fff7e0; border-left:4px solid #e0a800; padding:10px 14px; border-radius:6px; margin-bottom:18px; font-size:.92rem; }
    dl { display:grid; grid-template-columns: 150px 1fr; gap:8px 12px; margin:0; }
    dt { color:var(--abu); }
    dd { margin:0; font-weight:600; }
    ul { margin:6px 0 0; padding-left:20px; }
    .kosong { color:var(--abu); font-style:italic; }
    .kembali { display:inline-block; margin-bottom:16px; }
</style>
