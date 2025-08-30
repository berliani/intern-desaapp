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
use Livewire\Attributes\Computed;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class CreateProfil extends Component
{
    use WithFileUploads;

    public $provinces;
    public $cities = [];
    public $districts = [];
    public $villages = [];
    public $selectedProvince = null;
    public $selectedCity = null;
    public $selectedDistrict = null;
    public $selectedVillage = null;

    public string $newProvinceName = '';
    public string $newProvinceCode = '';
    public string $newCityName = '';
    public string $newCityCode = '';
    public string $newDistrictName = '';
    public string $newDistrictCode = '';
    public string $newVillageName = '';
    public string $newVillageCode = '';

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

            $this->telepon = $this->profil->telepon;
            $this->email = $this->profil->email;
            
            $this->existingLogo = $this->profil->logo;
            $this->existingThumbnails = $this->profil->thumbnails;

            if ($this->profil->provinsi) {
                $province = Province::where('name', 'LIKE', $this->profil->provinsi)->first();
                if($province) {
                    $this->selectedProvince = $province->code;
                    $this->cities = City::where('province_code', $this->selectedProvince)->get();
                }
            }
            if ($this->profil->kabupaten && $this->selectedProvince) {
                 $city = City::where('name', 'LIKE', $this->profil->kabupaten)->where('province_code', $this->selectedProvince)->first();
                 if($city) {
                    $this->selectedCity = $city->code;
                    $this->districts = District::where('city_code', $this->selectedCity)->get();
                 }
            }
            if ($this->profil->kecamatan && $this->selectedCity) {
                $district = District::where('name', 'LIKE', $this->profil->kecamatan)->where('city_code', $this->selectedCity)->first();
                if ($district) {
                    $this->selectedDistrict = $district->code;
                    $this->villages = Village::where('district_code', $this->selectedDistrict)->get();
                }
            }
            if ($this->profil->nama_desa && $this->selectedDistrict) {
                $village = Village::where('name', 'LIKE', $this->profil->nama_desa)->where('district_code', $this->selectedDistrict)->first();
                if ($village) {
                    $this->selectedVillage = $village->code;
                }
            }

        } else {
            $this->email = $this->user->email ?? '';
            $this->telepon = $this->user->telepon ?? '';
        }
    }

    protected function rules(): array
    {
        $companyId = optional($this->user->company)->id;

        return [
            'nama_desa' => ['required', 'string', 'max:100', Rule::unique('companies', 'name')->ignore($companyId)],
            'subdomain' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('companies', 'subdomain')->ignore($companyId)],
            'selectedProvince' => 'required',
            'selectedCity' => 'required_unless:selectedProvince,lainnya',
            'selectedDistrict' => 'required_unless:selectedProvince,lainnya|required_unless:selectedCity,lainnya',
            'selectedVillage' => 'required_unless:selectedProvince,lainnya|required_unless:selectedCity,lainnya|required_unless:selectedDistrict,lainnya',
            'kode_pos' => 'required|string|max:5',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:16',
            'email' => 'required|email|max:100',
            'logo' => 'nullable|image|max:1024',
            'thumbnails' => 'nullable|image|max:2048',

            'newProvinceName' => 'required_if:selectedProvince,lainnya|nullable|string|max:255|unique:indonesia_provinces,name',
            'newProvinceCode' => 'required_if:selectedProvince,lainnya|nullable|digits:2|unique:indonesia_provinces,code',
            'newCityName' => 'required_if:selectedProvince,lainnya|required_if:selectedCity,lainnya|nullable|string|max:255|unique:indonesia_cities,name',
            'newCityCode' => 'required_if:selectedProvince,lainnya|required_if:selectedCity,lainnya|nullable|digits:4|unique:indonesia_cities,code',
            'newDistrictName' => 'required_if:selectedDistrict,lainnya|nullable|string|max:255|unique:indonesia_districts,name',
            'newDistrictCode' => 'required_if:selectedDistrict,lainnya|nullable|digits:6|unique:indonesia_districts,code',
            'newVillageName' => 'required_if:selectedVillage,lainnya|nullable|string|max:255|unique:indonesia_villages,name',
            'newVillageCode' => 'required_if:selectedVillage,lainnya|nullable|digits:10|unique:indonesia_villages,code',
        ];
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['newProvinceName', 'newProvinceCode', 'newCityName', 'newCityCode', 'newDistrictName', 'newDistrictCode', 'newVillageName', 'newVillageCode', 'nama_desa', 'subdomain'])) {
            $this->validateOnly($propertyName);
        }
    }

    #[Computed]
    public function isLocationSelectionComplete(): bool
    {
        if (empty($this->selectedProvince)) return false;
        if ($this->selectedProvince === 'lainnya') {
            return filled($this->newProvinceName) && filled($this->newProvinceCode) && !$this->getErrorBag()->hasAny(['newProvinceName', 'newProvinceCode'])
                && filled($this->newCityName) && filled($this->newCityCode) && !$this->getErrorBag()->hasAny(['newCityName', 'newCityCode'])
                && filled($this->newDistrictName) && filled($this->newDistrictCode) && !$this->getErrorBag()->hasAny(['newDistrictName', 'newDistrictCode'])
                && filled($this->newVillageName) && filled($this->newVillageCode) && !$this->getErrorBag()->hasAny(['newVillageName', 'newVillageCode']);
        }
        if (empty($this->selectedCity)) return false;
        if ($this->selectedCity === 'lainnya') {
            return filled($this->newCityName) && filled($this->newCityCode) && !$this->getErrorBag()->hasAny(['newCityName', 'newCityCode'])
                && filled($this->newDistrictName) && filled($this->newDistrictCode) && !$this->getErrorBag()->hasAny(['newDistrictName', 'newDistrictCode'])
                && filled($this->newVillageName) && filled($this->newVillageCode) && !$this->getErrorBag()->hasAny(['newVillageName', 'newVillageCode']);
        }
        if (empty($this->selectedDistrict)) return false;
        if ($this->selectedDistrict === 'lainnya') {
            return filled($this->newDistrictName) && filled($this->newDistrictCode) && !$this->getErrorBag()->hasAny(['newDistrictName', 'newDistrictCode'])
                && filled($this->newVillageName) && filled($this->newVillageCode) && !$this->getErrorBag()->hasAny(['newVillageName', 'newVillageCode']);
        }
        if (empty($this->selectedVillage)) return false;
        if ($this->selectedVillage === 'lainnya') {
            return filled($this->newVillageName) && filled($this->newVillageCode) && !$this->getErrorBag()->hasAny(['newVillageName', 'newVillageCode']);
        }
        return true;
    }

    public function updatedNamaDesa($value) { $this->subdomain = Str::slug($value); $domain = config('app.domain', 'desa.local'); $this->website = "http://{$this->subdomain}.{$domain}"; }
    public function updatedSelectedProvince($provinceId) { $this->cities = ($provinceId && $provinceId !== 'lainnya') ? City::where('province_code', $provinceId)->get() : collect(); $this->reset(['selectedCity', 'selectedDistrict', 'selectedVillage', 'districts', 'villages']); }
    public function updatedSelectedCity($cityId) { $this->districts = ($cityId && $cityId !== 'lainnya') ? District::where('city_code', $cityId)->get() : collect(); $this->reset(['selectedDistrict', 'selectedVillage', 'villages']); }
    public function updatedSelectedDistrict($districtId) { $this->villages = ($districtId && $districtId !== 'lainnya') ? Village::where('district_code', $districtId)->get() : collect(); $this->reset('selectedVillage'); }
    public function saveProfile()
    {
        if ($this->selectedProvince === 'lainnya' && !empty($this->newCityName)) {
            $this->selectedCity = 'lainnya';
            $this->selectedDistrict = 'lainnya';
            $this->selectedVillage = 'lainnya';
        } elseif ($this->selectedCity === 'lainnya' && !empty($this->newDistrictName)) {
            $this->selectedDistrict = 'lainnya';
            $this->selectedVillage = 'lainnya';
        } elseif ($this->selectedDistrict === 'lainnya' && !empty($this->newVillageName)) {
            $this->selectedVillage = 'lainnya';
        }

        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $provinceCode = $this->selectedProvince;
            $provinceName = '';
            if ($provinceCode === 'lainnya') {
                $newProvince = Province::create(['code' => $this->newProvinceCode, 'name' => Str::upper($this->newProvinceName)]);
                $provinceCode = $newProvince->code;
                $provinceName = $newProvince->name;
            } else {
                $provinceName = Province::where('code', $provinceCode)->first()?->name;
            }

            $cityCode = $this->selectedCity;
            $cityName = '';
            if ($this->selectedProvince === 'lainnya' || $this->selectedCity === 'lainnya') {
                $newCity = City::create(['code' => $this->newCityCode, 'name' => Str::upper($this->newCityName), 'province_code' => $provinceCode]);
                $cityCode = $newCity->code;
                $cityName = $newCity->name;
            } else {
                $cityName = City::where('code', $cityCode)->first()?->name;
            }
            
            $districtCode = $this->selectedDistrict;
            $districtName = '';
            if ($this->selectedProvince === 'lainnya' || $this->selectedCity === 'lainnya' || $this->selectedDistrict === 'lainnya') {
                $newDistrict = District::create(['code' => $this->newDistrictCode, 'name' => Str::upper($this->newDistrictName), 'city_code' => $cityCode]);
                $districtCode = $newDistrict->code;
                $districtName = $newDistrict->name;
            } else {
                $districtName = District::where('code', $districtCode)->first()?->name;
            }
            
            if ($this->selectedProvince === 'lainnya' || $this->selectedCity === 'lainnya' || $this->selectedDistrict === 'lainnya' || $this->selectedVillage === 'lainnya') {
                Village::create(['code' => $this->newVillageCode, 'name' => Str::upper($this->newVillageName), 'district_code' => $districtCode]);
            }

            $company = Company::updateOrCreate(
                ['id' => $this->user->company_id],
                ['name' => $validated['nama_desa'], 'subdomain' => $validated['subdomain']]
            );

            if (!$this->user->company_id) {
                $this->user->company_id = $company->id;
                $this->user->save();
            }
   
            $profileData = $validated;
            $profileData['provinsi'] = $provinceName;
            $profileData['kabupaten'] = $cityName;
            $profileData['kecamatan'] = $districtName;
            
            if (isset($validated['logo'])) $profileData['logo'] = $this->logo->store('logos', 'public');
            if (isset($validated['thumbnails'])) $profileData['thumbnails'] = $this->thumbnails->store('thumbnails', 'public');
            
            $domain = config('app.domain', 'desa.local');
            $profileData['website'] = "http://{$this->subdomain}.{$domain}";
            $profileData['created_by'] = $this->user->id;

            ProfilDesa::updateOrCreate(
                ['company_id' => $company->id],
                $profileData 
            );
        });

        session()->flash('message', 'Profil desa berhasil disimpan!');
        $domain = config('app.domain', 'desa.local');
        return $this->redirect("http://{$this->subdomain}.{$domain}", navigate: true);
    }

    public function render()
    {
        return view('livewire.desa.create-profil');
    }
}
