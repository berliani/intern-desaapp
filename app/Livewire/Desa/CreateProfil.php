<?php

namespace App\Livewire\Desa;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProfilDesa;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CreateProfil extends Component
{
    use WithFileUploads;

    // Properti untuk menampung data form
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

    // Properti untuk file upload
    public $logo;
    public $thumbnails;
    public $existingLogo;
    public $existingThumbnails;

    public $user;
    public $profil;

    public function mount()
    {
        $this->user = Auth::user();
        $this->profil = ProfilDesa::where('company_id', $this->user->company_id)->first();

        if ($this->profil) {
            $this->fill($this->profil->toArray());
            $this->existingLogo = $this->profil->logo;
            $this->existingThumbnails = $this->profil->thumbnails;
        } else {
            $this->nama_desa = $this->user->company->name ?? '';
            $this->email = $this->user->email ?? '';
            $this->telepon = $this->user->telepon ?? '';
            if ($this->user->company) {
                $this->website = "http://{$this->user->company->subdomain}.desa.local";
            }
        }
    }

    public function saveProfile()
    {
        $validated = $this->validate([
            'nama_desa' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:50',
            'kabupaten' => 'required|string|max:50',
            'provinsi' => 'required|string|max:50',
            'kode_pos' => 'required|string|max:5',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:16',
            'email' => 'required|email|max:100',
            'website' => 'nullable|url|max:100',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'logo' => 'nullable|image|max:1024', // 1MB Max
            'thumbnails' => 'nullable|image|max:2048', // 2MB Max
        ]);

        if ($this->logo) {
            $validated['logo'] = $this->logo->store('logos', 'public');
        }
        if ($this->thumbnails) {
            $validated['thumbnails'] = $this->thumbnails->store('thumbnails', 'public');
        }

        ProfilDesa::updateOrCreate(
            ['company_id' => $this->user->company_id],
            array_merge($validated, ['created_by' => $this->user->id])
        );

        session()->flash('message', 'Profil desa berhasil disimpan!');

        // DIUBAH: Mengarahkan ke halaman depan website desa yang sesuai
        $subdomain = $this->user->company->subdomain;
        $url = "http://{$subdomain}.desa.local";

        return $this->redirect($url);
    }

    public function render()
    {
        return view('livewire.desa.create-profil');
    }
}
