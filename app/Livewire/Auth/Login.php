<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function mount()
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            // Regenerate session and clear any potential redirects
            session()->regenerate();
            session()->forget('url.intended');
            session()->forget('_previous');

            // Direct redirection based on role WITHOUT using intended()
            if (auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
                // Hapus semua state dan cache
                session()->flush();  // Lebih kuat dari forget(), hapus semua session

                // Redirect dengan JavaScript untuk memastikan page benar-benar di-reload
                return response()->json([
                    'redirect' => url('/admin?fresh=' . now()->timestamp)
                ])->withHeaders([
                    'HX-Redirect' => url('/admin?fresh=' . now()->timestamp),
                    'X-Livewire-Redirect' => 'true'
                ]);
            } elseif (auth()->user()->hasRole('warga')) {
                return redirect('/warga/dashboard');
            } elseif (auth()->user()->hasRole('unverified')) {
                return redirect('/verifikasi-data');
            }

            return redirect('/dashboard');
        }

        $this->addError('email', trans('auth.failed'));
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.guest');
    }
}
