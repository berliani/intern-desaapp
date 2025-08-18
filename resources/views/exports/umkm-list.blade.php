<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Force landscape orientation -->
    <meta name="pdfOrientation" content="landscape">
    <title>Daftar UMKM</title>
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

        .umkm-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        .umkm-table th, .umkm-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            overflow: hidden;
            word-wrap: break-word;
        }

        .umkm-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .umkm-table tr:nth-child(even) {
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

        /* Kategori colors */
        .badge-kuliner {
            border-color: #A3D9B0;
            background-color: #EDFBF0;
        }
        .badge-kerajinan {
            border-color: #9AC1EB;
            background-color: #E9F3FE;
        }
        .badge-fashion {
            border-color: #FECB8F;
            background-color: #FFF5E9;
        }
        .badge-pertanian {
            border-color: #C6A3D9;
            background-color: #F7EDFB;
        }
        .badge-jasa {
            border-color: #EB9A9A;
            background-color: #FEE9E9;
        }
        .badge-lainnya {
            border-color: #C1C6CC;
            background-color: #F1F3F5;
        }

        .verified {
            color: #28a745;
            font-weight: normal;
        }

        .not-verified {
            color: #6c757d;
            font-weight: normal;
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
            font-size: 12px;
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
        <h1>LAPORAN DATA UMKM</h1>
        <p class="subtitle">Tanggal Cetak: {{ $tanggal }}</p>
    </div>

    <div class="filters">
        <div>
            <strong>Filter:</strong>
            @if(isset($filter['kategori']) && $filter['kategori'] !== 'Semua Kategori')
                <span class="filter-item">Kategori: {{ $filter['kategori'] }}</span>
            @endif
            @if(isset($filter['is_verified']) && $filter['is_verified'] !== 'Semua Status')
                <span class="filter-item">Status: {{ $filter['is_verified'] }}</span>
            @endif
            @if(isset($filter['dari_tanggal']) && isset($filter['sampai_tanggal']))
                <span class="filter-item">Periode: {{ $filter['dari_tanggal'] }} s/d {{ $filter['sampai_tanggal'] }}</span>
            @elseif(isset($filter['dari_tanggal']))
                <span class="filter-item">Dari tanggal: {{ $filter['dari_tanggal'] }}</span>
            @elseif(isset($filter['sampai_tanggal']))
                <span class="filter-item">Sampai tanggal: {{ $filter['sampai_tanggal'] }}</span>
            @endif
            @if(!isset($filter) || (
                (!isset($filter['kategori']) || $filter['kategori'] === 'Semua Kategori') &&
                (!isset($filter['is_verified']) || $filter['is_verified'] === 'Semua Status') &&
                !isset($filter['dari_tanggal']) && !isset($filter['sampai_tanggal'])
                ))
                <span class="filter-item">Semua UMKM</span>
            @endif
        </div>
        <div>
            <strong>Total Data:</strong> {{ $umkmList->count() }} UMKM
        </div>
    </div>

    <table class="umkm-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="18%">Nama Usaha</th>
                <th width="13%">Pemilik</th>
                <th width="10%">Kategori</th>
                <th width="18%">Produk/Layanan</th>
                <th width="15%">Lokasi</th>
                <th width="10%">Kontak</th>
                <th width="7%">Status</th>
                <th width="6%">Desa</th>
            </tr>
        </thead>
        <tbody>
            @php
                $kategoriCounts = $umkmList->groupBy('kategori')->map->count();
                $statusCounts = [
                    'Terverifikasi' => $umkmList->where('is_verified', true)->count(),
                    'Belum Terverifikasi' => $umkmList->where('is_verified', false)->count()
                ];
                $desaCounts = $umkmList->groupBy(function($item) {
                    return $item->desa->nama_desa ?? 'Tidak Ada Data';
                })->map->count();
            @endphp

            @forelse($umkmList as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_usaha }}</td>
                <td>{{ $item->penduduk->nama ?? 'N/A' }}</td>
                <td>
                    @php
                        $badgeClass = '';
                        switch($item->kategori) {
                            case 'Kuliner': $badgeClass = 'badge-kuliner'; break;
                            case 'Kerajinan': $badgeClass = 'badge-kerajinan'; break;
                            case 'Fashion': $badgeClass = 'badge-fashion'; break;
                            case 'Pertanian': $badgeClass = 'badge-pertanian'; break;
                            case 'Jasa': $badgeClass = 'badge-jasa'; break;
                            default: $badgeClass = 'badge-lainnya';
                        }
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $item->kategori }}</span>
                </td>
                <td>{{ $item->produk }}</td>
                <td>{{ $item->lokasi }}</td>
                <td>{{ $item->kontak_whatsapp }}</td>
                <td class="{{ $item->is_verified ? 'verified' : 'not-verified' }}">
                    {{ $item->is_verified ? 'Terverifikasi' : 'Belum' }}
                </td>
                <td>{{ $item->desa->nama_desa ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data UMKM</td>
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
