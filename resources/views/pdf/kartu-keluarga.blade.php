<!DOCTYPE html>
<html>
<head>
    <title>Data Kartu Keluarga</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 6px; }
        .table th { background-color: #f2f2f2; text-align: left; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .page-break { page-break-after: always; }
        .kk-section { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Data Kartu Keluarga</h2>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    @forelse($keluargas as $nomor_kk => $anggota)
        <div class="kk-section">
            @php
                $kepalaKeluarga = $anggota->firstWhere('kepala_keluarga', true);
            @endphp
            <p><strong>Nomor KK:</strong> {{ $nomor_kk }} | <strong>Kepala Keluarga:</strong> {{ $kepalaKeluarga->nama ?? 'Tidak Ditemukan' }} | <strong>Alamat:</strong> {{ $kepalaKeluarga->alamat ?? '' }}</p>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Status Hubungan</th>
                        <th>L/P</th>
                        <th>Tanggal Lahir</th>
                        <th>Pendidikan</th>
                        <th>Pekerjaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anggota as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>'{{ $item->nik }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->kepala_keluarga ? 'Kepala Keluarga' : 'Anggota Keluarga' }}</td>
                            <td>{{ $item->jenis_kelamin }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                            <td>{{ $item->pendidikan }}</td>
                            <td>{{ $item->pekerjaan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @empty
        <p>Tidak ada data untuk ditampilkan.</p>
    @endforelse
</body>
</html>
