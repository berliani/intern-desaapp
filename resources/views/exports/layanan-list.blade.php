<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Force landscape orientation -->
    <meta name="pdfOrientation" content="landscape">
    <title>Daftar Layanan Desa</title>
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

        .layanan-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        .layanan-table th, .layanan-table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            overflow: hidden;
            word-wrap: break-word;
        }

        .layanan-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .layanan-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: #fff;
        }

        .badge-surat { background-color: #3b82f6; }
        .badge-kesehatan { background-color: #10b981; }
        .badge-pendidikan { background-color: #f59e0b; }
        .badge-sosial { background-color: #8b5cf6; }
        .badge-infrastruktur { background-color: #ef4444; }
        .badge-lainnya { background-color: #6b7280; }

        .footer {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }

        .stats-box {
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            overflow: hidden;
        }

        .stats-header {
            background-color: #2c3e50;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 11px;
        }

        .stats-content {
            display: flex;
            flex-wrap: wrap;
            padding: 8px;
            background-color: #f9f9f9;
        }

        .stats-item {
            margin-right: 20px;
            margin-bottom: 5px;
        }

        .stats-item span {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR LAYANAN DESA</h1>
        @if(isset($filter))
            <p class="subtitle">
                {{ isset($filter['kategori']) && $filter['kategori'] ? 'Kategori: '.$filter['kategori'].' | ' : '' }}
                @if(isset($filter['dari_tanggal']) && $filter['dari_tanggal'])
                    Periode: {{ $filter['dari_tanggal'] }} - {{ $filter['sampai_tanggal'] ?? 'Sekarang' }}
                @endif
            </p>
        @endif
    </div>

    <div class="filters">
        <div>
            <strong>Filter:</strong>
            @if(isset($filter['kategori']) && $filter['kategori'])
                <span class="filter-item">Kategori: {{ $filter['kategori'] }}</span>
            @endif
            @if(isset($filter['dari_tanggal']) && $filter['dari_tanggal'])
                <span class="filter-item">Dari: {{ $filter['dari_tanggal'] }}</span>
            @endif
            @if(isset($filter['sampai_tanggal']) && $filter['sampai_tanggal'])
                <span class="filter-item">Sampai: {{ $filter['sampai_tanggal'] }}</span>
            @endif
            @if(!isset($filter) || (!isset($filter['kategori']) && !isset($filter['dari_tanggal']) && !isset($filter['sampai_tanggal'])))
                <span class="filter-item">Semua layanan</span>
            @endif
        </div>
        <div>
            <strong>Total Data:</strong> {{ isset($layanans) ? $layanans->count() : 0 }} layanan
        </div>
    </div>

    <table class="layanan-table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="18%">Nama Layanan</th>
                <th width="8%">Kategori</th>
                <th width="9%">Biaya</th>
                <th width="12%">Lokasi</th>
                <th width="15%">Jadwal</th>
                <th width="12%">Kontak</th>
                <th width="10%">Desa</th>
                <th width="13%">Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($layanans) && $layanans->count() > 0)
                @foreach($layanans as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_layanan }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower(str_replace(' ', '', $item->kategori)) }}">
                            {{ $item->kategori }}
                        </span>
                    </td>
                    <td>
                        @if($item->biaya == 0)
                            <strong style="color: #10b981;">Gratis</strong>
                        @else
                            Rp {{ number_format($item->biaya, 0, ',', '.') }}
                        @endif
                    </td>
                    <td>{{ $item->lokasi_layanan ?? '-' }}</td>
                    <td>{{ $item->jadwal_pelayanan ?? '-' }}</td>
                    <td>{{ $item->kontak_layanan ?? '-' }}</td>
                    <td>{{ $item->desa->nama_desa ?? 'N/A' }}</td>
                    <td>{{ $item->creator->name ?? 'Sistem' }}</td>
                </tr>
                @endforeach
            @else
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data layanan</td>
            </tr>
            @endif
        </tbody>
    </table>

    @if(isset($layanans) && $layanans->count() > 0)
    <!-- Statistik Layanan dalam Format Tabel -->
    @php
        $kategoriStats = $layanans->groupBy('kategori')->map->count();
        $biayaStats = [
            'Gratis' => $layanans->where('biaya', 0)->count(),
            'Berbayar' => $layanans->where('biaya', '>', 0)->count()
        ];
        $desaStats = $layanans->groupBy('desa.nama_desa')->map->count();

        // Menentukan desa dengan layanan terbanyak
        $maxCount = 0;
        $maxDesa = '';
        foreach($desaStats as $desa => $count) {
            if($count > $maxCount) {
                $maxCount = $count;
                $maxDesa = $desa ?: 'N/A';
            }
        }
    @endphp

    <table class="layanan-table">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center;">STATISTIK LAYANAN</th>
            </tr>
            <tr>
                <th colspan="2" style="width: 33%;">Berdasarkan Kategori</th>
                <th colspan="2" style="width: 33%;">Berdasarkan Biaya</th>
                <th colspan="2" style="width: 33%;">Desa Terbanyak</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2" style="vertical-align: top;">
                    <table style="width: 100%; border: none;">
                        <tbody>
                            @foreach($kategoriStats as $kategori => $count)
                            <tr>
                                <td style="border: none; width: 70%;">{{ $kategori }}</td>
                                <td style="border: none; width: 30%; text-align: right; font-weight: bold;">{{ $count }} layanan</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td colspan="2" style="vertical-align: top;">
                    <table style="width: 100%; border: none;">
                        <tbody>
                            <tr>
                                <td style="border: none; width: 70%;">Layanan Gratis</td>
                                <td style="border: none; width: 30%; text-align: right; font-weight: bold;">{{ $biayaStats['Gratis'] }} layanan</td>
                            </tr>
                            <tr>
                                <td style="border: none; width: 70%;">Layanan Berbayar</td>
                                <td style="border: none; width: 30%; text-align: right; font-weight: bold;">{{ $biayaStats['Berbayar'] }} layanan</td>
                            </tr>
                            <tr>
                                <td style="border: none; width: 70%;">Total</td>
                                <td style="border: none; width: 30%; text-align: right; font-weight: bold;">{{ $layanans->count() }} layanan</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td colspan="2" style="vertical-align: top;">
                    <table style="width: 100%; border: none;">
                        <tbody>
                            <tr>
                                <td style="border: none; width: 70%;">{{ $maxDesa }}</td>
                                <td style="border: none; width: 30%; text-align: right; font-weight: bold;">{{ $maxCount }} layanan</td>
                            </tr>
                            <tr>
                                <td style="border: none; width: 70%;">Jumlah Desa</td>
                                <td style="border: none; width: 30%; text-align: right; font-weight: bold;">{{ count($desaStats) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    @endif

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