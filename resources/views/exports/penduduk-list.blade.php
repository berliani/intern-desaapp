<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Penduduk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
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
            font-size: 9px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 4px;
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
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .gender-stats {
            margin-top: 15px;
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .gender-stats table {
            border: none;
            width: auto;
            margin-bottom: 0;
        }
        .gender-stats table td, .gender-stats table th {
            border: none;
            padding: 2px 8px;
        }
        .gender-stats h4 {
            margin: 0 0 5px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR PENDUDUK</h1>
        <p>Tanggal Cetak: {{ $tanggal }}</p>
        <p>Total Data: {{ $total }} penduduk</p>
    </div>

    <div class="filter-info">
        <div>
            <p><strong>Filter yang Diterapkan:</strong></p>
            @if($filters['jenis_kelamin'])
                <p>Jenis Kelamin: {{ $filters['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            @endif

            @if($filters['status_perkawinan'])
                <p>Status Perkawinan: {{ $filters['status_perkawinan'] }}</p>
            @endif
        </div>
        <div>
            @if($filters['pekerjaan'])
                <p>Pekerjaan: {{ $filters['pekerjaan'] }}</p>
            @endif

            @if($filters['dariTanggal'] && $filters['sampaiTanggal'])
                <p>Periode: {{ $filters['dariTanggal'] }} - {{ $filters['sampaiTanggal'] }}</p>
            @elseif($filters['dariTanggal'])
                <p>Dari Tanggal: {{ $filters['dariTanggal'] }}</p>
            @elseif($filters['sampaiTanggal'])
                <p>Sampai Tanggal: {{ $filters['sampaiTanggal'] }}</p>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="8%">NIK</th>
                <th width="12%">Nama</th>
                <th width="4%">JK</th>
                <th width="6%">Tempat Lahir</th>
                <th width="6%">Tgl Lahir</th>
                <th width="3%">Usia</th>
                <th width="10%">Alamat</th>
                <th width="5%">RT/RW</th>
                <th width="6%">Desa</th>
                <th width="6%">Kecamatan</th>
                <th width="5%">Agama</th>
                <th width="6%">Status</th>
                <th width="8%">Pekerjaan</th>
                <th width="6%">Pendidikan</th>
                <th width="6%">No. HP</th>
            </tr>
        </thead>
        <tbody>
            @if(count($pendudukList) > 0)
                @foreach($pendudukList as $index => $penduduk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $penduduk->nik }}</td>
                        <td>{{ $penduduk->nama }}</td>
                        <td>{{ $penduduk->jenis_kelamin }}</td>
                        <td>{{ $penduduk->tempat_lahir }}</td>
                        <td>{{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->age : '-' }}</td>
                        <td>{{ $penduduk->alamat }}</td>
                        <td>{{ $penduduk->rt_rw }}</td>
                        <td>{{ $penduduk->desa_kelurahan }}</td>
                        <td>{{ $penduduk->kecamatan }}</td>
                        <td>{{ $penduduk->agama }}</td>
                        <td>{{ $penduduk->status_perkawinan }}</td>
                        <td>{{ $penduduk->pekerjaan }}</td>
                        <td>{{ $penduduk->pendidikan }}</td>
                        <td>{{ $penduduk->no_hp ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="16" style="text-align: center;">Tidak ada data penduduk</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Statistik Jenis Kelamin -->
    @php
        $totalL = $pendudukList->where('jenis_kelamin', 'L')->count();
        $totalP = $pendudukList->where('jenis_kelamin', 'P')->count();
        $totalHouseholds = $pendudukList->where('kepala_keluarga', true)->count();
    @endphp

    <div class="gender-stats">
        <h4>Statistik Penduduk</h4>
        <table>
            <tr>
                <th>Laki-laki:</th>
                <td>{{ $totalL }} orang</td>
                <th>Perempuan:</th>
                <td>{{ $totalP }} orang</td>
                <th>Total KK:</th>
                <td>{{ $totalHouseholds }} KK</td>
            </tr>
        </table>
    </div>

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
