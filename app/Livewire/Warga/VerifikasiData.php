<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use App\Models\VerifikasiPenduduk;
use App\Models\Company;
use App\Models\ProfilDesa; // <-- DITAMBAHKAN
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Http\Request;

#[Layout('layouts.app')]
class VerifikasiData extends Component
{
    // Properti untuk menampung data form
    public $nik = '';
    public $kk = '';
    public $nama = '';
    public $email = '';
    public $no_hp = '';
    public $tempat_lahir = '';
    public $tanggal_lahir = '';
    public $jenis_kelamin = '';
    public $golongan_darah = '';
    public $alamat = '';
    public $rt_rw = '';
    public $agama = '';
    public $status_perkawinan = '';
    public $pekerjaan = '';
    public $pendidikan = '';
    public $kepala_keluarga = false;

    public $verifikasiPending;

    public function mount()
    {
        $user = Auth::user();
        $this->nama = $user->name;
        $this->email = $user->email;

        $this->verifikasiPending = VerifikasiPenduduk::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($this->verifikasiPending) {
            if ($this->verifikasiPending->status === 'approved') {
                return redirect()->route('warga.dashboard');
            }
        }
    }

    protected function rules()
    {
        return [
            'nik' => 'required|string|size:16|unique:verifikasi_penduduk,nik',
            'kk' => 'required|string|size:16',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'rt_rw' => 'required|string|max:7',
            'agama' => 'required|string',
            'status_perkawinan' => 'required|string',
            'pekerjaan' => 'nullable|string',
            'pendidikan' => 'nullable|string',
            'kepala_keluarga' => 'boolean',
            'no_hp' => 'nullable|string|max:15',
            'email' => 'required|email|max:255',
            'golongan_darah' => 'nullable|string|max:3',
        ];
    }

    public function submit(Request $request)
    {
        $validatedData = $this->validate();

        try {
            // Mengambil company (desa) dari middleware subdomain
            $company = $request->attributes->get('company');
            if (!$company) {
                throw new \Exception('Tidak dapat menemukan data desa. Pastikan Anda berada di subdomain yang benar.');
            }

            // Cari profil desa yang sesuai berdasarkan company
            $profilDesa = ProfilDesa::where('company_id', $company->id)->first();
            if (!$profilDesa) {
                throw new \Exception('Profil desa untuk wilayah ini belum lengkap. Hubungi admin.');
            }

            VerifikasiPenduduk::create(array_merge($validatedData, [
                'user_id' => Auth::id(),
                'company_id' => $company->id,
                'id_desa' => $profilDesa->id, // <-- FIX: Menambahkan id_desa yang benar
                'status' => 'pending'
            ]));

            session()->flash('message', 'Data verifikasi berhasil dikirim dan akan segera diproses oleh admin.');
            $this->mount(); // Refresh komponen

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.warga.verifikasi-data');
    }
}
