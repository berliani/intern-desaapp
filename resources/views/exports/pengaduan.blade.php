<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Force landscape orientation -->
    <meta name="pdfOrientation" content="landscape">
    <title>Detail Pengaduan</title>
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

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }

        .header p {
            margin: 5px 0;
            font-size: 12px;
        }

        .content-container {
            display: flex;
            flex-wrap: wrap;
        }

        .left-column {
            width: 48%;
            float: left;
            margin-right: 2%;
        }

        .right-column {
            width: 48%;
            float: right;
        }

        h2 {
            color: #2c3e50;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table th, .info-table td {
            padding: 6px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .info-table th {
            width: 30%;
            font-weight: bold;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: #fff;
        }

        .badge-Belum-Ditangani { background-color: #dc3545; }
        .badge-Sedang-Diproses { background-color: #fd7e14; }
        .badge-Selesai { background-color: #28a745; }
        .badge-Ditolak { background-color: #6c757d; }

        .badge-Tinggi { background-color: #dc3545; }
        .badge-Sedang { background-color: #fd7e14; }
        .badge-Rendah { background-color: #28a745; }

        .description-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }

        .tanggapan-box {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #eaf7ff;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .photo {
            max-width: 100%;
            height: auto;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .meta-info {
            margin-top: 15px;
            background-color: #f5f5f5;
            padding: 8px;
            border-radius: 4px;
            font-size: 10px;
        }

        .footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL PENGADUAN</h1>
        <p>{{ $pengaduan->judul }}</p>
    </div>

    <div class="content-container">
        <div class="left-column">
            <h2>Informasi Pengaduan</h2>
            <table class="info-table">
                <tr>
                    <th>ID Pengaduan</th>
                    <td>{{ $pengaduan->id }}</td>
                </tr>
                <tr>
                    <th>Kategori</th>
                    <td>{{ $pengaduan->kategori ?: 'Tidak Terkategori' }}</td>
                </tr>
                <tr>
                    <th>Prioritas</th>
                    <td>
                        <span class="badge badge-{{ $pengaduan->prioritas }}">
                            {{ $pengaduan->prioritas }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge badge-{{ str_replace(' ', '-', $pengaduan->status) }}">
                            {{ $pengaduan->status }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Tanggal Laporan</th>
                    <td>{{ $pengaduan->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Desa</th>
                    <td>{{ $pengaduan->desa->nama_desa ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Visibilitas</th>
                    <td>{{ $pengaduan->is_public ? 'Publik' : 'Privat' }}</td>
                </tr>
            </table>

            <h2>Informasi Pelapor</h2>
            <table class="info-table">
                <tr>
                    <th>Nama</th>
                    <td>
                        @if($pengaduan->penduduk)
                            {{ $pengaduan->penduduk->nama ?? 'Anonim' }}
                        @else
                            Anonim
                        @endif
                    </td>
                </tr>
                @if($pengaduan->penduduk)
                    @if(!empty($pengaduan->penduduk->no_hp))
                    <tr>
                        <th>Nomor HP</th>
                        <td>{{ $pengaduan->penduduk->no_hp }}</td>
                    </tr>
                    @endif

                    @if(!empty($pengaduan->penduduk->email))
                    <tr>
                        <th>Email</th>
                        <td>{{ $pengaduan->penduduk->email }}</td>
                    </tr>
                    @endif

                    @if(!empty($pengaduan->penduduk->alamat))
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $pengaduan->penduduk->alamat }}</td>
                    </tr>
                    @endif
                @endif
            </table>

            @if($pengaduan->foto)
                <h2>Foto Bukti</h2>
                <img src="{{ public_path($pengaduan->foto) }}" class="photo">
            @endif
        </div>

        <div class="right-column">
            <h2>Deskripsi Pengaduan</h2>
            <div class="description-box">
                {!! nl2br(e($pengaduan->deskripsi)) !!}
            </div>

            @if($pengaduan->tanggapan)
                <h2>Tanggapan</h2>
                <div class="tanggapan-box">
                    <p>{!! nl2br(e($pengaduan->tanggapan)) !!}</p>

                    <p>
                        <strong>Ditanggapi oleh:</strong>
                        {{ $pengaduan->petugas->name ?? 'Admin' }}
                    </p>

                    <p>
                        <strong>Tanggal Tanggapan:</strong>
                        {{ $pengaduan->tanggal_tanggapan ? $pengaduan->tanggal_tanggapan->format('d/m/Y H:i') : '-' }}
                    </p>

                    @php
                        $waktuPenanganan = null;
                        if ($pengaduan->tanggal_tanggapan && $pengaduan->created_at) {
                            $waktuPenanganan = $pengaduan->created_at->diffInHours($pengaduan->tanggal_tanggapan);
                        }
                    @endphp

                    @if($waktuPenanganan !== null)
                    <p>
                        <strong>Waktu Penanganan:</strong>
                        {{ $waktuPenanganan }} jam
                    </p>
                    @endif
                </div>
            @endif

            <div class="meta-info">
                <div><strong>ID Pengaduan:</strong> {{ $pengaduan->id }}</div>
                <div><strong>Dibuat Pada:</strong> {{ $pengaduan->created_at->format('d/m/Y H:i') }}</div>
                <div><strong>Terakhir Diperbarui:</strong> {{ $pengaduan->updated_at->format('d/m/Y H:i') }}</div>
                @if($pengaduan->petugas)
                <div><strong>Ditangani Oleh:</strong> {{ $pengaduan->petugas->name }}</div>
                @endif
                @if($pengaduan->tanggal_tanggapan)
                <div><strong>Tanggal Tanggapan:</strong> {{ $pengaduan->tanggal_tanggapan->format('d/m/Y H:i') }}</div>
                @endif
            </div>
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
