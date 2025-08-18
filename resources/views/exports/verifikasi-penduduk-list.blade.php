<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Verifikasi Penduduk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 18pt;
        }
        .header p {
            margin: 5px 0;
            font-size: 12pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }
        th {
            background-color: #f2f2f2;
        }
        .filter-info {
            margin-bottom: 15px;
            font-size: 11pt;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11pt;
        }
        .status-pending {
            color: #f59e0b;
            font-weight: bold;
        }
        .status-approved {
            color: #10b981;
            font-weight: bold;
        }
        .status-rejected {
            color: #ef4444;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN VERIFIKASI PENDUDUK</h1>
        <p>Tanggal Cetak: {{ $tanggal }}</p>
    </div>

    <div class="filter-info">
        <strong>Filter:</strong>
        {{ $filter['status'] ?? 'Semua Status' }}
        @if(isset($filter['dari_tanggal']) && isset($filter['sampai_tanggal']))
            | Periode: {{ $filter['dari_tanggal'] }} s/d {{ $filter['sampai_tanggal'] }}
        @elseif(isset($filter['dari_tanggal']))
            | Dari tanggal: {{ $filter['dari_tanggal'] }}
        @elseif(isset($filter['sampai_tanggal']))
            | Sampai tanggal: {{ $filter['sampai_tanggal'] }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Pengaju</th>
                <th width="10%">NIK</th>
                <th width="15%">Nama</th>
                <th width="20%">Alamat</th>
                <th width="15%">Tanggal Pengajuan</th>
                <th width="10%">Status</th>
                <th width="10%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($verifikasiList as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->user ? $item->user->name : 'Pengajuan Mandiri' }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->alamat }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td class="status-{{ $item->status }}">{{ ucfirst($item->status) }}</td>
                <td>{{ $item->catatan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data verifikasi penduduk</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Verifikasi: {{ $verifikasiList->count() }}</p>
    </div>
</body>
</html>