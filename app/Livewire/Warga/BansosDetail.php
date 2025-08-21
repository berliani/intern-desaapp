<?php

namespace App\Livewire\Warga;

use App\Models\Bansos;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class BansosDetail extends Component
{
    use WithFileUploads;

    public $bansosId;
    public $bansos;
    public $buktiPenerimaan;
    public $showKonfirmasiModal = false;

    public function mount($id)
    {
        $this->bansosId = $id;
        $this->loadBansos();
    }

    public function loadBansos()
    {
        $this->bansos = Bansos::with(['jenisBansos', 'riwayatStatus'])
            ->where('penduduk_id', auth()->user()->penduduk_id)
            ->findOrFail($this->bansosId);
    }

    public function konfirmasiPengambilan()
    {
        // Validasi apakah masih dalam batas waktu
        if ($this->bansos->tenggat_pengambilan && now()->isAfter($this->bansos->tenggat_pengambilan)) {
            $this->addError('pengambilan', 'Batas waktu pengambilan telah berakhir');
            return;
        }

        // Update status menjadi Sudah Diambil
        $this->bansos->update([
            'status' => 'Sudah Diambil',
            'tanggal_pengambilan' => now()
        ]);

        // Tambahkan ke riwayat status
        $this->bansos->riwayatStatus()->create([
            'status_baru' => 'Sudah Diambil',
            'status_lama' => 'Disetujui',
            'keterangan' => 'Bantuan telah diambil oleh penerima',
            'waktu_perubahan' => now()
        ]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Pengambilan bantuan berhasil dikonfirmasi'
        ]);
    }

    public function openKonfirmasiModal()
    {
        $this->showKonfirmasiModal = true;
    }

    public function konfirmasiPenerimaan()
    {
        $this->validate([
            'buktiPenerimaan' => 'required|image|max:2048|mimes:jpg,jpeg,png,gif',
        ], [
            'buktiPenerimaan.required' => 'Bukti penerimaan harus diupload',
            'buktiPenerimaan.image' => 'File harus berupa gambar',
            'buktiPenerimaan.max' => 'Ukuran file maksimal 2MB',
            'buktiPenerimaan.mimes' => 'Format file harus JPG, PNG, atau GIF'
        ]);

        try {
            DB::beginTransaction();

            // Upload bukti penerimaan
            $path = $this->buktiPenerimaan->store('bansos/bukti', 'public');

            // Update status dan bukti penerimaan
            $this->bansos->update([
                'status' => 'Sudah Diterima',
                'tanggal_penerimaan' => now(),
                'bukti_penerimaan' => $path
            ]);

            // Tambahkan ke riwayat status
            $this->bansos->riwayatStatus()->create([
                'status_baru' => 'Sudah Diterima',
                'status_lama' => 'Disetujui',
                'keterangan' => 'Bantuan telah diterima dan dikonfirmasi oleh penerima',
                'waktu_perubahan' => now(),
                'diubah_oleh' => auth()->id()
            ]);

            DB::commit();

            // Reset form
            $this->reset('buktiPenerimaan');
            $this->loadBansos();

            // Kirim event untuk menutup modal
            $this->dispatch('closeModal');

            // Tampilkan notifikasi sukses
            $this->dispatch('konfirmasiSuccess');
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Konfirmasi penerimaan bantuan berhasil disimpan',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat mengkonfirmasi penerimaan bantuan'
            ]);
        }
    }

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
        return view('livewire.warga.bansos-detail');
    }
}