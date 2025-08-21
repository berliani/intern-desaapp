<?php

namespace App\Livewire\Warga;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Bansos as BansosModel;
use Livewire\WithPagination;

class Bansos extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $filterTanggal = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'filterTanggal' => ['except' => '']
    ];

    public function mount()
    {
        // Inisialisasi jika diperlukan
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function getStatusColor($status)
    {
        return match($status) {
            'Diajukan' => 'yellow',
            'Dalam Verifikasi' => 'blue',
            'Diverifikasi' => 'indigo',
            'Disetujui' => 'green',
            'Ditolak' => 'red',
            'Sudah Diterima' => 'purple',
            default => 'gray'
        };
    }

    public function render()
    {
        $user = Auth::user();
        $penduduk = $user->penduduk;

        if (!$penduduk) {
            return view('livewire.warga.bansos', [
                'bansosList' => collect(),
                'penduduk' => null,
            ])->layout('layouts.app');
        }

        $query = BansosModel::where('penduduk_id', $penduduk->id)
            ->with(['jenisBansos'])
            ->when($this->search, function($query) {
                $query->whereHas('jenisBansos', function($q) {
                    $q->where('nama_bansos', 'like', '%' . $this->search . '%')
                        ->orWhere('kategori', 'like', '%' . $this->search . '%');
                })->orWhere('keterangan', 'like', '%' . $this->search . '%');
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->when($this->filterTanggal, function($query) {
                if($this->filterTanggal === 'today') {
                    $query->whereDate('tanggal_pengajuan', today());
                } elseif($this->filterTanggal === 'week') {
                    $query->whereBetween('tanggal_pengajuan', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif($this->filterTanggal === 'month') {
                    $query->whereMonth('tanggal_pengajuan', now()->month)
                          ->whereYear('tanggal_pengajuan', now()->year);
                }
            })
            ->orderBy('tanggal_pengajuan', 'desc');

        return view('livewire.warga.bansos', [
            'bansosList' => $query->paginate(10),
            'penduduk' => $penduduk,
            'statusOptions' => BansosModel::getStatusOptions(),
            'filterTanggalOptions' => [
                'today' => 'Hari Ini',
                'week' => 'Minggu Ini',
                'month' => 'Bulan Ini',
            ],
        ])->layout('layouts.app');
    }

    // Method untuk refresh data
    public function refresh()
    {
        $this->reset(['search', 'status', 'filterTanggal']);
        $this->resetPage();
    }

    public function konfirmasiPenerimaan($bansosId)
    {
        $bansos = BansosModel::find($bansosId);

        if (!$bansos || $bansos->status !== 'Disetujui') {
            $this->dispatch('showAlert', [[
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Bantuan tidak dapat dikonfirmasi.'
            ]]);
            return;
        }

        try {
            $bansos->update([
                'status' => 'Sudah Diterima',
                'tanggal_penerimaan' => now()
            ]);

            // Catat riwayat status
            $bansos->addStatusHistory('Sudah Diterima', 'Bantuan telah diterima oleh warga');

            $this->dispatch('showAlert', [[
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Konfirmasi penerimaan bantuan berhasil.'
            ]]);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [[
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat mengkonfirmasi penerimaan.'
            ]]);
        }
    }
}