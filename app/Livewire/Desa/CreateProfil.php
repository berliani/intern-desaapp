<?php

namespace App\Livewire\Desa;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProfilDesa;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class CreateProfil extends Component
{
    use WithFileUploads;

    // Properti Laravolt
    public $provinces;
    public $cities = [];
    public $districts = [];
    public $villages = [];
    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedDistrict = null;
    public $selectedVillage = null;

    // Properti form
    public string $nama_desa = '';
    public string $subdomain = '';
    public string $kode_pos = '';
    public string $alamat = '';
    public string $telepon = '';
    public string $email = '';
    public string $website = '';
    public string $visi = '';
    public string $misi = '';
    public string $sejarah = '';
    public $logo;
    public $thumbnails;
    public $existingLogo;
    public $existingThumbnails;
    public $user;
    public $profil;

    public function mount()
    {
        $this->user = Auth::user();
        $this->provinces = Province::all();
        $this->profil = ProfilDesa::where('company_id', $this->user->company_id)->first();

        if ($this->profil) {
            $this->fill($this->profil->toArray());
            $this->existingLogo = $this->profil->logo;
            $this->existingThumbnails = $this->profil->thumbnails;

            // Pre-fill dropdowns if data exists
            if ($this->profil->provinsi) {
                $province = Province::where('name', 'LIKE', '%' . $this->profil->provinsi . '%')->first();
                if($province) {
                    $this->selectedProvince = $province->code;
                    $this->cities = City::where('province_code', $this->selectedProvince)->get();
                }
            }
            if ($this->profil->kabupaten && $this->selectedProvince) {
                 $city = City::where('name', 'LIKE', '%' . $this->profil->kabupaten . '%')->where('province_code', $this->selectedProvince)->first();
                 if($city) {
                    $this->selectedCity = $city->code;
                    $this->districts = District::where('city_code', $this->selectedCity)->get();
                 }
            }
        } else {
            // Data default untuk profil baru
            $this->email = $this->user->email ?? '';
            $this->telepon = $this->user->telepon ?? '';

            // FIX: Initialize existing file variables to null for new profiles
            $this->existingLogo = null;
            $this->existingThumbnails = null;
        }
    }

    public function updatedNamaDesa($value)
    {
        $this->subdomain = Str::slug($value);
        $this->website = "http://{$this->subdomain}.desa.local";
    }

    public function updatedSelectedProvince($provinceId)
    {
        $this->cities = City::where('province_code', $provinceId)->get();
        $this->reset(['selectedCity', 'selectedDistrict', 'selectedVillage', 'districts', 'villages']);
    }

    public function updatedSelectedCity($cityId)
    {
        $this->districts = District::where('city_code', $cityId)->get();
        $this->reset(['selectedDistrict', 'selectedVillage', 'villages']);
    }

    public function updatedSelectedDistrict($districtId)
    {
        $this->villages = Village::where('district_code', $districtId)->get();
        $this->reset('selectedVillage');
    }

    public function saveProfile()
    {
        $validated = $this->validate([
            'nama_desa' => 'required|string|max:100|unique:companies,name,' . optional($this->user->company)->id,
            'subdomain' => 'required|string|max:50|alpha_dash|unique:companies,subdomain,' . optional($this->user->company)->id,
            'selectedProvince' => 'required',
            'selectedCity' => 'required',
            'selectedDistrict' => 'required',
            'selectedVillage' => 'required',
            'kode_pos' => 'required|string|max:5',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:16',
            'email' => 'required|email|max:100',
            'logo' => 'nullable|image|max:1024',
            'thumbnails' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Buat atau update Company
            $company = Company::updateOrCreate(
                ['id' => $this->user->company_id],
                [
                    'name' => $validated['nama_desa'],
                    'subdomain' => $validated['subdomain'],
                ]
            );

            // 2. Hubungkan company ke user jika belum ada
            if (!$this->user->company_id) {
                $this->user->company_id = $company->id;
                $this->user->save();
            }

            // 3. Siapkan data untuk ProfilDesa
            if ($this->logo) {
                $validated['logo'] = $this->logo->store('logos', 'public');
            }
            if ($this->thumbnails) {
                $validated['thumbnails'] = $this->thumbnails->store('thumbnails', 'public');
            }

            // FIX: Use where('code', ...) instead of find()
            $village = Village::where('code', $this->selectedVillage)->first();
            $validated['kecamatan'] = $village->district->name;
            $validated['kabupaten'] = $village->district->city->name;
            $validated['provinsi'] = $village->district->city->province->name;
            $validated['website'] = "http://{$validated['subdomain']}.desa.local";

            // 4. Buat atau update ProfilDesa
            ProfilDesa::updateOrCreate(
                ['company_id' => $company->id],
                array_merge($validated, [
                    'created_by' => $this->user->id
                ])
            );
        });

        session()->flash('message', 'Profil desa berhasil disimpan!');
        return $this->redirect("http://{$this->subdomain}.desa.local");
    }

    public function render()
    {
        return view('livewire.desa.create-profil');
    }
}
