<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use App\Models\Umkm as UmkmModel;
use App\Models\ProfilDesa;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Penduduk;
use Livewire\Attributes\Layout;

class Umkm extends Component
{
    use WithFileUploads;

    // Properties for the UMKM
    public $nama_usaha = '';
    public $produk = '';
    public $kontak_whatsapp = '';
    public $foto_produk = null;
    public $lokasi = '';
    public $deskripsi = '';
    public $kategori = '';
    public $umkmId = null;
    public $isEditing = false;
    public $currentFotoUrl = null;
    public $selectedKategori = 'semua';
    public $detailUmkm = null;

    // For determining the current view mode
    public $view = 'list'; // Options: list, create, edit, detail

    // PENTING: Kembalikan property ini
    public $umkmList = [];

    protected $rules = [
        'nama_usaha' => 'required|string|max:255',
        'produk' => 'required|string|max:255',
        'kontak_whatsapp' => 'required|string|max:15',
        'foto_produk' => 'nullable|image|max:1024', // max 1MB
        'lokasi' => 'nullable|string|max:255',
        'deskripsi' => 'nullable|string|max:1000',
        'kategori' => 'nullable|string',
    ];

    public function filterKategori($kategori)
    {
        $this->selectedKategori = $kategori;
    }

    public function mount($id = null)
    {
        // Set statistics if penduduk data exists
        $this->setStatistics();

        // Determine the view based on the current route
        if (request()->routeIs('warga.umkm.create')) {
            $this->view = 'create';
        }
        elseif (request()->routeIs('warga.umkm.edit') && $id) {
            $this->editForm($id);
            $this->view = 'edit';
        }
        elseif (request()->routeIs('warga.umkm.detail') && $id) {
            $this->viewDetail($id);
            $this->view = 'detail';
        }
        else {
            $this->view = 'list';
        }
    }

    // Method to fetch and set UMKM statistics
    private function setStatistics()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;

