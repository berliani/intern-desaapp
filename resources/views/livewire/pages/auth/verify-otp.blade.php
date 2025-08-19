<div>
    <h1>Verifikasi OTP</h1>
    <form method="POST" action="{{ route('verify-otp') }}">
        @csrf
        <input type="text" name="otp" placeholder="Masukkan kode OTP">
        <button type="submit">Verifikasi</button>
    </form>
</div>
