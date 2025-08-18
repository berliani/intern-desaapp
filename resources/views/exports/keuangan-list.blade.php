<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Force landscape orientation -->
    <meta name="pdfOrientation" content="landscape">
    <title>Laporan Keuangan Desa</title>
    <style>
        /* Force landscape orientation with reduced margins */
        @page {
            size: A4 landscape;
            margin: 15mm 10mm 15mm 10mm; /* top right bottom left - reduced horizontal margins */
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            font-size: 10px;
            margin: 0;
            padding: 0;
            width: 98%; /* Increased width from 95% to 98% */
            max-width: 98%;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            padding-right: 5mm; /* Reduced right padding */
            padding-left: 5mm; /* Reduced left padding */
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            width: 100%;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
        }

        /* Make tables slightly narrower */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed; /* Use fixed layout for more control */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 4px; /* Slightly reduced padding */
            text-align: left;
            overflow: hidden; /* Prevent content overflow */
            word-wrap: break-word; /* Allow long words to break */
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .amount {
            text-align: right;
            font-weight: bold;
        }
        .pemasukan {
            color: #28a745;
        }
        .pengeluaran {
            color: #dc3545;
        }
        .filters {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            background-color: #f9f9f9;
            padding: 5px 8px; /* Slightly reduced padding */
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }
        .filter-item {
            margin-right: 8px; /* Slightly reduced margin */
        }
        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            display: flex;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }
        .text-right {
            text-align: right;
        }

        /* Ringkasan Table Style */
        .ringkasan-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            table-layout: fixed; /* Force fixed layout */
        }
        .ringkasan-table th {
            background-color: #2c3e50;
            color: white;
            font-size: 11px;
            text-align: center;
            padding: 5px; /* Slightly reduced padding */
        }
        .ringkasan-table td {
            padding: 5px 6px; /* Slightly reduced padding */
            font-size: 11px;
        }
        .ringkasan-table .value {
            text-align: right;
            font-weight: bold;
            font-size: 12px;
        }
        .ringkasan-table .label {
            width: 40%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>LAPORAN KEUANGAN DESA</h2>
            <p>Periode: {{ $filters['dariTanggal'] ? \Carbon\Carbon::parse($filters['dariTanggal'])->format('d/m/Y') : 'Awal' }} - {{ $filters['sampaiTanggal'] ? \Carbon\Carbon::parse($filters['sampaiTanggal'])->format('d/m/Y') : 'Sekarang' }}</p>
        </div>

        <div class="filters">
            <div>
                <strong>Filter:</strong>
                @if ($filters['jenis'])
                    <span class="filter-item">Jenis: {{ $filters['jenis'] }}</span>
                @endif
                @if ($filters['dariTanggal'])
                    <span class="filter-item">Dari: {{ \Carbon\Carbon::parse($filters['dariTanggal'])->format('d/m/Y') }}</span>
                @endif
                @if ($filters['sampaiTanggal'])
                    <span class="filter-item">Sampai: {{ \Carbon\Carbon::parse($filters['sampaiTanggal'])->format('d/m/Y') }}</span>
                @endif
                @if (!$filters['jenis'] && !$filters['dariTanggal'] && !$filters['sampaiTanggal'])
                    <span class="filter-item">Semua transaksi</span>
                @endif
            </div>
            <div>
                <strong>Total Data:</strong> {{ $keuanganList->count() }} transaksi
            </div>
        </div>



        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="13%">Desa</th>
                    <th width="7%">Tanggal</th>
                    <th width="28%">Deskripsi</th>
                    <th width="7%">Jenis</th>
                    <th width="11%">Jumlah</th>
                    <th width="12%">Dibuat Oleh</th>
                    <th width="9%">Waktu Input</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keuanganList as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->desa->nama_desa ?? 'N/A' }}</td>
                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $item->deskripsi }}</td>
                        <td style="color: {{ $item->jenis === 'Pemasukan' ? '#28a745' : '#dc3545' }}; font-weight: bold;">
                            {{ $item->jenis }}
                        </td>
                        <td class="amount {{ $item->jenis === 'Pemasukan' ? 'pemasukan' : 'pengeluaran' }}">
                            Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>
                        <td>{{ $item->creator->name ?? 'N/A' }}</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
     <!-- Tabel Ringkasan Keuangan yang Sederhana -->
     @php
     $jenisCounts = $keuanganList->groupBy('jenis')->map->count();
     $jenisTotals = $keuanganList->groupBy('jenis')->map(function ($items) {
         return $items->sum('jumlah');
     });

     $avgPemasukan = $jenisCounts['Pemasukan'] ?? 0 > 0
         ? ($jenisTotals['Pemasukan'] ?? 0) / $jenisCounts['Pemasukan']
         : 0;

     $avgPengeluaran = $jenisCounts['Pengeluaran'] ?? 0 > 0
         ? ($jenisTotals['Pengeluaran'] ?? 0) / $jenisCounts['Pengeluaran']
         : 0;
 @endphp

 <table class="ringkasan-table">
     <tr>
         <th colspan="4">RINGKASAN KEUANGAN</th>
     </tr>
     <tr>
         <td class="label"><strong>Total Pemasukan</strong></td>
         <td class="value pemasukan">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
         <td class="label"><strong>Jumlah Transaksi Pemasukan</strong></td>
         <td class="value">{{ $jenisCounts['Pemasukan'] ?? 0 }} transaksi</td>
     </tr>
     <tr>
         <td class="label"><strong>Total Pengeluaran</strong></td>
         <td class="value pengeluaran">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
         <td class="label"><strong>Jumlah Transaksi Pengeluaran</strong></td>
         <td class="value">{{ $jenisCounts['Pengeluaran'] ?? 0 }} transaksi</td>
     </tr>
     <tr>
         <td class="label"><strong>Saldo Akhir</strong></td>
         <td class="value {{ $saldo >= 0 ? 'pemasukan' : 'pengeluaran' }}">
             Rp {{ number_format(abs($saldo), 0, ',', '.') }} {{ $saldo < 0 ? '(Defisit)' : '' }}
         </td>
         <td class="label"><strong>Total Transaksi</strong></td>
         <td class="value">{{ $keuanganList->count() }} transaksi</td>
     </tr>
     <tr>
         <td class="label"><strong>Rata-rata Pemasukan</strong></td>
         <td class="value pemasukan">Rp {{ number_format($avgPemasukan, 0, ',', '.') }}</td>
         <td class="label"><strong>Rata-rata Pengeluaran</strong></td>
         <td class="value pengeluaran">Rp {{ number_format($avgPengeluaran, 0, ',', '.') }}</td>
     </tr>
 </table>
        <div class="footer">
            <div>
                <p>Catatan: Dokumen ini dicetak dalam orientasi landscape.</p>
            </div>
            <div style="text-align: right;">
                <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
                <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
