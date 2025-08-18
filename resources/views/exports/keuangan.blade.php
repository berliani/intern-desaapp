<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Keuangan Desa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            font-size: 12px;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .detail-container {
            width: 100%;
            margin-bottom: 15px;
        }
        .detail-box {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .detail-header {
            background-color: #f2f2f2;
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .detail-content {
            padding: 10px;
        }
        .detail-row {
            margin-bottom: 8px;
            border-bottom: 1px dotted #eee;
            padding-bottom: 5px;
            display: flex;
        }
        .detail-label {
            width: 40%;
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            width: 60%;
        }
        .info-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 15px;
            border-radius: 5px;
        }
        .text-success {
            color: #28a745;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
        }
        .amount-box {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .amount-value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .amount-label {
            font-size: 14px;
            color: #555;
        }
        .meta-info {
            background-color: #f5f5f5;
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
        <h2>DETAIL TRANSAKSI KEUANGAN DESA</h2>
        <p>{{ $keuangan->desa->nama_desa ?? 'N/A' }}</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="amount-box" style="background-color: {{ $keuangan->jenis === 'Pemasukan' ? '#e8f5e9' : '#ffebee' }}; border: 1px solid {{ $keuangan->jenis === 'Pemasukan' ? '#c8e6c9' : '#ffcdd2' }};">
        <div class="amount-label">Jumlah {{ $keuangan->jenis }}</div>
        <div class="amount-value {{ $keuangan->jenis === 'Pemasukan' ? 'text-success' : 'text-danger' }}">
            Rp {{ number_format($keuangan->jumlah, 0, ',', '.') }}
        </div>
        <div>Tanggal Transaksi: {{ $keuangan->tanggal->format('d F Y') }}</div>
    </div>

    <div class="meta-info">
        <strong>ID Transaksi:</strong> {{ $keuangan->id }} |
        <strong>Ref:</strong> {{ substr(md5($keuangan->id . $keuangan->created_at), 0, 10) }}
    </div>

    <div class="detail-box">
        <div class="detail-header">Informasi Transaksi</div>
        <div class="detail-content">
            <div class="detail-row">
                <div class="detail-label">Desa</div>
                <div class="detail-value">{{ $keuangan->desa->nama_desa ?? 'N/A' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Jenis Transaksi</div>
                <div class="detail-value">
                    <span class="{{ $keuangan->jenis === 'Pemasukan' ? 'text-success' : 'text-danger' }}">
                        {{ $keuangan->jenis }}
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Deskripsi</div>
                <div class="detail-value">{{ $keuangan->deskripsi }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tanggal Transaksi</div>
                <div class="detail-value">{{ $keuangan->tanggal->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <div class="detail-box">
        <div class="detail-header">Informasi Pencatatan</div>
        <div class="detail-content">
            <div class="detail-row">
                <div class="detail-label">Dibuat Oleh</div>
                <div class="detail-value">{{ $keuangan->creator->name ?? 'N/A' }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tanggal Dibuat</div>
                <div class="detail-value">{{ $keuangan->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Terakhir Diperbarui</div>
                <div class="detail-value">{{ $keuangan->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="info-box">
        <p><strong>Catatan Penting:</strong></p>
        <ul>
            <li>Transaksi ini telah tercatat dalam sistem keuangan desa.</li>
            <li>Dokumen ini dapat digunakan sebagai bukti transaksi resmi.</li>
            <li>Jika ada perubahan, dapat dilakukan melalui sistem.</li>
        </ul>
    </div>

    <div style="margin-top: 20px;">
        <div class="text-center">
            <p>{{ $keuangan->desa->nama_desa ?? 'Desa' }}, {{ now()->format('d F Y') }}</p>
            <p>Petugas Pencatat</p>
            <br><br><br>
            <p>{{ $keuangan->creator->name ?? 'Admin' }}</p>
        </div>
    </div>

    <div class="footer">
        <div>
            <p>Dicetak dari Sistem Informasi Desa Digital</p>
        </div>
        <div style="text-align: right;">
            <p>Dokumen ini dihasilkan secara otomatis</p>
        </div>
    </div>
</body>
</html>
