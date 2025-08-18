<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Inventaris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        .section {
            margin-bottom: 15px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            padding-left: 5px;
            color: #333;
            border-left: 4px solid #4b5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            width: 40%;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px;
        }
        .footer p {
            margin: 3px 0;
        }
        .meta-info {
            background-color: #f9f9f9;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 11px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL INVENTARIS</h1>
        <p>{{ $inventaris->desa->nama_desa ?? 'Desa' }}</p>
        <p>Tanggal Cetak: {{ $tanggal }}</p>
    </div>

    <div class="meta-info">
        <strong>Kode Barang:</strong> {{ $inventaris->kode_barang ?? '-' }} |
        <strong>Tanggal Perolehan:</strong> {{ $inventaris->tanggal_perolehan ? \Carbon\Carbon::parse($inventaris->tanggal_perolehan)->translatedFormat('d F Y') : '-' }}
    </div>

    <div class="section">
        <div class="title">Informasi Umum</div>
        <table>
            <tr>
                <th>Nama Barang</th>
                <td>{{ $inventaris->nama_barang }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $inventaris->kategori }}</td>
            </tr>
            <tr>
                <th>Jumlah</th>
                <td>{{ $inventaris->jumlah }}</td>
            </tr>
            <tr>
                <th>Kondisi</th>
                <td>{{ $inventaris->kondisi }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="title">Detail Tambahan</div>
        <table>
            <tr>
                <th>Nominal Harga</th>
                <td>Rp {{ number_format($inventaris->nominal_harga ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Sumber Dana</th>
                <td>{{ $inventaris->sumber_dana ?? '-' }}</td>
            </tr>
            <tr>
                <th>Lokasi</th>
                <td>{{ $inventaris->lokasi ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $inventaris->status ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="title">Keterangan</div>
        <table>
            <tr>
                <td style="padding: 10px;">{{ $inventaris->keterangan ?? 'Tidak ada keterangan' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dibuat oleh: {{ $inventaris->creator->name ?? 'Admin' }}</p>
        <p>Tanggal Input: {{ $inventaris->created_at ? \Carbon\Carbon::parse($inventaris->created_at)->translatedFormat('d F Y') : '-' }}</p>
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }} - {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
    </div>
</body>
</html>
