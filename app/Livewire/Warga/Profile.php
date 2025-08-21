<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use App\Models\VerifikasiPenduduk;
use Spatie\Permission\Models\Role;

class Profile extends Component
{
    use WithFileUploads;

    public $user;
    public $penduduk;
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';
    public $photo;
    public $pendingVerification = false;

    // Penduduk data fields
    public $nama;
    public $alamat;
    public $rt_rw;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $jenis_kelamin;
    public $agama;
    public $status_perkawinan;
    public $pekerjaan;
    public $pendidikan;
    public $no_hp;
    public $email;
    public $golongan_darah;

    protected $rules = [
        'current_password' => 'required_with:password',
        'password' => 'required_with:current_password|min:8|confirmed',
        'photo' => 'nullable|image|max:1024',

        // Penduduk validation rules
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'rt_rw' => 'required|string',
        'tempat_lahir' => 'required|string',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:L,P',
        'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya',
        'status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
        'pekerjaan' => 'required|string',
        'pendidikan' => 'required|in:Tidak Sekolah,SD/Sederajat,SMP/Sederajat,SMA/Sederajat,D1/D2/D3,D4/S1,S2,S3',
        'no_hp' => 'required|string',
        'email' => 'required|email',
        'golongan_darah' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-,Belum Diketahui',
    ];

    protected $messages = [
        'nama.required' => 'Nama lengkap wajib diisi',
        'nama.max' => 'Nama lengkap maksimal 255 karakter',

        'alamat.required' => 'Alamat tempat tinggal wajib diisi',

        'rt_rw.required' => 'RT/RW wajib diisi (contoh: 001/002)',

        'tempat_lahir.required' => 'Tempat lahir wajib diisi',

        'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
        'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',

        'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
        'jenis_kelamin.in' => 'Pilih salah satu: Laki-laki atau Perempuan',

        'agama.required' => 'Agama wajib dipilih',
        'agama.in' => 'Pilih agama dari daftar yang tersedia',

        'status_perkawinan.required' => 'Status perkawinan wajib dipilih',
        'status_perkawinan.in' => 'Pilih status perkawinan dari daftar yang tersedia',

        'pekerjaan.required' => 'Pekerjaan wajib diisi',

        'pendidikan.required' => 'Pendidikan terakhir wajib dipilih',
        'pendidikan.in' => 'Pilih pendidikan dari daftar yang tersedia',

        'no_hp.required' => 'Nomor HP wajib diisi',

        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid (contoh: nama@domain.com)',

        'golongan_darah.in' => 'Pilih golongan darah dari daftar yang tersedia',

        'current_password.required' => 'Password saat ini wajib diisi',
        'current_password.required_with' => 'Password saat ini wajib diisi',

        'password.required' => 'Password baru wajib diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'password.required_with' => 'Password baru wajib diisi',

        'photo.required' => 'Pilih foto untuk diunggah',
        'photo.image' => 'File harus berupa gambar (JPG, PNG, GIF)',
        'photo.max' => 'Ukuran foto maksimal 1MB',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->penduduk = $this->user->penduduk;

        // Check if user has pending verification
        $this->pendingVerification = VerifikasiPenduduk::where('user_id', $this->user->id)
            ->where('status', 'pending')
            ->exists();

        // Load penduduk data
        if ($this->penduduk) {
            $this->loadPendudukData();
        }
    }

    private function loadPendudukData()
    {
        $this->nama = $this->penduduk->nama;
        $this->alamat = $this->penduduk->alamat;
        $this->rt_rw = $this->penduduk->rt_rw;
        $this->tempat_lahir = $this->penduduk->tempat_lahir;
        $this->tanggal_lahir = $this->penduduk->tanggal_lahir ? $this->penduduk->tanggal_lahir->format('Y-m-d') : null;
        $this->jenis_kelamin = $this->penduduk->jenis_kelamin;
        $this->agama = $this->penduduk->agama;
        $this->status_perkawinan = $this->penduduk->status_perkawinan;
        $this->pekerjaan = $this->penduduk->pekerjaan;
        $this->pendidikan = $this->penduduk->pendidikan;
        $this->no_hp = $this->penduduk->no_hp;
        $this->email = $this->penduduk->email;
        $this->golongan_darah = $this->penduduk->golongan_darah;
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Password saat ini tidak sesuai.');
            return;
        }

        $this->user->update([
            'password' => Hash::make($this->password)
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('message', 'Password berhasil diperbarui.');
    }

    public function updatePhoto()
    {
        $this->validate([
            'photo' => 'required|image|max:1024|mimes:jpg,jpeg,png,gif',
        ], [
            'photo.required' => 'Silakan pilih foto terlebih dahulu.',
            'photo.image' => 'File harus berupa gambar (JPG, PNG, GIF).',
            'photo.max' => 'Ukuran file maksimal 1MB.',
            'photo.mimes' => 'Format file harus JPG, PNG, atau GIF.'
        ]);

        try {
            $path = $this->photo->store('profile-photos', 'public');

            $this->user->update([
                'profile_photo_path' => $path
            ]);

            $this->reset('photo');
            $this->dispatch('photoUploaded');
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Foto profil berhasil diperbarui.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat mengunggah foto: ' . $e->getMessage(),
            ]);
        }
    }

    public function updatePendudukData()
    {
        // If no penduduk data or user already has pending verification, don't allow updates
        if (!$this->penduduk || $this->pendingVerification) {
            session()->flash('error', 'Tidak dapat memperbarui data saat ini. Verifikasi sedang dalam proses.');
            return;
        }

        // Validate penduduk data
        $this->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'rt_rw' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya',
            'status_perkawinan' => 'required|in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati',
            'pekerjaan' => 'required|string',
            'pendidikan' => 'required|in:Tidak Sekolah,SD/Sederajat,SMP/Sederajat,SMA/Sederajat,D1/D2/D3,D4/S1,S2,S3',
            'no_hp' => 'required|string',
            'email' => 'required|email',
            'golongan_darah' => 'nullable|in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-,Belum Diketahui',
        ]);

        // Create verification request
        VerifikasiPenduduk::create([
            'user_id' => $this->user->id,
            'penduduk_id' => $this->penduduk->id,
            'id_desa' => $this->penduduk->id_desa,
            'nik' => $this->penduduk->nik,
            'kk' => $this->penduduk->kk,
            'kepala_keluarga_id' => $this->penduduk->kepala_keluarga_id,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'rt_rw' => $this->rt_rw,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'agama' => $this->agama,
            'status_perkawinan' => $this->status_perkawinan,
            'kepala_keluarga' => $this->penduduk->kepala_keluarga,
            'pekerjaan' => $this->pekerjaan,
            'pendidikan' => $this->pendidikan,
            'status' => 'pending',
            'no_hp' => $this->no_hp,
            'email' => $this->email,
            'golongan_darah' => $this->golongan_darah
        ]);

        // Change user role from 'warga' to 'unverified'
        $unverifiedRole = Role::where('name', 'unverified')->first();
        $wagaRole = Role::where('name', 'warga')->first();

        if ($unverifiedRole && $wagaRole) {
            $this->user->removeRole($wagaRole);
            $this->user->assignRole($unverifiedRole);
        }

        // Update status and display message
        $this->pendingVerification = true;
        session()->flash('message', 'Perubahan data telah dikirim dan menunggu verifikasi dari admin. Selama proses verifikasi, beberapa layanan mungkin dibatasi.');
    }

    public function render()
    {
        return view('livewire.warga.profile')
            ->layout('layouts.app');
    }
}