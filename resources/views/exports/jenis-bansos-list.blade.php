<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jenis Bantuan Sosial</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 15px;
            font-size: 10px;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
            font-size: 16px;
        }
        .subtitle {
            text-align: center;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 12px;
            color: #555;
        }
        .header {
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .filter-info {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }
        .filter-info-item {
            margin-right: 15px;
            display: inline-block;
        }
        .section {
            margin-bottom: 15px;
        }
        .bansos-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .bansos-table th, .bansos-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 9px;
        }
        .bansos-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .bansos-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
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
            margin-top: 15px;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .stats-section {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        .stats-box {
            width: 31%;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px;
            background-color: #f9f9f9;
        }
        .stats-box h3 {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: #333;
        }
        .stats-table {
            width: 100%;
            font-size: 9px;
            border-collapse: collapse;
        }
        .stats-table td {
            padding: 3px;
            border: none;
        }
        .stats-table td:last-child {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DAFTAR JENIS BANTUAN SOSIAL</h1>
        <p class="subtitle">Total: {{ isset($jenisBansos) ? $jenisBansos->count() : 0 }} program bantuan</p>

        @if(isset($filter) && ($filter['kategori'] || $filter['bentuk_bantuan'] || $filter['periode'] || $filter['status'] !== null))
            <div class="filter-info">
                <div>
                    @if(isset($filter['kategori']) && $filter['kategori'])
                        <span class="filter-info-item">
                            <strong>Kategori:</strong> {{ $filter['kategori'] }}
                        </span>
                    @endif

                    @if(isset($filter['bentuk_bantuan']) && $filter['bentuk_bantuan'])
                        <span class="filter-info-item">
                            <strong>Bentuk:</strong> {{ $filter['bentuk_bantuan'] }}
                        </span>
                    @endif
                </div>
                <div>
                    @if(isset($filter['periode']) && $filter['periode'])
                        <span class="filter-info-item">
                            <strong>Periode:</strong> {{ $filter['periode'] }}
                        </span>
                    @endif

                    @if(isset($filter['status']) && $filter['status'] !== null && $filter['status'] !== '')
                        <span class="filter-info-item">
                            <strong>Status:</strong> {{ $filter['status'] === 'true' ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="section">
        <table class="bansos-table">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="18%">Nama Program</th>
                    <th width="8%">Kategori</th>
                    <th width="12%">Bentuk Bantuan</th>
                    <th width="12%">Nilai Bantuan</th>
                    <th width="15%">Instansi</th>
                    <th width="10%">Periode</th>
                    <th width="7%">Status</th>
                    <th width="5%">Penerima</th>
                    <th width="10%">Tgl. Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($jenisBansos) && $jenisBansos->count() > 0)
                    @foreach($jenisBansos as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_bansos }}</td>
                        <td>
                            <span class="badge badge-{{ $item->kategori }}">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td>{{ App\Models\JenisBansos::getBentukBantuanOptions()[$item->bentuk_bantuan] ?? '-' }}</td>
                        <td>{{ $item->getNilaiBantuanFormatted() }}</td>
                        <td>{{ $item->instansi_pemberi }}</td>
                        <td>{{ App\Models\JenisBansos::getPeriodeOptions()[$item->periode] ?? '-' }}</td>
                        <td>
                            @if($item->is_active)
                                <span style="color: #10b981; font-weight: bold;">Aktif</span>
                            @else
                                <span style="color: #ef4444; font-weight: bold;">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>{{ $item->bansos_count ?? $item->bansos()->count() }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                @else
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada data jenis bantuan</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        <div>
            <p>Catatan: Dokumen ini dicetak dalam orientasi landscape.</p>
        </div>
        <div style="text-align: right;">
            <p>Dicetak pada {{ $tanggal ?? now()->format('d/m/Y H:i') }}</p>
            <p>Dicetak oleh: {{ auth()->user()->name ?? 'Admin' }}</p>
        </div>
    </div>
</body>
</html>
