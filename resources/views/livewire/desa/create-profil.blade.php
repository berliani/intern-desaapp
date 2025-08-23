<div>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('message'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="p-2 bg-emerald-100 rounded-lg text-emerald-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Formulir Profil Desa</h3>
                </div>

                <form wire:submit="saveProfile" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <x-input-label for="nama_desa">Nama Desa <span class="text-red-500">*</span></x-input-label>
                            <x-text-input wire:model.live.debounce.500ms="nama_desa" id="nama_desa" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('nama_desa')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="subdomain">Subdomain Website <span class="text-red-500">*</span></x-input-label>
                            <div class="flex items-center mt-1">
                                <x-text-input wire:model="subdomain" id="subdomain" type="text" class="w-full rounded-r-none bg-gray-100" readonly />
                                <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md h-11">
                                    .{{ config('app.domain', 'desa.local') }}
                                </span>
                            </div>
                            <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
                        </div>

                        {{-- PROVINSI --}}
                        <div class="md:col-span-2">
                            <x-input-label for="province">Provinsi <span class="text-red-500">*</span></x-input-label>
                            <select wire:model.live="selectedProvince" id="province" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->code }}">{{ $province->name }}</option>
                                @endforeach
                                <option value="lainnya">-- Lainnya --</option>
                            </select>
                            @if($selectedProvince === 'lainnya')
                                <div class="mt-4 grid grid-cols-2 gap-4 p-4 border border-blue-200 bg-blue-50 rounded-lg">
                                    <div>
                                        <x-input-label for="newProvinceName">Nama Provinsi Baru <span class="text-red-500">*</span></x-input-label>
                                        <x-text-input wire:model.blur="newProvinceName" id="newProvinceName" type="text" class="mt-1 block w-full uppercase" />
                                        <x-input-error :messages="$errors->get('newProvinceName')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="newProvinceCode">Kode (2 digit) <span class="text-red-500">*</span></x-input-label>
                                        <x-text-input wire:model.blur="newProvinceCode" id="newProvinceCode" type="text" class="mt-1 block w-full" />
                                        <x-input-error :messages="$errors->get('newProvinceCode')" class="mt-2" />
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- KABUPATEN/KOTA --}}
                        <div class="md:col-span-2">
                            <x-input-label for="city">Kabupaten/Kota <span class="text-red-500">*</span></x-input-label>
                            @if($selectedProvince === 'lainnya')
                                <div class="mt-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-text-input wire:model.blur="newCityName" type="text" class="block w-full uppercase" placeholder="NAMA KABUPATEN/KOTA BARU" />
                                        <x-input-error :messages="$errors->get('newCityName')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-text-input wire:model.blur="newCityCode" type="text" class="block w-full" placeholder="KODE (4 DIGIT)" />
                                        <x-input-error :messages="$errors->get('newCityCode')" class="mt-2" />
                                    </div>
                                </div>
                            @else
                                <select wire:model.live="selectedCity" id="city" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" @if(empty($selectedProvince)) disabled @endif>
                                    <option value="">-- Pilih Kabupaten/Kota --</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->code }}">{{ $city->name }}</option>
                                    @endforeach
                                    @if($selectedProvince)
                                        <option value="lainnya">-- Lainnya --</option>
                                    @endif
                                </select>
                                @if($selectedCity === 'lainnya')
                                <div class="mt-4 grid grid-cols-2 gap-4 p-4 border border-blue-200 bg-blue-50 rounded-lg">
                                    <div>
                                        <x-input-label for="newCityName">Nama Kabupaten/Kota Baru <span class="text-red-500">*</span></x-input-label>
                                        <x-text-input wire:model.blur="newCityName" id="newCityName" type="text" class="mt-1 block w-full uppercase" />
                                        <x-input-error :messages="$errors->get('newCityName')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="newCityCode">Kode (4 digit) <span class="text-red-500">*</span></x-input-label>
                                        <x-text-input wire:model.blur="newCityCode" id="newCityCode" type="text" class="mt-1 block w-full" />
                                        <x-input-error :messages="$errors->get('newCityCode')" class="mt-2" />
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                        
                        {{-- KECAMATAN --}}
                        <div class="md:col-span-2">
                            <x-input-label for="district">Kecamatan <span class="text-red-500">*</span></x-input-label>
                             @if($selectedProvince === 'lainnya' || $selectedCity === 'lainnya')
                                <div class="mt-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-text-input wire:model.blur="newDistrictName" type="text" class="block w-full uppercase" placeholder="NAMA KECAMATAN BARU" />
                                        <x-input-error :messages="$errors->get('newDistrictName')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-text-input wire:model.blur="newDistrictCode" type="text" class="block w-full" placeholder="KODE (6 DIGIT)" />
                                        <x-input-error :messages="$errors->get('newDistrictCode')" class="mt-2" />
                                    </div>
                                </div>
                            @else
                                <select wire:model.live="selectedDistrict" id="district" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" @if(empty($selectedCity)) disabled @endif>
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district->code }}">{{ $district->name }}</option>
                                    @endforeach
                                    @if($selectedCity)
                                        <option value="lainnya">-- Lainnya --</option>
                                    @endif
                                </select>
                                @if($selectedDistrict === 'lainnya')
                                <div class="mt-4 grid grid-cols-2 gap-4 p-4 border border-blue-200 bg-blue-50 rounded-lg">
                                    <div>
                                        <x-input-label for="newDistrictName">Nama Kecamatan Baru <span class="text-red-500">*</span></x-input-label>
                                        <x-text-input wire:model.blur="newDistrictName" id="newDistrictName" type="text" class="mt-1 block w-full uppercase" />
                                        <x-input-error :messages="$errors->get('newDistrictName')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="newDistrictCode">Kode (6 digit) <span class="text-red-500">*</span></x-input-label>
                                        <x-text-input wire:model.blur="newDistrictCode" id="newDistrictCode" type="text" class="mt-1 block w-full" />
                                        <x-input-error :messages="$errors->get('newDistrictCode')" class="mt-2" />
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>

                        {{-- DESA/KELURAHAN --}}
                        <div class="md:col-span-2">
                            <x-input-label for="village">Desa/Kelurahan <span class="text-red-500">*</span></x-input-label>
                             @if($selectedProvince === 'lainnya' || $selectedCity === 'lainnya' || $selectedDistrict === 'lainnya')
                                <div class="mt-1 grid grid-cols-2 gap-4">
                                    <div>
                                        <x-text-input wire:model.blur="newVillageName" type="text" class="block w-full uppercase" placeholder="NAMA DESA/KELURAHAN BARU" />
                                        <x-input-error :messages="$errors->get('newVillageName')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-text-input wire:model.blur="newVillageCode" type="text" class="block w-full" placeholder="KODE (10 DIGIT)" />
                                        <x-input-error :messages="$errors->get('newVillageCode')" class="mt-2" />
                                    </div>
                                </div>
                            @else
                                <select wire:model.live="selectedVillage" id="village" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" @if(empty($selectedDistrict)) disabled @endif>
                                    <option value="">-- Pilih Desa/Kelurahan --</option>
                                    @foreach($villages as $village)
                                        <option value="{{ $village->code }}">{{ $village->name }}</option>
                                    @endforeach
                                    @if($selectedDistrict)
                                        <option value="lainnya">-- Lainnya --</option>
                                    @endif
                                </select>
                                @if($selectedVillage === 'lainnya')
                                <div class="mt-4 grid grid-cols-2 gap-4 p-4 border border-blue-200 bg-blue-50 rounded-lg">
                                    <div>
                                        <x-input-label for="newVillageName">Nama Desa/Kelurahan Baru <span class="text-red-500">*</span></x-input-label>
                                        <x-text-input wire:model.blur="newVillageName" id="newVillageName" type="text" class="mt-1 block w-full uppercase" />
                                        <x-input-error :messages="$errors->get('newVillageName')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="newVillageCode">Kode (10 digit) <span class="text-red-500">*</span></x-input-label>
                                        <x-text-input wire:model.blur="newVillageCode" id="newVillageCode" type="text" class="mt-1 block w-full" />
                                        <x-input-error :messages="$errors->get('newVillageCode')" class="mt-2" />
                                    </div>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Sisa Form --}}
                    <div class="space-y-6 border-t pt-6 @unless($this->isLocationSelectionComplete()) opacity-50 pointer-events-none @endunless">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="kode_pos">Kode Pos <span class="text-red-500">*</span></x-input-label>
                                <x-text-input wire:model="kode_pos" id="kode_pos" type="text" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('kode_pos')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="alamat">Alamat Kantor Desa <span class="text-red-500">*</span></x-input-label>
                                <textarea wire:model="alamat" id="alamat" rows="3" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required></textarea>
                                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="telepon">Telepon Desa <span class="text-red-500">*</span></x-input-label>
                                <x-text-input wire:model="telepon" id="telepon" type="text" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email">Email Desa <span class="text-red-500">*</span></x-input-label>
                                <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="website" value="Website" />
                                <x-text-input wire:model="website" id="website" type="url" class="mt-1 block w-full bg-gray-100" readonly />
                            </div>

                            {{-- File Uploads --}}
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="logo" value="Logo Desa (.jpg, .png)" />
                                    <input type="file" wire:model="logo" id="logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                    <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                                    <div wire:loading wire:target="logo" class="mt-2 text-sm text-gray-500">Uploading...</div>
                                    @if ($logo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                        <img src="{{ $logo->temporaryUrl() }}" class="mt-4 rounded-lg w-24 h-24 object-cover">
                                    @elseif($existingLogo)
                                          <img src="{{ asset('storage/' . $existingLogo) }}" class="mt-4 rounded-lg w-24 h-24 object-cover">
                                    @endif
                                </div>
                                 <div>
                                    <x-input-label for="thumbnails" value="Thumbnails (.jpg, .png)" />
                                    <input type="file" wire:model="thumbnails" id="thumbnails" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                                    <x-input-error :messages="$errors->get('thumbnails')" class="mt-2" />
                                    <div wire:loading wire:target="thumbnails" class="mt-2 text-sm text-gray-500">Uploading...</div>
                                    @if ($thumbnails instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
                                        <img src="{{ $thumbnails->temporaryUrl() }}" class="mt-4 rounded-lg w-full h-32 object-cover">
                                    @elseif($existingThumbnails)
                                          <img src="{{ asset('storage/' . $existingThumbnails) }}" class="mt-4 rounded-lg w-full h-32 object-cover">
                                    @endif
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="visi" value="Visi (Opsional)" />
                                <textarea wire:model="visi" id="visi" rows="4" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="misi" value="Misi (Opsional)" />
                                <textarea wire:model="misi" id="misi" rows="4" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="sejarah" value="Sejarah Singkat (Opsional)" />
                                <textarea wire:model="sejarah" id="sejarah" rows="4" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-4 border-t border-gray-100">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                            Simpan Profil Desa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</d