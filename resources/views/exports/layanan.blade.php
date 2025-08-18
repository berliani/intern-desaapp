<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Force landscape orientation -->
    <meta name="pdfOrientation" content="landscape">
    <title>Informasi Layanan - {{ $layanan->nama_layanan }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 10mm 15mm 10mm; /* top right bottom left - reduced margins */
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 10px;
            color: #333;
            font-size: 11px;
            width: 100%;
            box-sizing: border-box;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .subtitle {
            text-align: center;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            text-align: center;
        }

        .content-container {
            display: flex;
            margin-bottom: 20px;
        }

        .content-left {
            width: 48%;
            float: left;
            margin-right: 2%;
        }

        .content-right {
            width: 48%;
            float: right;
        }

        h2 {
            border-bottom: 1px solid #999;
            padding-bottom: 5px;
            margin-top: 20px;
            font-size: 14px;
            color: #2c3e50;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        .info-table td {
            padding: 6px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .info-table td:first-child {
            width: 120px;
            font-weight: bold;
        }

        ol, ul {
            margin-top: 5px;
            padding-left: 20px;
        }

        li {
            margin-bottom: 6px;
        }

        .req-title {
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #999;
            padding-top: 8px;
            clear: both;
        }

        .highlight {
            color: #10b981;
            font-weight: bold;
        }

        .meta-info {
            margin-top: 20px;
            background-color: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: #fff;
            background-color: #3b82f6;
            margin-right: 5px;
        }

        .badge-surat { background-color: #3b82f6; }
        .badge-kesehatan { background-color: #10b981; }
        .badge-pendidikan { background-color: #f59e0b; }
        .badge-sosial { background-color: #8b5cf6; }
        .badge-infrastruktur { background-color: #ef4444; }
        .badge-lainnya { background-color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <h1>INFORMASI LAYANAN DESA</h1>
        <p class="subtitle">
            {{ $layanan->desa->nama_desa ?? 'Tidak Ada Data' }}
        </p>
    </div>

    <h2 style="margin-top: 0;">{{ $layanan->nama_layanan }}</h2>

    <div class="content-container">
        <div class="content-left">
            <h2>Deskripsi Layanan</h2>
            <div>{!! $layanan->deskripsi !!}</div>

            <h2>Informasi Layanan</h2>
            <table class="info-table">
                <tr>
                    <td>Lokasi Layanan</td>
                    <td>: {{ $layanan->lokasi_layanan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Jadwal Pelayanan</td>
                    <td>: {{ $layanan->jadwal_pelayanan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kontak Layanan</td>
                    <td>: {{ $layanan->kontak_layanan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Biaya</td>
                    <td>:
                        @if($layanan->biaya == 0)
                            <span class="highlight">Gratis</span>
                        @else
                            Rp {{ number_format($layanan->biaya, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            </table>

            <div class="meta-info">
                <div><strong>ID Layanan:</strong> {{ $layanan->id }}</div>
                <div><strong>Dibuat Oleh:</strong> {{ $layanan->creator->name ?? 'Administrator' }}</div>
                <div><strong>Tanggal Dibuat:</strong> {{ $layanan->created_at->format('d/m/Y H:i') }}</div>
                <div><strong>Terakhir Diperbarui:</strong> {{ $layanan->updated_at->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div class="content-right">
            @if(isset($layanan->persyaratan) && is_array($layanan->persyaratan) && count($layanan->persyaratan) > 0)
            <h2>Persyaratan</h2>
            <ol>
                @foreach($layanan->persyaratan as $persyaratan)
                <li>
                    <span class="req-title">{{ $persyaratan['dokumen'] }}</span>
                    @if(isset($persyaratan['keterangan']) && !empty($persyaratan['keterangan']))
                    <div>{{ $persyaratan['keterangan'] }}</div>
                    @endif
                </li>
                @endforeach
            </ol>
            @endif

            @if(isset($layanan->prosedur) && is_array($layanan->prosedur) && count($layanan->prosedur) > 0)
            <h2>Prosedur</h2>
            <ol>
                @foreach($layanan->prosedur as $step)
                <li>
                    <span class="req-title">{{ $step['langkah'] }}</span>
                    @if(isset($step['keterangan']) && !empty($step['keterangan']))
                    <div>{{ $step['keterangan'] }}</div>
                    @endif
                </li>
                @endforeach
            </ol>
            @endif
        </div>
    </div>

    <div class="footer">
        <div>
            <p>Catatan: Dokumen ini dicetak dalam orientasi landscape.</p>
        </div>
        <div style="text-align: right;">
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
        </div>
    </div>
</body>
</html>