<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Keluarga - {{ $kk['kepala']->kk }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 15px;
            color: #333;
            font-size: 11px;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 18px;
        }
        h2 {
            font-size: 14px;
            margin-top: 12px;
            margin-bottom: 8px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            color: #333;
        }
        .subtitle {
            text-align: center;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .header {
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            text-align: center;
        }
        .section {
            margin-bottom: 12px;
        }
        .info-box {
            border: 1px solid #999;
            padding: 10px;
            margin-bottom: 12px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .info-item {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
        }
        table, th, td {
            border: 1px solid #999;
        }
        th, td {
            padding: 5px 4px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .contact-info {
            margin-top: 10px;
        }
        .contact-box {
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: 5px;
            background-color: #f5f5f5;
        }
        .stats-box {
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: 5px;
            background-color: #f0f7ff;
        }
        .stats-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #1e40af;
        }
        .signature-section {
            margin-top: 12px;
            text-align: right;
        }
        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #999;
            padding-top: 8px;
        }
        .meta-info {
            background-color: #f5f5f5;
            padding: 8px;
            border-radius: 4px;
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 11px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>KARTU KELUARGA</h1>
        <p class="subtitle">Nomor: {{ $kk['kepala']->kk }}</p>
    </div>

    <div class="meta-info">
        <strong>Alamat:</strong> {{ $kk['kepala']->alamat }}, RT/RW {{ $kk['kepala']->rt_rw }}, {{ $kk['kepala']->desa_kelurahan }}, {{ $kk['kepala']->kecamatan }}, {{ $kk['kepala']->kabupaten }}
    </div>

    <div class="section">
        <h2>Data Kepala Keluarga</h2>
        <div class="info-box">
            <div class="info-item">
                <span class="info-label">Nama:</span> {{ $kk['kepala']->nama }}
            </div>
            <div class="info-item">
                <span class="info-label">NIK:</span> {{ $kk['kepala']->nik }}
            </div>
            <div class="info-item">
                <span class="info-label">Jenis Kelamin:</span>
                {{ $kk['kepala']->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
            </div>
            <div class="info-item">
                <span class="info-label">Tempat/Tgl Lahir:</span>
                {{ $kk['kepala']->tempat_lahir }},
                {{ $kk['kepala']->tanggal_lahir ? \Carbon\Carbon::parse($kk['kepala']->tanggal_lahir)->format('d-m-Y') : '-' }}
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Agama:</span> {{ $kk['kepala']->agama }}
                </div>
                <div class="info-item">
                    <span class="info-label">Pendidikan:</span> {{ $kk['kepala']->pendidikan }}
                </div>
                <div class="info-item">
                    <span class="info-label">Pekerjaan:</span> {{ $kk['kepala']->pekerjaan }}
                </div>
                <div class="info-item">
                    <span class="info-label">Status Perkawinan:</span> {{ $kk['kepala']->status_perkawinan }}
                </div>
            </div>
            <div class="contact-box">
                <div class="info-item">
                    <span class="info-label">Nomor HP:</span> {{ $kk['kepala']->no_hp ?? '-' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span> {{ $kk['kepala']->email ?? '-' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Golongan Darah:</span> {{ $kk['kepala']->golongan_darah ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Data Anggota Keluarga</h2>
        <table>
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="23%">Nama</th>
                    <th width="12%">NIK</th>
                    <th width="5%">L/P</th>
                    <th width="13%">Tempat Lahir</th>
                    <th width="10%">Tgl Lahir</th>
                    <th width="8%">Umur</th>
                    <th width="25%">Pekerjaan</th>
                </tr>
            </thead>
            <tbody>
                @if(count($kk['anggota']) > 0)
                    @foreach($kk['anggota'] as $index => $anggota)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $anggota->nama }}</td>
                        <td>{{ $anggota->nik }}</td>
                        <td>{{ $anggota->jenis_kelamin }}</td>
                        <td>{{ $anggota->tempat_lahir }}</td>
                        <td>{{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $anggota->tanggal_lahir ? \Carbon\Carbon::parse($anggota->tanggal_lahir)->age . ' th' : '-' }}</td>
                        <td>{{ $anggota->pekerjaan }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada anggota keluarga</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table>
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="23%">Nama</th>
                    <th width="13%">Pendidikan</th>
                    <th width="15%">Status Kawin</th>
                    <th width="15%">Status Dalam KK</th>
                    <th width="15%">No. HP</th>
                    <th width="15%">Gol. Darah</th>
                </tr>
            </thead>
            <tbody>
                @if(count($kk['anggota']) > 0)
                    @foreach($kk['anggota'] as $index => $anggota)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $anggota->nama }}</td>
                        <td>{{ $anggota->pendidikan }}</td>
                        <td>{{ $anggota->status_perkawinan }}</td>
                        <td>{{ $anggota->status_hubungan_dalam_keluarga ?? 'Anggota' }}</td>
                        <td>{{ $anggota->no_hp ?? '-' }}</td>
                        <td>{{ $anggota->golongan_darah ?? '-' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" style="text-align: center;">Tidak ada anggota keluarga</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


    <div class="footer">
        <div style="display: flex; justify-content: space-between;">
            <div>
                <p>Dicetak pada {{ $tanggal ?? now()->format('d/m/Y H:i') }}</p>
            </div>
            <div style="text-align: right;">
                <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
            </div>
        </div>
    </div>
</body>
</html>