<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Force landscape orientation -->
    <meta name="pdfOrientation" content="landscape">
    <title>Daftar Pengaduan</title>
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
            font-size: 12px;
            color: #555;
        }

        .header {
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .filters {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            background-color: #f9f9f9;
            padding: 5px 8px;
            border-radius: 4px;
        }

        .filter-item {
            margin-right: 8px;
        }

        .pengaduan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        .pengaduan-table th, .pengaduan-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            overflow: hidden;
            word-wrap: break-word;
        }

        .pengaduan-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .pengaduan-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Badge style yang lebih halus */
        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 9px;
            color: #333;
            border: 1px solid #ddd;
            background-color: #f8f9fa;
        }

        /* Status colors */
        .badge-Belum-Ditangani {
            border-color: #EB9A9A;
            background-color: #FEE9E9;
        }
        .badge-Sedang-Diproses {
            border-color: #FECB8F;
            background-color: #FFF5E9;
        }
        .badge-Selesai {
            border-color: #A3D9B0;
            background-color: #EDFBF0;
        }
        .badge-Ditolak {
            border-color: #C1C6CC;
            background-color: #F1F3F5;
        }

        /* Prioritas colors */
        .badge-Tinggi {
            border-color: #EB9A9A;
            background-color: #FEE9E9;
        }
        .badge-Sedang {
            border-color: #FECB8F;
            background-color: #FFF5E9;
        }
        .badge-Rendah {
            border-color: #A3D9B0;
            background-color: #EDFBF0;
        }

        /* Kategori colors */
        .badge-Kesehatan {
            border-color: #A3D9C6;
            background-color: #EDFBF7;
        }
        .badge-Keamanan {
            border-color: #EB9A9A;
            background-color: #FEE9E9;
        }
        .badge-Pelayanan-Publik {
            border-color: #9AC1EB;
            background-color: #E9F3FE;
        }
        .badge-Sosial {
            border-color: #C6A3D9;
            background-color: #F7EDFB;
        }
        .badge-Lainnya {
            border-color: #C1C6CC;
            background-color: #F1F3F5;
        }

        .footer {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .stats-table th {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 5px;
            font-size: 11px;
        }

        .stats-table td {
            padding: 5px 6px;
            border: 1px solid #ddd;
            vertical-align: top;
            font-size: 11px;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inner-table td {
            border: none;
            padding: 3px 0;
        }

        .inner-table td:last-child {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR PENGADUAN</h1>
        @if(isset($filter))
            <p class="subtitle">
                @if(isset($filter['status']) && $filter['status'])
                    Status: {{ ucfirst($filter['status']) }} |
                @endif

                @if(isset($filter['kategori']) && $filter['kategori'])
                    Kategori: {{ $filter['kategori'] }} |
                @endif

                @if(isset($filter['periode']))
                    {{ $filter['periode'] }}
                @endif

                @if(isset($filter['jenis']))
                    {{ $filter['jenis'] }}
                @endif
            </p>
        @endif
    </div>

    <div class="filters">
        <div>
            <strong>Filter:</strong>
            @if(isset($filter['status']) && $filter['status'])
                <span class="filter-item">Status: {{ $filter['status'] }}</span>
            @endif
            @if(isset($filter['kategori']) && $filter['kategori'])
                <span class="filter-item">Kategori: {{ $filter['kategori'] }}</span>
            @endif
            @if(isset($filter['periode']) && $filter['periode'] !== 'Semua Waktu')
                <span class="filter-item">Periode: {{ $filter['periode'] }}</span>
            @endif
            @if(!isset($filter) || (!isset($filter['status']) && !isset($filter['kategori']) && (!isset($filter['periode']) || $filter['periode'] === 'Semua Waktu')))
                <span class="filter-item">Semua pengaduan</span>
            @endif
        </div>
        <div>
            <strong>Total Data:</strong> {{ isset($filter['count']) ? $filter['count'] : (isset($pengaduans) ? count($pengaduans) : 0) }} pengaduan
        </div>
    </div>

    <table class="pengaduan-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="22%">Judul</th>
                <th width="13%">Pelapor</th>
                <th width="13%">Desa</th>
                <th width="12%">Kategori</th>
                <th width="10%">Prioritas</th>
                <th width="10%">Status</th>
                <th width="8%">Ditanggapi</th>
                <th width="10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $statusCounts = collect($pengaduans)->groupBy('status')->map->count();
                $kategoriCounts = collect($pengaduans)->groupBy('kategori')->map->count();
                $prioritasCounts = collect($pengaduans)->groupBy('prioritas')->map->count();
                $tanggapiCounts = [
                    'Sudah' => collect($pengaduans)->filter(function($item) {
                        return !empty($item->tanggapan);
                    })->count(),
                    'Belum' => collect($pengaduans)->filter(function($item) {
                        return empty($item->tanggapan);
                    })->count()
                ];
            @endphp

            @forelse($pengaduans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->judul }}</td>
                <td>
                    @if($item->penduduk)
                        {{ $item->penduduk->nama ?? 'Anonim' }}
                    @else
                        Anonim
                    @endif
                </td>
                <td>{{ $item->desa->nama_desa ?? '-' }}</td>
                <td>
                    <span class="badge badge-{{ str_replace(' ', '-', $item->kategori ?: 'Lainnya') }}">
                        {{ $item->kategori ?: 'Tidak Terkategori' }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $item->prioritas }}">
                        {{ $item->prioritas }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ str_replace(' ', '-', $item->status) }}">
                        {{ $item->status }}
                    </span>
                </td>
                <td>{{ !empty($item->tanggapan) ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data pengaduan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

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
