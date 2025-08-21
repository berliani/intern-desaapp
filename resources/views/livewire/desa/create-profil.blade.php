<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lengkapi Profil Desa Anda
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- ... (notifikasi) ... --}}
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                {{-- ... (header form) ... --}}
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

                        {{-- Laravolt Dropdowns --}}
                        <div>
                            <x-input-label for="province">Provinsi <span class="text-red-500">*</span></x-input-label>
                            <select wire:model.live="selectedProvince" id="province" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Provinsi --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->code }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="city">Kabupaten/Kota <span class="text-red-500">*</span></x-input-label>
                            <select wire:model.live="selectedCity" id="city" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" @if(count($cities) == 0) disabled @endif>
                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->code }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="district">Kecamatan <span class="text-red-500">*</span></x-input-label>
                            <select wire:model.live="selectedDistrict" id="district" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" @if(count($districts) == 0) disabled @endif>
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($districts as $district)
                                    <option value="{{ $district->code }}">{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="village">Desa/Kelurahan <span class="text-red-500">*</span></x-input-label>
                            <select wire:model.live="selectedVillage" id="village" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" @if(count($villages) == 0) disabled @endif>
                                <option value="">-- Pilih Desa/Kelurahan --</option>
                                @foreach($villages as $village)
                                    <option value="{{ $village->code }}">{{ $village->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="kode_pos">
                                Kode Pos <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input wire:model="kode_pos" id="kode_pos" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('kode_pos')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="alamat">
                                Alamat Kantor Desa <span class="text-red-500">*</span>
                            </x-input-label>
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

                    <div class="flex items-center justify-end mt-8 pt-4 border-t border-gray-100">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                            Simpan Profil Desa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
