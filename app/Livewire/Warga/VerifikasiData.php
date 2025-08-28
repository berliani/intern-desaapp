<?php

namespace App\Livewire\Warga;

use App\Models\ProfilDesa;
use App\Models\User;
use App\Models\VerifikasiPenduduk;
use App\Models\Company;
use App\Models\ProfilDesa; // <-- DITAMBAHKAN
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Http\Request;

#[Layout('layouts.app')]
class VerifikasiData extends Component
{
    public $verifikasiPending;
    public $nik;
    public $kk;
    public $nama;
    public $alamat;
    public $rt_rw;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $agama;
    public $status_perkawinan;
    public $kepala_keluarga = false;
    public $pekerjaan;
    public $pendidikan;
    public $no_hp;
    public $email;
    public $golongan_darah;

    public bool $hasRegisteredWithEmail = false;
    public bool $hasRegisteredWithPhone = false;

    public function mount()
    {
        $user = Auth::user();

        $this->hasRegisteredWithEmail = !empty($user->email_encrypted);
        $this->hasRegisteredWithPhone = !empty($user->telepon_encrypted);

        $this->verifikasiPending = VerifikasiPenduduk::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();
            
        if ($this->verifikasiPending && $this->verifikasiPending->status === 'approved') {
            return redirect()->route('warga.dashboard');
        }

        if (!$this->verifikasiPending) {
            $this->nama = $user->name;
            $this->nik = $user->nik;
            $this->email = $user->email;
            $this->no_hp = $user->telepon; 
        }
    }

    protected function rules()
    {
        $rules = [
            'nik' => 'required|string|size:16',
            'kk' => 'required|string|size:16',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:100',
            'rt_rw' => 'required|string|max:7',
            'tempat_lahir' => 'required|string|max:20',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'kepala_keluarga' => 'required|boolean',
            'pekerjaan' => 'required|string|max:100',
            'pendidikan' => 'required|in:Tidak Sekolah,Belum Sekolah,SD,SMP,SMA,D1,D2,D3,D4/S1,S2,S3',
            'golongan_darah' => 'required|in:A,B,AB,O,Tidak Tahu',
        ];

        if ($this->hasRegisteredWithEmail) {
            $rules['email'] = 'required|email|max:100';
        } else {
            $rules['email'] = 'nullable|email|max:100';
        }

        if ($this->hasRegisteredWithPhone) {
            $rules['no_hp'] = 'required|string|max:16';
        } else {
            $rules['no_hp'] = 'nullable|string|max:16';
        }

        return $rules;
    }
    
    public function submit()
    {
        $validatedData = $this->validate();
 
        if (isset($validatedData['pendidikan']) && $validatedData['pendidikan'] === 'D4') {
            $validatedData['pendidikan'] = 'D4/S1';
        }

        $user = Auth::user();

        if (!$this->hasRegisteredWithEmail && !empty($validatedData['email'])) {
            $emailSearchHash = hash('sha256', strtolower($validatedData['email']));
            if (User::where('email_search_hash', $emailSearchHash)->where('id', '!=', $user->id)->exists()) {
                $this->addError('email', 'Alamat email ini sudah digunakan oleh akun lain.');
                return;
            }
        }

        if (!$this->hasRegisteredWithPhone && !empty($validatedData['no_hp'])) {
            $normalizedPhone = $this->normalizePhoneNumber($validatedData['no_hp']);
            $teleponSearchHash = hash('sha256', $normalizedPhone);
            if (User::where('telepon_search_hash', $teleponSearchHash)->where('id', '!=', $user->id)->exists()) {
                $this->addError('no_hp', 'Nomor HP ini sudah digunakan oleh akun lain.');
                return;
            }
        }

        $nikSearchHash = hash_hmac('sha256', $validatedData['nik'], hex2bin(env('IMS_PEPPER_KEY')));
        $existingVerification = VerifikasiPenduduk::where('nik_search_hash', $nikSearchHash)->first();

        if ($existingVerification) {
            $this->addError('nik', 'NIK ini sudah pernah diajukan untuk verifikasi.');
            return;
        }
        
        $profilDesa = ProfilDesa::where('company_id', $user->company_id)->first();
        if (!$profilDesa) {
            session()->flash('error', 'Profil desa tidak ditemukan. Harap hubungi admin.');
            return;
        }

        VerifikasiPenduduk::create(array_merge($validatedData, [
            'user_id' => $user->id,
            'id_desa' => $profilDesa->id,
            'company_id' => $user->company_id,
            'status' => 'pending',
        ]));

        $this->mount();
        session()->flash('status', 'Data verifikasi Anda telah berhasil dikirim dan sedang menunggu persetujuan admin.');
    }

    private function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) return null;
        $number = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (substr($number, 0, 1) === '0') {
            return '62' . substr($number, 1);
        }
        if (substr($number, 0, 2) !== '62') {
            return '62' . $number;
        }
        return $number;
    }

    public function render()
    {
        return view('livewire.warga.verifikasi-data');
    }
}
