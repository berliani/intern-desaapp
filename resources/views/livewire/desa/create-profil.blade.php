<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lengkapi Profil Desa Anda') }}
        </h2>
    </x-slot>

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

                        <div class="md:col-span-2">
                            <x-input-label for="nama_desa" value="Nama Desa" />
                            <x-text-input wire:model="nama_desa" id="nama_desa" type="text" class="mt-1 block w-full bg-gray-100" readonly />
                        </div>

                        <div>
                            <x-input-label for="kecamatan" value="Kecamatan" />
                            <x-text-input wire:model="kecamatan" id="kecamatan" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('kecamatan')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kabupaten" value="Kabupaten/Kota" />
                            <x-text-input wire:model="kabupaten" id="kabupaten" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('kabupaten')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="provinsi" value="Provinsi" />
                            <x-text-input wire:model="provinsi" id="provinsi" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('provinsi')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="kode_pos" value="Kode Pos" />
                            <x-text-input wire:model="kode_pos" id="kode_pos" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('kode_pos')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="alamat" value="Alamat Kantor Desa" />
                            <textarea wire:model="alamat" id="alamat" rows="3" class="mt-1 block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required></textarea>
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="telepon" value="Telepon Desa" />
                            <x-text-input wire:model="telepon" id="telepon" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email Desa" />
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

                                {{-- FIX: Cek apakah $logo adalah objek file upload --}}
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

                                {{-- FIX: Cek apakah $thumbnails adalah objek file upload --}}
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
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg wire:loading wire:target="saveProfile" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Simpan Profil Desa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
