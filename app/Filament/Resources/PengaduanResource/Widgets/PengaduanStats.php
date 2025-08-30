<?php

namespace App\Filament\Resources\PengaduanResource\Widgets;

use App\Models\Pengaduan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class PengaduanStats extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected int | string | array $columnSpan = 'full';

    public ?string $periode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    #[On('filter-changed')]
    public function onDashboardFilterChanged(string $dari_tanggal = '', string $sampai_tanggal = '', string $periode = 'bulan_ini'): void
    {
        $this->periode = $periode;
        if ($periode === 'kustom' && $dari_tanggal && $sampai_tanggal) {
            $this->dariTanggal = $dari_tanggal;
            $this->sampaiTanggal = $sampai_tanggal;
        } else {
            $this->setPeriodeFilter($periode);
        }
        $this->dispatch('$refresh');
    }

    public function setPeriodeFilter(string $periode): void
    {
        $periodeMap = [
            'semua_waktu' => 'semua',
            'hari_ini' => 'today',
            'minggu_ini' => 'this_week',
            'bulan_ini' => 'this_month',
            'tahun_ini' => 'this_year',
            'bulan_lalu' => 'last_month',
            'tahun_lalu' => 'last_year',
        ];
        $this->periode = $periodeMap[$periode] ?? 'semua';

        if ($this->periode === 'semua') {
            $this->dariTanggal = null;
            $this->sampaiTanggal = null;
            return;
        }
        $now = now();
        switch ($this->periode) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'this_week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'last_year':
                $start = $now->copy()->subYear()->startOfYear();
                $end = $now->copy()->subYear()->endOfYear();
                break;
            default:
                $start = null;
                $end = null;
        }
        $this->dariTanggal = $start?->toDateString();
        $this->sampaiTanggal = $end?->toDateString();
    }

    protected function getStats(): array
    {
        try {
            $tenant = Filament::getTenant();
            if (!$tenant) {
                return [
                    Stat::make('Error', 'Tenant tidak ditemukan')
                        ->description('Silakan pilih tenant yang valid.')
                        ->color('danger'),
                ];
            }
            $tenantId = $tenant->id;

            // Query dasar yang benar (menggunakan model Pengaduan)
            $baseQuery = Pengaduan::query()->where('company_id', $tenantId);

            if ($this->dariTanggal && $this->sampaiTanggal) {
                $baseQuery->whereBetween('created_at', [
                    Carbon::parse($this->dariTanggal)->startOfDay(),
                    Carbon::parse($this->sampaiTanggal)->endOfDay(),
                ]);
            }

            // Kalkulasi
            $totalPerStatus = (clone $baseQuery)
                ->select('status', \DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            $totalPerKategori = (clone $baseQuery)
                ->select('kategori', \DB::raw('count(*) as total'))
                ->groupBy('kategori')
                ->orderByDesc('total')
                ->limit(1)
                ->first();

            $totalPengaduan = $totalPerStatus->sum();
            $persentasePenyelesaian = $totalPengaduan > 0 ? round(($totalPerStatus->get('Selesai', 0) / $totalPengaduan) * 100) : 0;
            $prioritasTinggi = (clone $baseQuery)->where('prioritas', 'Tinggi')->where('status', 'Belum Ditangani')->count();

            $pengaduanMingguIni = (clone $baseQuery)
                ->where('created_at', '>=', now()->startOfWeek())
                ->count();

            $waktuPenanganan = (clone $baseQuery)
                ->whereIn('status', ['Selesai', 'Ditolak'])
                ->whereNotNull('tanggal_tanggapan')
                ->whereNotNull('created_at')
                ->select(\DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, tanggal_tanggapan)) as rata_rata'))
                ->first();
            $rataRataWaktu = $waktuPenanganan?->rata_rata ? round($waktuPenanganan->rata_rata) : 0;

            $belumSelesai = $totalPerStatus->get('Belum Ditangani', 0) + $totalPerStatus->get('Sedang Diproses', 0);

            $kategoriTerbanyak = $totalPerKategori?->kategori ?? 'Tidak ada';
            $jumlahKategoriTerbanyak = $totalPerKategori?->total ?? 0;

            $periodeLabel = match ($this->periode) {
                'today' => 'hari ini',
                'this_week' => 'minggu ini',
                'this_month' => 'bulan ini',
                'this_year' => 'tahun ini',
                'last_month' => 'bulan lalu',
                'last_year' => 'tahun lalu',
                default => 'semua waktu'
            };

            return [
                Stat::make('Total Pengaduan', number_format($totalPengaduan))
                    ->description('Pengaduan warga ' . $periodeLabel)->descriptionIcon('heroicon-o-megaphone')->color('primary'),
                Stat::make('Belum Ditangani', number_format($totalPerStatus->get('Belum Ditangani', 0)))
                    ->description('Perlu tanggapan segera')->descriptionIcon('heroicon-o-clock')->color('warning'),
                Stat::make('Prioritas Tinggi', number_format($prioritasTinggi))
                    ->description('Butuh penanganan cepat')->descriptionIcon('heroicon-o-exclamation-triangle')->color('danger'),
                Stat::make('Sedang Diproses', number_format($totalPerStatus->get('Sedang Diproses', 0)))
                    ->description('Dalam penanganan')->descriptionIcon('heroicon-o-arrow-path')->color('info'),
                Stat::make('Pengaduan Aktif', number_format($belumSelesai))
                    ->description('Belum ditangani + Sedang diproses')->descriptionIcon('heroicon-o-bell-alert')->color('gray'),
                Stat::make('Selesai Ditangani', number_format($totalPerStatus->get('Selesai', 0)))
                    ->description($persentasePenyelesaian . '% dari total pengaduan')->descriptionIcon('heroicon-o-check-circle')->color('success'),
                Stat::make('Kategori Terbanyak', $kategoriTerbanyak)
                    ->description($jumlahKategoriTerbanyak . ' pengaduan')->descriptionIcon('heroicon-o-bars-3')->color('indigo'),
                Stat::make('Pengaduan Minggu Ini', number_format($pengaduanMingguIni))
                    ->description('Sejak ' . now()->startOfWeek()->format('d M Y'))->descriptionIcon('heroicon-o-calendar')->color('blue'),
                Stat::make('Rata-rata Penanganan', $rataRataWaktu . ' jam')
                    ->description('Waktu respon pengaduan')->descriptionIcon('heroicon-o-clock')->color('emerald'),
                Stat::make('Ditolak', number_format($totalPerStatus->get('Ditolak', 0)))
                    ->description('Pengaduan tidak valid')->descriptionIcon('heroicon-o-x-circle')->color('rose'),
            ];
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('PengaduanStats widget error: ' . $e->getMessage());

            // Tampilkan pesan error di widget
            return [
                Stat::make('Error', 'Gagal memuat statistik')
                    ->description($e->getMessage())
                    ->color('danger'),
            ];
        }
    }
}
