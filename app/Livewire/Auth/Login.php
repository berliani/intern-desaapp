<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    public function mount()
    {

    }

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));
            return;
        }

        session()->regenerate();

        $user = Auth::user();
        $tenant = $user->company;

        if (!$tenant) {
            Auth::logout();
            session()->flash('error', 'Akun Anda tidak terhubung dengan desa manapun.');
            return $this->redirect(route('login'));
        }

        $panel = Filament::getPanel('admin');
        $tenantUrl = $panel->getTenantUrl($tenant);

        return redirect()->to($tenantUrl);
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.guest');
    }
}
