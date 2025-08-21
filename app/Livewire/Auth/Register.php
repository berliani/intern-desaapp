<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Auth;
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

    // Menambahkan method untuk auto-fill nama ketika NIK ditemukan
    public function updatedNik()
    {
        if (strlen($this->nik) === 16) {
            $penduduk = Penduduk::where('nik', $this->nik)->first();
            if ($penduduk) {
                $this->name = $penduduk->nama;
            }
        }
    }

    public function register()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'nik' => ['required', 'string', 'size:16'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Cari penduduk berdasarkan NIK
        $penduduk = Penduduk::where('nik', $this->nik)->first();

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
