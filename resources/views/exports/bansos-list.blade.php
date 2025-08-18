<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Bantuan Sosial</title>
    <style>
        @page {
            margin: 15mm 10mm 15mm 10mm;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }
        .wrapper {
            padding: 0 5mm;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 18px;
            color: #1a202c;
        }
        .subtitle {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 12px;
            color: #4a5568;
        }
        .header {
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 2px solid #3182ce;
            padding-bottom: 10px;
        }
        .filter-box {
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .filter-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c5282;
        }
        .filter-items {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .filter-item {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            border-radius: 3px;
            padding: 4px 8px;
            color: #2c5282;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
        }
        .data-table, .data-table th, .data-table td {
            border: 1px solid #e2e8f0;
        }
        .data-table th, .data-table td {
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }
        .data-table th {
            background-color: #edf2f7;
            font-weight: bold;
            color: #2d3748;
        }
        .data-table tr:nth-child(even) {
            background-color: #f7fafc;
        }
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-Diajukan { background-color: #e2e8f0; color: #4a5568; }
        .badge-Ditolak { background-color: #fed7d7; color: #c53030; }
        .badge-Disetujui { background-color: #c6f6d5; color: #2f855a; }
        .badge-Sudah-Diterima { background-color: #bee3f8; color: #2c5282; }
        .badge-Dalam-Verifikasi { background-color: #feebc8; color: #c05621; }
        .badge-Diverifikasi { background-color: #e9d8fd; color: #6b46c1; }
        .badge-Dibatalkan { background-color: #fed7d7; color: #c53030; }

        .badge-Rendah { background-color: #c6f6d5; color: #2f855a; }
        .badge-Sedang { background-color: #feebc8; color: #c05621; }
        .badge-Tinggi { background-color: #fed7d7; color: #c53030; }

        .badge-Sembako, .badge-Pangan { background-color: #bee3f8; color: #2c5282; }
        .badge-Tunai, .badge-Pertanian { background-color: #c6f6d5; color: #2f855a; }
        .badge-Kesehatan, .badge-UMKM { background-color: #e9d8fd; color: #6b46c1; }
        .badge-Pendidikan { background-color: #feebc8; color: #c05621; }
        .badge-Perumahan { background-color: #fed7d7; color: #c53030; }
        .badge-Lainnya { background-color: #e2e8f0; color: #4a5568; }

        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
        }
        .stats-container {
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .stats-title {
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 8px;
            padding-bottom: 3px;
            color: #2c5282;
            font-size: 11px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .stats-box {
            border: 1px solid #e2e8f0;
            border-radius: 3px;
            padding: 8px;
        }
        .stats-box-title {
            font-weight: bold;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
            margin-bottom: 5px;
            font-size: 9px;
        }
        .stats-items {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .stats-item {
            display: flex;
            justify-content: space-between;
        }
        .stats-item-label {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        .stats-item-value {
            font-weight: bold;
            color: #2c5282;
        }
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .dot-Diajukan { background-color: #a0aec0; }
        .dot-Ditolak, .dot-Dibatalkan { background-color: #f56565; }
        .dot-Disetujui { background-color: #48bb78; }
        .dot-Sudah-Diterima { background-color: #4299e1; }
        .dot-Dalam-Verifikasi { background-color: #ed8936; }
        .dot-Diverifikasi { background-color: #9f7aea; }

        .dot-Rendah { background-color: #48bb78; }
        .dot-Sedang { background-color: #ed8936; }
        .dot-Tinggi { background-color: #f56565; }

        .dot-Sembako, .dot-Pangan { background-color: #4299e1; }
        .dot-Tunai, .dot-Pertanian { background-color: #48bb78; }
        .dot-Kesehatan, .dot-UMKM { background-color: #9f7aea; }
        .dot-Pendidikan { background-color: #ed8936; }
        .dot-Perumahan { background-color: #f56565; }
        .dot-Lainnya { background-color: #a0aec0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>DAFTAR BANTUAN SOSIAL</h1>
            @if(isset($filter['dariTanggal']) && isset($filter['sampaiTanggal']))
                <p class="subtitle">Periode: {{ $filter['dariTanggal'] ?? '-' }} s/d {{ $filter['sampaiTanggal'] ?? '-' }}</p>
            @else
                <p class="subtitle">Tanggal Cetak: {{ $tanggal }}</p>
            @endif
        </div>

        <div class="filter-box">
            <div class="filter-title">Filter yang Diterapkan:</div>
            <div class="filter-items">
                @if(isset($filter['id_desa']) && $filter['id_desa'])
                    <span class="filter-item">Desa: {{ $filter['id_desa'] }}</span>
                @endif
                @if(isset($filter['jenis_bansos_id']) && $filter['jenis_bansos_id'])
                    <span class="filter-item">Jenis Bantuan: {{ App\Models\JenisBansos::find($filter['jenis_bansos_id'])->nama_bansos ?? $filter['jenis_bansos_id'] }}</span>
                @endif
                @if(isset($filter['status']) && $filter['status'])
                    <span class="filter-item">Status: {{ $filter['status'] }}</span>
                @endif
                @if(isset($filter['prioritas']) && $filter['prioritas'])
                    <span class="filter-item">Prioritas: {{ $filter['prioritas'] }}</span>
                @endif
                @if(isset($filter['sumber_pengajuan']) && $filter['sumber_pengajuan'])
                    <span class="filter-item">Sumber: {{ $filter['sumber_pengajuan'] === 'admin' ? 'Admin/Petugas' : 'Warga' }}</span>
                @endif
                @if(isset($filter['dariTanggal']) && $filter['dariTanggal'])
                    <span class="filter-item">Dari: {{ $filter['dariTanggal'] }}</span>
                @endif
                @if(isset($filter['sampaiTanggal']) && $filter['sampaiTanggal'])
                    <span class="filter-item">Sampai: {{ $filter['sampaiTanggal'] }}</span>
                @endif
                @if(!isset($filter['id_desa']) && !isset($filter['jenis_bansos_id']) && !isset($filter['status']) && !isset($filter['prioritas']) && !isset($filter['sumber_pengajuan']) && !isset($filter['dariTanggal']) && !isset($filter['sampaiTanggal']))
                    <span class="filter-item">Semua Data</span>
                @endif
            </div>
            <div style="margin-top: 5px;">Total Data: <strong>{{ $bansosList->count() }}</strong> bantuan</div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 3%">No</th>
                    <th style="width: 13%">Penerima</th>
                    <th style="width: 8%">NIK</th>
                    <th style="width: 9%">Desa</th>
                    <th style="width: 14%">Jenis Bantuan</th>
                    <th style="width: 7%">Kategori</th>
                    <th style="width: 7%">Tgl Pengajuan</th>
                    <th style="width: 7%">Status</th>
                    <th style="width: 6%">Prioritas</th>
                    <th style="width: 8%">Sumber</th>
                    <th style="width: 18%">Alasan Pengajuan</th>
                </tr>
            </thead>
            <tbody>
                @if($bansosList->count() > 0)
                    @foreach($bansosList as $index => $bansos)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $bansos->penduduk->nama ?? 'N/A' }}</td>
                        <td>{{ $bansos->penduduk->nik ?? 'N/A' }}</td>
                        <td>{{ $bansos->desa->nama_desa ?? 'N/A' }}</td>
                        <td>{{ $bansos->jenisBansos->nama_bansos ?? 'N/A' }}</td>
                        <td>
                            @if($bansos->jenisBansos)
                                <span class="badge badge-{{ $bansos->jenisBansos->kategori }}">{{ $bansos->jenisBansos->kategori }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $bansos->tanggal_pengajuan ? $bansos->tanggal_pengajuan->format('d/m/Y') : '-' }}</td>
                        <td>
                            <span class="badge badge-{{ str_replace(' ', '-', $bansos->status) }}">{{ $bansos->status }}</span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $bansos->prioritas }}">{{ $bansos->prioritas }}</span>
                        </td>
                        <td>{{ $bansos->sumber_pengajuan === 'admin' ? 'Admin/Petugas' : 'Warga' }}</td>
                        <td>{{ Str::limit($bansos->alasan_pengajuan, 100) }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11" style="text-align: center;">Tidak ada data bantuan sosial yang ditemukan</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="stats-container">
            <div class="stats-title">STATISTIK BANTUAN SOSIAL</div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 33%">Berdasarkan Status</th>
                        <th style="width: 33%">Berdasarkan Kategori</th>
                        <th style="width: 33%">Berdasarkan Prioritas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="vertical-align: top; padding: 5px;">
                            <table style="width: 100%; border-collapse: collapse;">
                                @foreach($stats['total_by_status'] as $status => $count)
                                <tr>
                                    <td style="padding: 3px; border: none;">
                                        <span class="dot dot-{{ str_replace(' ', '-', $status) }}"></span>
                                        {{ $status }}
                                    </td>
                                    <td style="padding: 3px; border: none; text-align: right; font-weight: bold; color: #2c5282;">
                                        {{ $count }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                        <td style="vertical-align: top; padding: 5px;">
                            <table style="width: 100%; border-collapse: collapse;">
                                @foreach($stats['total_by_kategori'] as $kategori => $count)
                                <tr>
                                    <td style="padding: 3px; border: none;">
                                        <span class="dot dot-{{ $kategori }}"></span>
                                        {{ $kategori }}
                                    </td>
                                    <td style="padding: 3px; border: none; text-align: right; font-weight: bold; color: #2c5282;">
                                        {{ $count }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                        <td style="vertical-align: top; padding: 5px;">
                            <table style="width: 100%; border-collapse: collapse;">
                                @foreach($stats['total_by_prioritas'] as $prioritas => $count)
                                <tr>
                                    <td style="padding: 3px; border: none;">
                                        <span class="dot dot-{{ $prioritas }}"></span>
                                        {{ $prioritas }}
                                    </td>
                                    <td style="padding: 3px; border: none; text-align: right; font-weight: bold; color: #2c5282;">
                                        {{ $count }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <div>
                <p>Dicetak pada {{ $tanggal }}</p>
            </div>
            <div style="text-align: right;">
                <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
            </div>
        </div>
    </div>
</body>
</html>