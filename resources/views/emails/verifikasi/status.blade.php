<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Status Verifikasi Data</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            background-color: #f8fafc;
            color: #718096;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .wrapper {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 0 auto;
            max-width: 600px;
            padding: 40px;
        }
        h1 {
            color: #3d4852;
            font-size: 24px;
            font-weight: bold;
            margin-top: 0;
            text-align: left;
        }
        p {
            font-size: 16px;
            margin-top: 0;
        }
        .panel {
            background-color: #edf2f7;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 25px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Bagian ini akan berubah tergantung status "approved" -->
        @if ($verifikasi->status === 'approved')
        <h1>Verifikasi Data Kependudukan Disetujui</h1>
        <p>Halo <strong>{{ $verifikasi->nama }}</strong>,</p>
        <p>Kabar baik! Pengajuan verifikasi data kependudukan Anda telah <strong>DISETUJUI</strong>.</p>
        <p>Anda sekarang dapat login dan mengakses semua layanan warga yang tersedia di portal desa kami.</p>
        @else
        <!-- atau bagian ini, jika status "rejected" -->
        <h1>Verifikasi Data Kependudukan Ditolak</h1>
        <p>Halo <strong>{{ $verifikasi->nama }}</strong>,</p>
        <p>Mohon maaf, setelah peninjauan, pengajuan verifikasi data kependudukan Anda <strong>DITOLAK</strong>.</p>
        <p><strong>Alasan Penolakan:</strong></p>
        <div class="panel">
            {{ $verifikasi->catatan }}
        </div>
        <p>Jika Anda memiliki pertanyaan lebih lanjut atau merasa ini adalah sebuah kekeliruan, silakan hubungi kantor desa kami secara langsung.</p>
        @endif

        <p>
            Terima kasih,<br>
            Pemerintah {{ config('app.name') }}
        </p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</body>
</html>
