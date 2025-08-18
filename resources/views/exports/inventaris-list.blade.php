<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Inventaris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
        .filter-info {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .filter-info div {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .summary {
            border-top: 1px solid #ddd;
            margin-top: 8px;
            padding-top: 8px;
        }
        .summary-title {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR INVENTARIS</h1>
        <p>Tanggal Cetak: {{ $tanggal }}</p>
    </div>

    <div class="filter-info">
        <div>
            <p><strong>Filter:</strong></p>
            <p>Kategori: {{ $filter['kategori'] ?? 'Semua Kategori' }}</p>
        </div>
        <div>
            @if($filter['dari_tanggal'] && $filter['sampai_tanggal'])
                <p>Periode: {{ $filter['dari_tanggal'] }} - {{ $filter['sampai_tanggal'] }}</p>
            @elseif($filter['dari_tanggal'])
                <p>Dari Tanggal: {{ $filter['dari_tanggal'] }}</p>
            @elseif($filter['sampai_tanggal'])
                <p>Sampai Tanggal: {{ $filter['sampai_tanggal'] }}</p>
            @else
                <p>Periode: Semua Waktu</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="13%">Nama Desa</th>
                <th width="15%">Nama Barang</th>
                <th width="8%">Kode</th>
                <th width="8%">Kategori</th>
                <th width="4%">Jumlah</th>
                <th width="10%">Tanggal Perolehan</th>
                <th width="8%">Kondisi</th>
                <th width="10%">Nominal Harga</th>
                <th width="8%">Sumber Dana</th>
                <th width="13%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @if(count($inventaris) > 0)
                @foreach($inventaris as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->desa->nama_desa ?? 'N/A' }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->kode_barang ?? '-' }}</td>
                        <td>{{ $item->kategori }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->tanggal_perolehan ? \Carbon\Carbon::parse($item->tanggal_perolehan)->translatedFormat('d F Y') : '-' }}</td>
                        <td>{{ $item->kondisi }}</td>
                        <td>Rp {{ number_format($item->nominal_harga, 0, ',', '.') }}</td>
                        <td>{{ $item->sumber_dana ?? '-' }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11" style="text-align: center;">Tidak ada data inventaris</td>
                </tr>
            @endif
        </tbody>
    </table>

    @if(isset($totalNilai) || isset($totalUnit))
    <div class="summary">
        <table>
            <tr>
                <td class="summary-title" width="85%">Total Nilai Inventaris:</td>
                <td>Rp {{ number_format($totalNilai ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="summary-title" width="85%">Total Unit/Barang:</td>
                <td>{{ number_format($totalUnit ?? 0, 0, ',', '.') }} unit</td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        <div>
            <p>Catatan: Dokumen ini dicetak dalam orientasi landscape.</p>
        </div>
        <div style="text-align: right;">
            <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
            <p>Tanggal: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