        if ($penduduk) {
            // Gunakan withoutTrashed() untuk memastikan hanya mengambil data yang aktif
            $this->umkmList = UmkmModel::where('penduduk_id', $penduduk->id)
                                       ->withoutTrashed()
                                       ->get();
        } else {
            // Initialize with empty data if no penduduk record
            $this->umkmList = collect([]);
        }
    }

    // Method to handle creating new UMKM
    public function createForm()
    {
        return redirect()->route('warga.umkm.create');
    }

    // Method to handle editing UMKM
    public function editForm($id)
    {
        $umkm = UmkmModel::findOrFail($id);
        $this->umkmId = $umkm->id;
        $this->nama_usaha = $umkm->nama_usaha;
        $this->produk = $umkm->produk;
        $this->kontak_whatsapp = $umkm->kontak_whatsapp;
        $this->kategori = $umkm->kategori;
        $this->lokasi = $umkm->lokasi;
        $this->deskripsi = $umkm->deskripsi;

        // Tambahkan ini: Set URL foto saat ini untuk preview
        if ($umkm->foto_usaha) {
            $this->currentFotoUrl = Storage::url($umkm->foto_usaha);
        }

        if (!request()->routeIs('warga.umkm.edit')) {
            return redirect()->route('warga.umkm.edit', $id);
        }
    }

    // Method to view UMKM details
    public function viewDetail($id)
    {
        try {
            $umkm = UmkmModel::with(['penduduk', 'desa'])->findOrFail($id);
            $this->detailUmkm = $umkm;

            if (!request()->routeIs('warga.umkm.detail')) {
                return redirect()->route('warga.umkm.detail', $id);
            }
        } catch (\Exception $e) {
            $this->showAlert('error', 'Gagal!', 'Data UMKM tidak ditemukan.');
            return redirect()->route('warga.umkm');
        }
    }

    // Method to go back to list view
    public function backToList()
    {
        return redirect()->route('warga.umkm');
    }

    // Create UMKM record
    public function createUmkm()
    {
        $this->validate();

        $user = Auth::user();
        $penduduk = $user->penduduk;

        if (!$penduduk) {
            $this->showAlert('error', 'Gagal!', 'Anda belum terhubung dengan data penduduk.');
            return;
        }

        // Ambil desa pertama (asumsi hanya ada satu desa)
        $desa = ProfilDesa::first();

        if (!$desa) {
            $this->showAlert('error', 'Gagal!', 'Data desa belum tersedia.');
            return;
        }

        try {
            $data = [
                'id_desa' => $desa->id,
                'penduduk_id' => $penduduk->id,
                'user_id' => $user->id, // Tambahkan user_id agar konsisten
                'nama_usaha' => $this->nama_usaha,
                'produk' => $this->produk,
                'kontak_whatsapp' => $this->kontak_whatsapp,
                'lokasi' => $this->lokasi,
                'deskripsi' => $this->deskripsi,
                'kategori' => $this->kategori,
                'is_verified' => false, // Default ke belum terverifikasi
            ];

            // Upload foto jika ada
            if ($this->foto_produk) {
                $data['foto_usaha'] = $this->foto_produk->store('umkm', 'public'); // Changed to foto_usaha
            }

            UmkmModel::create($data);

            $this->showAlert('success', 'Berhasil!', 'Data UMKM berhasil disimpan dan sedang menunggu verifikasi admin.');

            $this->reset(['nama_usaha', 'produk', 'kontak_whatsapp', 'foto_produk', 'lokasi', 'deskripsi', 'kategori']);

            // Redirect ke halaman list UMKM setelah berhasil
            return redirect()->route('warga.umkm');

        } catch (\Exception $e) {
            $this->showAlert('error', 'Gagal!', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Update UMKM record
    public function updateUmkm()
    {
        $this->validate();

        $umkm = UmkmModel::find($this->umkmId);

        if (!$umkm) {
            $this->showAlert('error', 'Gagal!', 'Data UMKM tidak ditemukan.');
            return;
        }

        try {
            $data = [
                'nama_usaha' => $this->nama_usaha,
                'produk' => $this->produk,
                'kontak_whatsapp' => $this->kontak_whatsapp,
                'lokasi' => $this->lokasi,
                'deskripsi' => $this->deskripsi,
                'kategori' => $this->kategori,
                'is_verified' => false, // Set kembali ke belum terverifikasi karena ada perubahan
            ];

            // Upload foto baru jika ada
            if ($this->foto_produk) {
                // Hapus foto lama jika ada
                if ($umkm->foto_usaha && Storage::disk('public')->exists($umkm->foto_usaha)) {
                    Storage::disk('public')->delete($umkm->foto_usaha);
                }
                $data['foto_usaha'] = $this->foto_produk->store('umkm', 'public');
            }

            $umkm->update($data);

            $this->showAlert('success', 'Berhasil!', 'Data UMKM berhasil diperbarui dan sedang menunggu verifikasi ulang.');

            // Reset semua field termasuk foto dan currentFotoUrl
            $this->reset(['nama_usaha', 'produk', 'kontak_whatsapp', 'foto_produk', 'lokasi', 'deskripsi', 'kategori', 'umkmId', 'isEditing', 'currentFotoUrl']);

            // Redirect ke halaman list UMKM setelah berhasil
            return redirect()->route('warga.umkm');

        } catch (\Exception $e) {
            $this->showAlert('error', 'Gagal!', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Delete UMKM
    public function deleteUmkm($id)
    {
        try {
            $umkm = UmkmModel::find($id);
            if($umkm) {
                // Hapus foto jika ada
                if($umkm->foto_usaha) {
                    Storage::delete($umkm->foto_usaha);
                }
                if($umkm->foto_produk) {
                    Storage::delete($umkm->foto_produk);
                }

                $nama_usaha = $umkm->nama_usaha;
                $umkm->delete();

                // Reset property umkmList untuk memaksa refresh
                $this->reset('umkmList');
                // Load ulang data
                $this->mount();

                // Kirim alert berhasil
                $this->dispatch('showAlert', [
                    'icon' => 'success',
                    'title' => 'Berhasil!',
                    'text' => "UMKM '$nama_usaha' berhasil dihapus."
                ]);

                // Dispatch refresh event
                $this->dispatch('refresh');

                return true;
            }
        } catch (\Exception $e) {
            // Kirim alert error
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat menghapus UMKM.'
            ]);

            return false;
        }
    }

    // Helper method to show SweetAlert (sama persis dengan yang ada di Pengaduan.php)
    private function showAlert($icon, $title, $text)
    {
        $this->dispatch('showAlert', [
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirmDeleteUmkm', $id);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;

        // Ensure detailUmkm is available when needed
        if ($this->view === 'detail' && !$this->detailUmkm) {
            $id = request()->route('id');
            if ($id) {
                $this->viewDetail($id);
            }
        }

        return view('livewire.warga.umkm.' . $this->view, [
            'penduduk' => $penduduk,
            'detailUmkm' => $this->detailUmkm // Pass it explicitly to the view
        ]);
    }

    protected function getListeners()
    {
        return [
            'delete-umkm' => 'simpleDelete'
        ];
    }

    public function simpleDelete($id)
    {
        $this->deleteUmkm($id);
    }
}