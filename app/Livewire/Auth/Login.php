<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function mount()
    {
        // Fungsi ini bisa dibiarkan kosong. Middleware Filament akan menangani
        // pengguna yang sudah login.
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            // PENTING: JANGAN LAKUKAN REDIRECT DI SINI.
            // Biarkan method ini selesai. Filament akan secara otomatis
            // mendeteksi login yang berhasil dan mengarahkan pengguna
            // ke halaman yang benar (termasuk ke subdomain yang sesuai).
            return;
        }

        $this->addError('email', trans('auth.failed'));
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.guest');
    }
}
