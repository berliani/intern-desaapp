<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use App\Models\VerifikasiPenduduk;
use App\Models\Company;
use App\Models\ProfilDesa; // <-- DITAMBAHKAN
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $this->nik = $user->nik; // Mengambil NIK dari user yang login

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
            // --- Validasi keunikan NIK menggunakan hash ---
            'nik' => [
                'required', 'string', 'size:16',
                function ($attribute, $value, $fail) {
                    $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                    $searchHash = hash_hmac('sha256', $value, $pepperKey);

                    // Cek hanya jika user ini belum pernah mengajukan verifikasi
                    if (!$this->verifikasiPending) {
                        $existsInPenduduk = DB::table('penduduk')->where('nik_search_hash', $searchHash)->exists();
                        $existsInVerifikasi = DB::table('verifikasi_penduduk')->where('nik_search_hash', $searchHash)->exists();
                        if ($existsInPenduduk || $existsInVerifikasi) {
                            $fail('NIK yang Anda masukkan sudah terdaftar atau sedang dalam proses verifikasi.');
                        }
                    }
                }
            ],
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

            // Menggunakan updateOrCreate untuk mencegah duplikasi data jika user me-refresh halaman
            VerifikasiPenduduk::updateOrCreate(
                ['user_id' => Auth::id()], // Kunci untuk mencari
                array_merge($validatedData, [ // Data untuk diisi atau diperbarui
                    'company_id' => $company->id,
                    'desa_id' => $profilDesa->id,
                    'status' => 'pending'
                ])
            );

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
