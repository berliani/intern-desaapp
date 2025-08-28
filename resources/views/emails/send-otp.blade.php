<!DOCTYPE html>
<html>

<head>
    <title>
        @if ($purpose === 'reset')
        Kode OTP Reset Password
        @else
        Kode Verifikasi Pendaftaran
        @endif
    </title>
</head>

<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    @if ($purpose === 'reset')
    <h1 style="color: #059669;">Reset Password Anda</h1>
    <p>Anda telah meminta untuk mereset password akun Anda. Gunakan kode OTP di bawah ini untuk melanjutkan:</p>
    @else
    <h1 style="color: #059669;">Kode Verifikasi Pendaftaran</h1>
    @if (isset($company) && $company)
    <p>Gunakan kode berikut untuk melanjutkan proses pendaftaran akun warga Anda di <strong>{{ $company->name }}</strong>:</p>
    @else
    <p>Gunakan kode berikut untuk melanjutkan proses pendaftaran desa Anda:</p>
    @endif
    @endif

    <p>Kode Anda adalah:</p>
    <h2 style="font-size: 28px; letter-spacing: 5px; background-color: #f0fdf4; padding: 15px; border-radius: 8px; text-align: center; color: #065f46;">
        <strong>{{ $otp }}</strong>
    </h2>

    <p>
        @if ($purpose === 'reset')
        Kode OTP ini hanya berlaku selama <strong>10 menit</strong>.
        @else
        Kode ini hanya berlaku selama <strong>15 menit</strong>.
        @endif
    </p>
    <p style="font-weight: bold; color: #be123c;">Mohon untuk tidak membagikan kode ini kepada siapapun demi keamanan akun Anda.</p>

    <br>
    <p>Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini.</p>
    <p>Terima kasih.</p>
</body>

</html>