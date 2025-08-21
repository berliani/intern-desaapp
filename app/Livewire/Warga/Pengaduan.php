<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pengaduan as PengaduanModel;
use App\Models\ProfilDesa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Pengaduan extends Component
{
    use WithFileUploads;

    public $judul = '';
    public $kategori = '';
    public $deskripsi = '';
    public $foto;
    public $oldFoto;
    public $pengaduanId = null;
    public $isEditing = false;
    public $showDetail = false;
    public $selectedPengaduan = null;

    protected $rules = [
        'judul' => 'required|string|min:5|max:255',
        'kategori' => 'required|in:Keamanan,Infrastruktur,Sosial,Lingkungan,Pelayanan Publik,Kesehatan,Lainnya',
        'deskripsi' => 'required|string|min:10',
        'foto' => 'nullable|image|max:1024', // Max 1MB
    ];

    protected $messages = [
        'judul.required' => 'Judul pengaduan wajib diisi',
        'judul.min' => 'Judul pengaduan minimal 5 karakter',
        'judul.max' => 'Judul pengaduan maksimal 255 karakter',
        'kategori.required' => 'Kategori pengaduan wajib dipilih',
        'kategori.in' => 'Kategori pengaduan tidak valid',
        'deskripsi.required' => 'Deskripsi pengaduan wajib diisi',
        'deskripsi.min' => 'Deskripsi pengaduan minimal 10 karakter',
        'foto.image' => 'File harus berupa gambar (JPG, PNG, GIF)',
        'foto.max' => 'Ukuran gambar maksimal 1MB',
    ];

    protected function getListeners()
    {
        return [
            'refresh' => '$refresh'
        ];
    }

    public function createPengaduan()
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
                'judul' => $this->judul,
                'kategori' => $this->kategori,
                'deskripsi' => $this->deskripsi,
                'status' => 'Belum Ditangani',
            ];

            // Upload foto jika ada
            if ($this->foto) {
                $data['foto'] = $this->foto->store('pengaduan', 'public');
            }

            PengaduanModel::create($data);

            $this->reset(['judul', 'kategori', 'deskripsi', 'foto']);
            $this->showAlert('success', 'Berhasil!', 'Pengaduan berhasil dikirim dan akan segera diproses.');
        } catch (\Exception $e) {
            $this->showAlert('error', 'Gagal!', 'Terjadi kesalahan saat mengirim pengaduan: ' . $e->getMessage());
        }
    }

    public function editPengaduan($id)
    {
        $pengaduan = PengaduanModel::find($id);

        if ($pengaduan && $pengaduan->status === 'Belum Ditangani') {
            $this->pengaduanId = $pengaduan->id;
            $this->judul = $pengaduan->judul;
            $this->kategori = $pengaduan->kategori;
            $this->deskripsi = $pengaduan->deskripsi;
            $this->oldFoto = $pengaduan->foto;
            $this->isEditing = true;
        } else {
            $this->showAlert('error', 'Gagal!', 'Pengaduan tidak dapat diedit karena sudah diproses.');
        }
    }

    public function updatePengaduan()
    {
        $this->validate();

        try {
            $pengaduan = PengaduanModel::find($this->pengaduanId);

            if ($pengaduan && $pengaduan->status === 'Belum Ditangani') {
                $data = [
                    'judul' => $this->judul,
                    'kategori' => $this->kategori,
                    'deskripsi' => $this->deskripsi,
                ];

                // Upload foto baru jika ada
                if ($this->foto) {
                    // Hapus foto lama jika ada
                    if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                        Storage::disk('public')->delete($pengaduan->foto);
                    }
                    $data['foto'] = $this->foto->store('pengaduan', 'public');
                }

                $pengaduan->update($data);

                $this->reset(['judul', 'kategori', 'deskripsi', 'foto', 'oldFoto', 'pengaduanId', 'isEditing']);
                $this->showAlert('success', 'Berhasil!', 'Pengaduan berhasil diperbarui.');
            } else {
                $this->showAlert('error', 'Gagal!', 'Pengaduan tidak dapat diperbarui karena sudah diproses.');
            }
        } catch (\Exception $e) {
            $this->showAlert('error', 'Gagal!', 'Terjadi kesalahan saat memperbarui pengaduan: ' . $e->getMessage());
        }
    }

    public function cancelEdit()
    {
        $this->reset(['judul', 'kategori', 'deskripsi', 'foto', 'oldFoto', 'pengaduanId', 'isEditing']);
    }

    public function deletePengaduan($id)
    {
        $this->dispatch('confirmDelete', $id);
    }

    public function confirmDeletePengaduan($id)
    {
        try {
            $pengaduan = PengaduanModel::find($id);

            if ($pengaduan && $pengaduan->status === 'Belum Ditangani') {
                // Hapus foto jika ada
                if ($pengaduan->foto && Storage::disk('public')->exists($pengaduan->foto)) {
                    Storage::disk('public')->delete($pengaduan->foto);
                }

                $pengaduan->delete();
                $this->showAlert('success', 'Berhasil!', 'Pengaduan berhasil dihapus.');
            } else {
                $this->showAlert('error', 'Gagal!', 'Pengaduan tidak dapat dihapus karena sudah diproses.');
            }
        } catch (\Exception $e) {
            $this->showAlert('error', 'Gagal!', 'Terjadi kesalahan saat menghapus pengaduan: ' . $e->getMessage());
        }
    }

    public function viewPengaduan($id)
    {
        $this->selectedPengaduan = PengaduanModel::find($id);
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->selectedPengaduan = null;
    }

    // Helper method to show SweetAlert
    private function showAlert($icon, $title, $text)
    {
        $this->dispatch('showAlert', [
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    public function render()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;
        $pengaduan = $penduduk ? $penduduk->pengaduan()->orderBy('created_at', 'desc')->get() : collect();

        return view('livewire.warga.pengaduan', [
            'pengaduanList' => $pengaduan,
            'penduduk' => $penduduk,
        ])->layout('layouts.app');
    }
}