<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kartu Keluarga</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 15px;
            color: #333;
            font-size: 10px;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 16px;
        }
        .subtitle {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 12px;
        }
        .header {
            margin-bottom: 15px;
            border-bottom: 1px solid #999;
            padding-bottom: 10px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #999;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .filter-info {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }
        .filter-info-item {
            margin-right: 15px;
            display: inline-block;
        }
        .stats-section {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        .stats-box {
            width: 31%;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px;
            background-color: #f9f9f9;
        }
        .stats-box h3 {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: #333;
        }
        .stats-table {
            width: 100%;
            font-size: 9px;
            border-collapse: collapse;
        }
        .stats-table td {
            padding: 3px;
            border: none;
        }
        .stats-table td:last-child {
            text-align: right;
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #999;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR KARTU KELUARGA</h1>
        <p class="subtitle">{{ $filter['desa'] ?? 'Semua Desa' }}</p>

        @if(isset($filter['periode']))
        <div class="filter-info">
            <div>
                <span class="filter-info-item">
                    <strong>Periode: </strong> {{ $filter['periode'] }}
                </span>
            </div>
        </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Nomor KK</th>
                <th width="15%">Kepala Keluarga</th>
                <th width="10%">NIK</th>
                <th width="4%">JK</th>
                <th width="15%">Alamat</th>
                <th width="6%">RT/RW</th>
                <th width="10%">Desa/Kelurahan</th>
                <th width="10%">Kecamatan</th>
                <th width="5%">Anggota</th>
                <th width="12%">Kontak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kartuKeluarga as $index => $kk)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $kk['kepala']->kk }}</td>
                <td>{{ $kk['kepala']->nama }}</td>
                <td>{{ $kk['kepala']->nik }}</td>
                <td>{{ $kk['kepala']->jenis_kelamin }}</td>
                <td>{{ $kk['kepala']->alamat }}</td>
                <td>{{ $kk['kepala']->rt_rw }}</td>
                <td>{{ $kk['kepala']->desa_kelurahan }}</td>
                <td>{{ $kk['kepala']->kecamatan }}</td>
                <td>{{ $kk['jumlah_anggota'] }}</td>
                <td>{{ $kk['kepala']->no_hp ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center;">Tidak ada data kartu keluarga</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div>
            <p>Catatan: Dokumen ini dicetak dalam orientasi landscape.</p>
        </div>
        <div style="text-align: right;">
            <p>Dicetak pada {{ $tanggal ?? now()->format('d/m/Y H:i') }}</p>
            <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
            <p>Total: {{ count($kartuKeluarga) }} kartu keluarga</p>
        </div>
    </div>
</body>
</html>