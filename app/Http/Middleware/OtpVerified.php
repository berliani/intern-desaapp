<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OtpVerified
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah session 'otp_verified' ada dan bernilai true
        if (session('otp_verified')) {
            // Jika ya, izinkan akses ke halaman berikutnya
            return $next($request);
        }

        // Jika tidak, paksa kembali ke halaman awal pendaftaran
        return redirect()->route('pre-register')->with('error', 'Anda harus verifikasi email terlebih dahulu.');
    }
}