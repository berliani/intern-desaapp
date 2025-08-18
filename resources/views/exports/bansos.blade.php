<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Bantuan Sosial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #2d3748;
            font-size: 12px;
            margin: 0;
            padding: 15px;
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
            margin-bottom: 15px;
            font-size: 14px;
            color: #4a5568;
        }
        .header {
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #3182ce;
            padding-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
        }
        h2 {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #2c5282;
        }
        .meta-info {
            background-color: #ebf8ff;
            border: 1px solid #bee3f8;
            padding: 8px 12px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border-radius: 4px;
            overflow: hidden;
        }
        .info-table th, .info-table td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        .info-table th {
            background-color: #edf2f7;
            font-weight: bold;
            width: 40%;
            color: #2d3748;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
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

        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        .description-box {
            padding: 12px;
            background-color: #f7fafc;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            margin-top: 5px;
        }
        .status-timeline {
            margin-top: 15px;
            padding: 10px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .timeline-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dotted #e2e8f0;
        }
        .timeline-date {
            font-size: 10px;
            color: #718096;
        }
        .dokumen-section {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            margin-top: 10px;
        }
        .dokumen-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c5282;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL BANTUAN SOSIAL</h1>
        <p class="subtitle">{{ $bansos->jenisBansos->nama_bansos ?? 'Bantuan' }}</p>
    </div>

    <div class="meta-info">
        <div>
            <strong>ID Bantuan:</strong> {{ $bansos->id }} |
            <strong>Tanggal Pengajuan:</strong> {{ $bansos->tanggal_pengajuan ? $bansos->tanggal_pengajuan->translatedFormat('d F Y') : '-' }}
        </div>
    </div>

    <div class="section">
        <h2>Informasi Penerima</h2>
        <table class="info-table">
            <tr>
                <th>Nama Penerima</th>
                <td>{{ $bansos->penduduk->nama ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>NIK</th>
                <td>{{ $bansos->penduduk->nik ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Desa</th>
                <td>{{ $bansos->desa->nama_desa ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Alamat</th>
                <td>{{ $bansos->penduduk->alamat ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Detail Bantuan</h2>
        <table class="info-table">
            <tr>
                <th>Jenis Bantuan</th>
                <td>{{ $bansos->jenisBansos->nama_bansos ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $bansos->jenisBansos->kategori ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Instansi Pemberi</th>
                <td>{{ $bansos->jenisBansos->instansi_pemberi ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Bentuk Bantuan</th>
                <td>
                    @if($bansos->jenisBansos)
                        @if($bansos->jenisBansos->bentuk_bantuan === 'uang')
                            Uang ({{ $bansos->jenisBansos->getNilaiBantuanFormatted() }})
                        @else
                            Barang ({{ $bansos->jenisBansos->jumlah_per_penerima ?? '' }}
                            {{ App\Models\JenisBansos::getSatuanOptions()[$bansos->jenisBansos->satuan] ?? '' }})
                        @endif
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Status Bantuan</h2>
        <table class="info-table">
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge badge-{{ str_replace(' ', '-', $bansos->status) }}">{{ $bansos->status }}</span>
                </td>
            </tr>
            <tr>
                <th>Prioritas</th>
                <td>
                    <span class="badge badge-{{ $bansos->prioritas }}">{{ $bansos->prioritas }}</span>
                    @if($bansos->is_urgent)
                        <span style="color: #c53030; font-weight: bold; margin-left: 10px;">MENDESAK/URGENT</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Sumber Pengajuan</th>
                <td>{{ $bansos->sumber_pengajuan === 'admin' ? 'Admin/Petugas Desa' : 'Pengajuan Warga' }}</td>
            </tr>
            <tr>
                <th>Tanggal Pengajuan</th>
                <td>{{ $bansos->tanggal_pengajuan ? $bansos->tanggal_pengajuan->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            @if($bansos->status === 'Disetujui')
                <tr>
                    <th>Tenggat Pengambilan</th>
                    <td>{{ $bansos->tenggat_pengambilan ? $bansos->tenggat_pengambilan->translatedFormat('d F Y') : '-' }}</td>
                </tr>
            @endif
            @if($bansos->status === 'Sudah Diterima')
                <tr>
                    <th>Tanggal Penerimaan</th>
                    <td>{{ $bansos->tanggal_penerimaan ? $bansos->tanggal_penerimaan->translatedFormat('d F Y') : '-' }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <h2>Alasan Pengajuan</h2>
        <div class="description-box">
            {{ $bansos->alasan_pengajuan ?? 'Tidak ada alasan yang dicatat' }}
        </div>
    </div>

    @if($bansos->keterangan)
    <div class="section">
        <h2>Keterangan Tambahan</h2>
        <div class="description-box">
            {{ $bansos->keterangan }}
        </div>
    </div>
    @endif

    <div class="section">
        <h2>Informasi Administrasi</h2>
        <table class="info-table">
            <tr>
                <th>Dibuat Oleh</th>
                <td>{{ $bansos->creator->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Terakhir Diubah Oleh</th>
                <td>{{ $bansos->editor->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Tanggal Dibuat</th>
                <td>{{ $bansos->created_at ? $bansos->created_at->translatedFormat('d F Y, H:i') : '-' }}</td>
            </tr>
            <tr>
                <th>Terakhir Diperbarui</th>
                <td>{{ $bansos->updated_at ? $bansos->updated_at->translatedFormat('d F Y, H:i') : '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div style="display: flex; justify-content: space-between;">
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