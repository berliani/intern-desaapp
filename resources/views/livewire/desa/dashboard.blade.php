<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\ProfilDesa;
use Illuminate\Support\Facades\Auth;

// Menggunakan layout aplikasi utama
new #[Layout('layouts.app')] class extends Component
{
    public string $nama_desa = '';
    public string $kecamatan = '';
    public string $kabupaten = '';
    public string $provinsi = '';
    public string $kode_pos = '';
    public string $alamat = '';
    public string $telepon = '';
    public string $email = '';
    public string $website = '';
    public string $visi = '';
    public string $misi = '';
    public string $sejarah = '';
    public string $otp = '';
    public bool $otpSent = false;
    public bool $otpVerified = false;

    public $user;

    public function mount()
    {
        $this->user = Auth::user();
        $this->nama_desa = $this->user->company->name ?? '';
        if ($this->user->company) {
            $subdomain = $this->user->company->subdomain;
            $this->website = "http://{$subdomain}.desa.local";
        }
    }

    // Step 1: Kirim OTP ke WhatsApp via Twilio
    public function sendOtp()
    {
        $validated = $this->validate([
            'telepon' => ['required', 'string', 'regex:/^(\+62|62|0)8[0-9]{9,15}$/'],
        ]);
        $nomorTelepon = preg_replace('/[^0-9]/', '', $validated['telepon']);
        if (substr($nomorTelepon, 0, 1) === '0') {
            $nomorTelepon = '62' . substr($nomorTelepon, 1);
        }
        $otp = rand(100000, 999999);
        session(['otp' => $otp]);

        // Kirim OTP ke WhatsApp via Twilio
        require_once base_path('vendor/autoload.php');
        $sid    = 'AC9d927572f130906188615a23a41542be'; 
        $token  = 'afae1aa6b74d12f9d7c0ac38ec44f0fd'; 
        $twilio = new \Twilio\Rest\Client($sid, $token); 

        $from = 'whatsapp:+14155238886'; // Nomor sandbox Twilio
        $to   = 'whatsapp:+' . $nomorTelepon; // Format: whatsapp:+628xxxx

        $twilio->messages->create($to, [
            "from" => $from,
            "body" => "Kode verifikasi Anda: $otp"
            // Jika ingin pakai template, tambahkan contentSid dan contentVariables sesuai kebutuhan
            // "contentSid" => "HX229f5a04fd0510ce1b071852155d3e77",
            // "contentVariables" => json_encode(["1" => $otp]),
        ]);

        $this->otpSent = true;
        session()->flash('message', 'Kode OTP telah dikirim ke WhatsApp Anda.');
    }

    // Step 2: Verifikasi OTP
    public function verifyOtp()
    {
        if ($this->otp == session('otp')) {
            $this->otpVerified = true;
            session()->flash('message', 'Nomor WhatsApp berhasil diverifikasi.');
        } else {
            session()->flash('message', 'Kode OTP salah. Silakan coba lagi.');
        }
    }

    // Step 3: Simpan profil desa (hanya jika sudah verifikasi)
    public function saveProfile()
    {
        if (!$this->otpVerified) {
            session()->flash('message', 'Silakan verifikasi nomor WhatsApp terlebih dahulu.');
            return;
        }
        $validated = $this->validate([
            'nama_desa' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:50',
            'kabupaten' => 'required|string|max:50',
            'provinsi' => 'required|string|max:50',
            'kode_pos' => 'required|string|max:5',
            'alamat' => 'required|string|max:255',
            'email' => 'required|email|max:100',
            'website' => 'nullable|url|max:100',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah' => 'nullable|string',
        ]);
        $nomorTelepon = preg_replace('/[^0-9]/', '', $this->telepon);
        if (substr($nomorTelepon, 0, 1) === '0') {
            $nomorTelepon = '62' . substr($nomorTelepon, 1);
        }
        ProfilDesa::create([
            'company_id' => $this->user->company_id,
            'created_by' => $this->user->id,
            'nama_desa' => $validated['nama_desa'],
            'kecamatan' => $validated['kecamatan'],
            'kabupaten' => $validated['kabupaten'],
            'provinsi' => $validated['provinsi'],
            'kode_pos' => $validated['kode_pos'],
            'alamat' => $validated['alamat'],
            'telepon' => $nomorTelepon,
            'email' => $validated['email'],
            'website' => $validated['website'],
            'visi' => $validated['visi'],
            'misi' => $validated['misi'],
            'sejarah' => $validated['sejarah'],
        ]);
        session()->flash('message', 'Profil desa berhasil disimpan! Selamat datang di website desa Anda.');
        return $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lengkapi Profil Desa Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="p-2 bg-emerald-100 rounded-lg text-emerald-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Formulir Profil Desa</h3>
                </div>

                <!-- Step 1: Verifikasi WhatsApp -->
                @if(!$otpVerified)
                <div>
                    <x-input-label for="telepon" value="Nomor WhatsApp" />
                    <x-text-input wire:model="telepon" id="telepon" type="text" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                    @if(!$otpSent)
                        <button type="button" wire:click="sendOtp" class="mt-2 px-3 py-1 bg-emerald-500 text-white rounded">Kirim OTP</button>
                    @endif
                    @if($otpSent)
                        <div class="mt-2">
                            <x-input-label for="otp" value="Masukkan Kode OTP" />
                            <x-text-input wire:model="otp" id="otp" type="text" class="mt-1 block w-full" />
                            <button type="button" wire:click="verifyOtp" class="mt-2 px-3 py-1 bg-emerald-500 text-white rounded">Verifikasi OTP</button>
                        </div>
                    @endif
                </div>
                @endif

                <!-- Step 2: Form Profil Desa, hanya tampil jika sudah verifikasi -->
                @if($otpVerified)
                <form wire:submit="saveProfile" class="space-y-6 mt-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="nama_desa" value="Nama Desa" />
                            <x-text-input wire:model="nama_desa" id="nama_desa" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('nama_desa')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kecamatan" value="Kecamatan" />
                            <x-text-input wire:model="kecamatan" id="kecamatan" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('kecamatan')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kabupaten" value="Kabupaten/Kota" />
                            <x-text-input wire:model="kabupaten" id="kabupaten" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('kabupaten')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="provinsi" value="Provinsi" />
                            <x-text-input wire:model="provinsi" id="provinsi" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('provinsi')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kode_pos" value="Kode Pos" />
                            <x-text-input wire:model="kode_pos" id="kode_pos" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('kode_pos')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="alamat" value="Alamat Kantor Desa" />
                            <textarea wire:model="alamat" id="alamat" rows="3" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required></textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="email" value="Email Desa" />
                            <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="website" value="Website" />
                            <x-text-input wire:model="website" id="website" type="url" class="mt-1 block w-full bg-gray-100" readonly />
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="visi" value="Visi (Opsional)" />
                            <textarea wire:model="visi" id="visi" rows="4" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"></textarea>
                            <x-input-error :messages="$errors->get('visi')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="misi" value="Misi (Opsional)" />
                            <textarea wire:model="misi" id="misi" rows="4" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"></textarea>
                            <x-input-error :messages="$errors->get('misi')" class="mt-2" />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="sejarah" value="Sejarah Singkat (Opsional)" />
                            <textarea wire:model="sejarah" id="sejarah" rows="4" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"></textarea>
                            <x-input-error :messages="$errors->get('sejarah')" class="mt-2" />
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-8 pt-4 border-t border-gray-100">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg wire:loading wire:target="saveProfile" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Simpan Profil Desa
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>