<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\JenisBansos;
use App\Models\Bansos as BansosModel;

class PengajuanBansos extends Component
{
    use WithFileUploads;

    public $jenisBansos = [];
    public $selectedJenis = null;
    public $keterangan = '';
    public $alasan_pengajuan = '';
    public $is_urgent = false;
    public $foto_rumah;
    public $dokumen_pendukung;

    public function mount()
    {
        $this->jenisBansos = JenisBansos::where('is_active', true)->get();
    }

    public function resetForm()
    {
        $this->selectedJenis = null;
        $this->keterangan = '';
        $this->alasan_pengajuan = '';
        $this->is_urgent = false;
        $this->foto_rumah = null;
        $this->dokumen_pendukung = null;
    }

    // Metode helper untuk menampilkan SweetAlert
    private function showAlert($icon, $title, $text)
    {
        $this->dispatch('showAlert', [
            'icon' => $icon,
            'title' => $title,
            'text' => $text
        ]);
    }

    public function ajukanBansos()
    {
        $this->validate([
            'selectedJenis' => 'required|exists:jenis_bansos,id',
            'alasan_pengajuan' => 'required|string|min:10',
            'keterangan' => 'nullable|string',
            'is_urgent' => 'boolean',
            'foto_rumah' => 'nullable|image|max:2048', // Max 2MB
            'dokumen_pendukung' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $penduduk = $user->penduduk;

        if (!$penduduk) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Anda belum terhubung dengan data penduduk.'
            ]);
            return;
        }

        try {
            // Tambahkan ID desa dari profil desa pertama
            $desa = \App\Models\ProfilDesa::first();

            if (!$desa) {
                $this->dispatch('showAlert', [
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'text' => 'Data desa tidak ditemukan.'
                ]);
                return;
            }

            // Handle file uploads
            $foto_rumah_path = null;
            $dokumen_pendukung_path = null;

            if ($this->foto_rumah) {
                $foto_rumah_path = $this->foto_rumah->store('bansos/foto-rumah', 'public');
            }

            if ($this->dokumen_pendukung) {
                $dokumen_pendukung_path = $this->dokumen_pendukung->store('bansos/dokumen', 'public');
            }

            // Buat data bantuan sosial
            BansosModel::create([
                'desa_id' => $desa->id,
                'penduduk_id' => $penduduk->id,
                'jenis_bansos_id' => $this->selectedJenis,
                'status' => 'Diajukan',
                'prioritas' => 'Sedang',  // Default prioritas
                'tanggal_pengajuan' => now(),
                'alasan_pengajuan' => $this->alasan_pengajuan,
                'keterangan' => $this->keterangan,
                'sumber_pengajuan' => 'warga',
                'is_urgent' => $this->is_urgent,
                'foto_rumah' => $foto_rumah_path,
                'dokumen_pendukung' => $dokumen_pendukung_path,
            ]);

            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Pengajuan bantuan sosial berhasil dikirim.'
            ]);

            $this->resetForm();

            // Tambahkan redirect ke halaman bansos setelah notifikasi
            return redirect()->route('warga.bansos');

        } catch (\Exception $e) {
            // Log error untuk debugging
            \Illuminate\Support\Facades\Log::error('Error saat mengajukan bansos: ' . $e->getMessage());

            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;

        return view('livewire.warga.pengajuan-bansos', [
            'penduduk' => $penduduk,
        ])->layout('layouts.app');
    }
}
