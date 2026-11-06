<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk #{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }} — BI-KANTIN</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 32px 16px;
            background: #f1f2f7;
            color: #171a21;
            font-family: ui-monospace, "SFMono-Regular", "Cascadia Mono", Consolas, monospace;
            font-size: 13px;
            line-height: 1.5;
        }
        .wrap { max-width: 420px; margin: 0 auto; }
        .toolbar {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 16px;
        }
        .toolbar a, .toolbar button {
            border: 1px solid #e6e8ef;
            background: #fff;
            color: #171a21;
            border-radius: 10px;
            padding: 9px 14px;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .toolbar button {
            border: 0;
            background: linear-gradient(100deg, #0602ca, #8b0bdd);
            color: #fff;
        }
        .struk {
            background: #fff;
            border-radius: 16px;
            padding: 24px 22px;
            box-shadow: 0 18px 40px -28px rgba(6, 2, 202, .45);
        }
        .brand { display: flex; align-items: center; gap: 10px; }
        .logo {
            width: 40px; height: 40px; border-radius: 10px;
            display: grid; place-items: center;
            background: linear-gradient(115deg, #0602ca, #8b0bdd);
            color: #fff; font-weight: 700; font-size: 12px;
        }
        .brand h1 { margin: 0; font: 700 17px/1.1 ui-sans-serif, system-ui, sans-serif; letter-spacing: -.02em; }
        .brand p { margin: 2px 0 0; font-size: 11px; color: #6b7280; }
        .rule { border: 0; border-top: 1px dashed #d6d9e4; margin: 16px 0; }
        .meta { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 12px; font-size: 12px; }
        .meta dt { color: #6b7280; }
        .meta dd { margin: 0; text-align: right; font-weight: 600; }
        .item { display: flex; justify-content: space-between; gap: 10px; }
        .item small { display: block; color: #6b7280; font-size: 11px; }
        .total { display: flex; justify-content: space-between; font-size: 15px; font-weight: 700; }
        .stamp {
            margin-top: 14px; display: inline-block;
            border: 2px solid #0f8a4d; color: #0f8a4d;
            border-radius: 6px; padding: 4px 10px;
            font-weight: 700; letter-spacing: .12em; font-size: 11px;
            transform: rotate(-3deg);
        }
        .note { margin-top: 16px; font-size: 11px; color: #6b7280; text-align: center; }
        .badge-status { font-size: 11px; color: #6b7280; }
        @media print {
            @page { margin: 8mm; }
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .struk { box-shadow: none; border-radius: 0; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="toolbar">
            <a href="{{ route('kasir.dashboard', ['filter' => 'semua']) }}">← Kembali</a>
            <button type="button" onclick="window.print()">Cetak struk</button>
        </div>

        <main class="struk">
            <div class="brand">
                <span class="logo">BI</span>
                <div>
                    <h1>BI-KANTIN</h1>
                    <p>Kantin SMK Bina Informatika</p>
                </div>
            </div>

            <hr class="rule">

            <dl class="meta">
                <dt>No. pesanan</dt>
                <dd>#{{ str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</dd>
                <dt>Tanggal</dt>
                <dd>{{ $pesanan->created_at->format('d/m/Y H:i') }}</dd>
                <dt>Siswa</dt>
                <dd>{{ $pesanan->user->username }}</dd>
                <dt>Kelas</dt>
                <dd>{{ $pesanan->user->kelas ?: '—' }}</dd>
                <dt>Kasir</dt>
                <dd>{{ auth()->user()->username }}</dd>
            </dl>

            <hr class="rule">

            <div class="item">
                <div>
                    {{ $pesanan->menu->nama_menu }}
                    <small>{{ $pesanan->jumlah }} × Rp{{ number_format($pesanan->menu->harga, 0, ',', '.') }}</small>
                </div>
                <strong>Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong>
            </div>

            @if($pesanan->catatan)
                <p class="badge-status">Catatan: {{ $pesanan->catatan }}</p>
            @endif

            <hr class="rule">

            <div class="total">
                <span>TOTAL</span>
                <span>Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>

            <div style="margin-top: 8px;">
                @if($pesanan->sudahLunas())
                    <span class="stamp">LUNAS</span>
                @else
                    <span class="stamp" style="border-color:#c0271a;color:#c0271a;">BELUM LUNAS</span>
                @endif
            </div>

            <p class="badge-status" style="margin-top: 10px;">Status pesanan: {{ $pesanan->status }}</p>

            <p class="note">
                Simpan struk ini sebagai bukti pengambilan pesanan.<br>
                Terima kasih sudah jajan di kantin sekolah.
            </p>
        </main>
    </div>
</body>
</html>
