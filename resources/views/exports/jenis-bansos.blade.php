<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Jenis Bantuan Sosial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 15px;
            font-size: 12px;
            color: #333;
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
            color: #fff;
        }
        .badge-Sembako { background-color: #3b82f6; }
        .badge-Tunai { background-color: #10b981; }
        .badge-Kesehatan { background-color: #0ea5e9; }
        .badge-Pendidikan { background-color: #f59e0b; }
        .badge-Perumahan { background-color: #ef4444; }
        .badge-Pangan { background-color: #3b82f6; }
        .badge-Pertanian { background-color: #10b981; }
        .badge-UMKM { background-color: #0ea5e9; }
        .badge-Lainnya { background-color: #6b7280; }
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
        .status-active {
            color: #2f855a;
            font-weight: bold;
        }
        .status-inactive {
            color: #c53030;
            font-weight: bold;
        }
        .stats-box {
            border: 1px solid #bee3f8;
            border-radius: 4px;
            padding: 10px;
            background-color: #ebf8ff;
            margin-top: 15px;
        }
        .stats-title {
            font-weight: bold;
            color: #2b6cb0;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL PROGRAM BANTUAN SOSIAL</h1>
        <p class="subtitle">{{ $jenisBansos->nama_bansos }}</p>
    </div>

    <div class="meta-info">
        <div>
            <strong>ID Program:</strong> {{ $jenisBansos->id }}
        </div>
        <div>
            <strong>Status:</strong>
            @if($jenisBansos->is_active)
                <span class="status-active">Aktif</span>
            @else
                <span class="status-inactive">Tidak Aktif</span>
            @endif
        </div>
    </div>

    <div class="section">
        <h2>Informasi Program</h2>
        <table class="info-table">
            <tr>
                <th>Nama Program</th>
                <td>{{ $jenisBansos->nama_bansos }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>
                    <span class="badge badge-{{ $jenisBansos->kategori }}">
                        {{ $jenisBansos->kategori }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Instansi Pemberi</th>
                <td>{{ $jenisBansos->instansi_pemberi }}</td>
            </tr>
            <tr>
                <th>Periode Bantuan</th>
                <td>{{ App\Models\JenisBansos::getPeriodeOptions()[$jenisBansos->periode] ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Detail Bantuan</h2>
        <table class="info-table">
            <tr>
                <th>Bentuk Bantuan</th>
                <td>{{ App\Models\JenisBansos::getBentukBantuanOptions()[$jenisBansos->bentuk_bantuan] ?? '-' }}</td>
            </tr>

            @if($jenisBansos->bentuk_bantuan === 'uang')
            <tr>
                <th>Nominal Standar</th>
                <td>
                    <strong>{{ $jenisBansos->getNilaiBantuanFormatted() }}</strong>
                </td>
            </tr>
            @else
            <tr>
                <th>Jumlah per Penerima</th>
                <td>{{ $jenisBansos->jumlah_per_penerima }} {{ App\Models\JenisBansos::getSatuanOptions()[$jenisBansos->satuan] ?? '' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="section">
        <h2>Deskripsi Program</h2>
        <div class="description-box">
            {{ $jenisBansos->deskripsi }}
        </div>
    </div>

    <div class="stats-box">
        <div class="stats-title">Statistik Penggunaan</div>
        <table class="info-table" style="margin-bottom: 0;">
            <tr>
                <th>Jumlah Penerima</th>
                <td>{{ $jenisBansos->bansos_count ?? $jenisBansos->bansos()->count() }} penerima</td>
            </tr>
            <tr>
                <th>Perkiraan Total Nilai</th>
                <td>
                    @php
                        $count = $jenisBansos->bansos_count ?? $jenisBansos->bansos()->count();
                        if($jenisBansos->bentuk_bantuan === 'uang') {
                            $total = $count * intval($jenisBansos->nominal_standar);
                            echo 'Rp ' . number_format($total, 0, ',', '.');
                        } else {
                            $total = $count * intval($jenisBansos->jumlah_per_penerima);
                            echo $total . ' ' . (App\Models\JenisBansos::getSatuanOptions()[$jenisBansos->satuan] ?? '');
                        }
                    @endphp
                </td>
            </tr>
            <tr>
                <th>Tanggal Dibuat</th>
                <td>{{ \Carbon\Carbon::parse($jenisBansos->created_at)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <th>Terakhir Diperbarui</th>
                <td>{{ \Carbon\Carbon::parse($jenisBansos->updated_at)->translatedFormat('d F Y') }}</td>
            </tr>
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
