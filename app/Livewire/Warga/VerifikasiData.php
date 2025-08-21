<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use App\Models\VerifikasiPenduduk;
use App\Models\ProfilDesa;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class VerifikasiData extends Component
{
    public $nik;
    public $kk;
    public $nama;
    public $tanggal_lahir;
    public $alamat;
    public $rt_rw;
    public $pekerjaan;
    public $pendidikan;
    public $kepala_keluarga = false;
    public $tempat_lahir = '';
    public $jenis_kelamin = '';
    public $agama = '';
    public $status_perkawinan = '';
    public $no_hp = '';
    public $email = '';
    public $golongan_darah = '';
    public $verifikasiPending;

    public function mount()
    {
        $user = Auth::user();
        $this->nik = $user->nik;
        $this->nama = $user->name;
        $this->email = $user->email;

        $this->verifikasiPending = VerifikasiPenduduk::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($this->verifikasiPending) {
            if ($this->verifikasiPending->status === 'pending') {
                session()->flash('warning', 'Pengajuan verifikasi data Anda sedang dalam proses review oleh admin.');
            } elseif ($this->verifikasiPending->status === 'approved') {
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
            'rt_rw' => 'required|string',
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

    public function submit()
    {
        $this->validate();

        try {
            // Get default desa
            $desa = ProfilDesa::first();
            if (!$desa) {
                throw new \Exception('Data desa tidak ditemukan.');
            }

            // Create verifikasi data
            VerifikasiPenduduk::create([
                'user_id' => Auth::id(),
                'id_desa' => $desa->id,
                'nik' => $this->nik,
                'kk' => $this->kk,
                'nama' => $this->nama,
                'tempat_lahir' => $this->tempat_lahir,
                'tanggal_lahir' => $this->tanggal_lahir,
                'jenis_kelamin' => $this->jenis_kelamin,
                'alamat' => $this->alamat,
                'rt_rw' => $this->rt_rw,
                'agama' => $this->agama,
                'status_perkawinan' => $this->status_perkawinan,
                'pekerjaan' => $this->pekerjaan,
                'pendidikan' => $this->pendidikan,
                'kepala_keluarga' => $this->kepala_keluarga,
                'email' => $this->email,
                'no_hp' => $this->no_hp,
                'golongan_darah' => $this->golongan_darah,
                'status' => 'pending'
            ]);

            session()->flash('message', 'Data verifikasi berhasil dikirim.');
            return redirect()->route('warga.dashboard');

        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.warga.verifikasi-data');
    }
}