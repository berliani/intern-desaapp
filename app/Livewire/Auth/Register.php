<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $nik = '';
    public $password = '';
    public $passwordConfirmation = '';

    /**
     * Mengisi nama secara otomatis ketika NIK ditemukan.
     * Logika ini sudah disiapkan untuk bekerja setelah tabel 'penduduk' dienkripsi.
     */
    public function updatedNik()
    {
        if (strlen($this->nik) === 16) {
            $penduduk = null;
            // Cek apakah tabel penduduk sudah memiliki kolom hash (untuk masa depan)
            if (DB::getSchemaBuilder()->hasColumn('penduduk', 'nik_search_hash')) {
                $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                $searchHash = hash_hmac('sha256', $this->nik, $pepperKey);
                $penduduk = Penduduk::where('nik_search_hash', $searchHash)->first();
            } else {
                // Cara lama jika tabel penduduk belum dienkripsi
                $penduduk = Penduduk::where('nik', $this->nik)->first();
            }

            if ($penduduk) {
                $this->name = $penduduk->nama;
            }
        }
    }

    /**
     * Menangani proses pendaftaran user baru.
     */
    public function register()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                // Validasi keunikan email menggunakan hash
                function ($attribute, $value, $fail) {
                    $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                    $searchHash = hash_hmac('sha256', $value, $pepperKey);
                    $exists = DB::table('users')->where('email_search_hash', $searchHash)->exists();
                    if ($exists) {
                        $fail('Email yang Anda masukkan sudah terdaftar.');
                    }
                }
            ],
            'nik' => [
                'required', 'string', 'size:16',
                // PERBAIKAN: Validasi keunikan NIK menggunakan hash
                function ($attribute, $value, $fail) {
                    $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                    $searchHash = hash_hmac('sha256', $value, $pepperKey);
                    $exists = DB::table('users')->where('nik_search_hash', $searchHash)->exists();
                    if ($exists) {
                        $fail('NIK yang Anda masukkan sudah terdaftar.');
                    }
                }
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Cari penduduk berdasarkan hash NIK (dengan fallback)
        $penduduk = null;
        if (DB::getSchemaBuilder()->hasColumn('penduduk', 'nik_search_hash')) {
            $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
            $nikSearchHash = hash_hmac('sha256', $this->nik, $pepperKey);
            $penduduk = Penduduk::where('nik_search_hash', $nikSearchHash)->first();
        } else {
            $penduduk = Penduduk::where('nik', $this->nik)->first();
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'nik' => $this->nik,
            'password' => Hash::make($this->password),
            'penduduk_id' => $penduduk?->id,
        ]);

        if ($penduduk) {
             // Jika NIK terdaftar, berikan role warga
            $user->assignRole('warga');
            session()->flash('message', 'Pendaftaran berhasil! Data NIK ditemukan, akun Anda sudah terverifikasi.');
            $redirectTo = '/warga/dashboard';
        } else {
            // Jika NIK tidak terdaftar, berikan role unverified
            $user->assignRole('unverified');
            session()->flash('warning', 'Pendaftaran berhasil! Silakan lengkapi data verifikasi Anda.');
            $redirectTo = '/verifikasi-data';
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect($redirectTo);
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.guest');
    }
}
