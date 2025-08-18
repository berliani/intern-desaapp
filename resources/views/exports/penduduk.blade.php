<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Penduduk</title>
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
            border-bottom: 2px solid #ddd;
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
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            padding-left: 5px;
            color: #333;
            border-left: 4px solid #4b5563;
        }
        .meta-info {
            background-color: #f9f9f9;
            padding: 8px;
            border-radius: 4px;
            margin: 10px 0 15px 0;
            font-size: 11px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA PENDUDUK</h1>
        <p>{{ $penduduk->desa->nama_desa ?? 'Desa' }}</p>
        <p>Tanggal Cetak: {{ $tanggal }}</p>
    </div>

    <div class="meta-info">
        <strong>NIK:</strong> {{ $penduduk->nik }} |
        <strong>Nama:</strong> {{ $penduduk->nama }} |
        <strong>Status:</strong> {{ $penduduk->kepala_keluarga ? 'Kepala Keluarga' : 'Anggota Keluarga' }}
    </div>

    <div class="section">
        <div class="title">Informasi Pribadi</div>
        <table>
            <tr>
                <th>NIK</th>
                <td>{{ $penduduk->nik }}</td>
            </tr>
            <tr>
                <th>Nomor KK</th>
                <td>{{ $penduduk->kk ?? '-' }}</td>
            </tr>
            <tr>
                <th>Nama Lengkap</th>
                <td>{{ $penduduk->nama }}</td>
            </tr>
            <tr>
                <th>Jenis Kelamin</th>
                <td>{{ $penduduk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            </tr>
            <tr>
                <th>Tempat, Tanggal Lahir</th>
                <td>
                    {{ $penduduk->tempat_lahir }},
                    {{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <th>Usia</th>
                <td>{{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->age . ' tahun' : '-' }}</td>
            </tr>
            <tr>
                <th>Agama</th>
                <td>{{ $penduduk->agama }}</td>
            </tr>
            <tr>
                <th>Golongan Darah</th>
                <td>{{ $penduduk->golongan_darah ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="title">Domisili & Kontak</div>
        <table>
            <tr>
                <th>Alamat</th>
                <td>{{ $penduduk->alamat }}</td>
            </tr>
            <tr>
                <th>RT/RW</th>
                <td>{{ $penduduk->rt_rw }}</td>
            </tr>
            <tr>
                <th>Desa/Kelurahan</th>
                <td>{{ $penduduk->desa_kelurahan }}</td>
            </tr>
            <tr>
                <th>Kecamatan</th>
                <td>{{ $penduduk->kecamatan }}</td>
            </tr>
            <tr>
                <th>Kabupaten</th>
                <td>{{ $penduduk->kabupaten }}</td>
            </tr>
            <tr>
                <th>Nomor HP</th>
                <td>{{ $penduduk->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $penduduk->email ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="title">Status & Pekerjaan</div>
        <table>
            <tr>
                <th>Status Perkawinan</th>
                <td>{{ $penduduk->status_perkawinan }}</td>
            </tr>
            <tr>
                <th>Pekerjaan</th>
                <td>{{ $penduduk->pekerjaan }}</td>
            </tr>
            <tr>
                <th>Pendidikan</th>
                <td>{{ $penduduk->pendidikan }}</td>
            </tr>
            <tr>
                <th>Status dalam Keluarga</th>
                <td>{{ $penduduk->kepala_keluarga ? 'Kepala Keluarga' : 'Anggota Keluarga' }}</td>
            </tr>
            <tr>
                <th>Tanggal Terdaftar</th>
                <td>{{ \Carbon\Carbon::parse($penduduk->created_at)->translatedFormat('d F Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
        <p>Tanggal & Waktu: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>
    </div>
</body>
</html>
