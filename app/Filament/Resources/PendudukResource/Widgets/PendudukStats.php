<?php

namespace App\Filament\Resources\PendudukResource\Widgets;

use App\Models\Penduduk;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;

class PendudukStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';

    // Properties untuk filter
    public ?string $periode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    public function mount(): void
    {

        $this->setPeriodeFilter('semua');
    }

    // Helper untuk mengatur periode filter
    public function setPeriodeFilter(string $periode): void
    {
        $this->periode = $periode;

        switch ($periode) {
            case 'hari_ini':
                $this->dariTanggal = now()->toDateString();
                $this->sampaiTanggal = now()->toDateString();
                break;
            case 'minggu_ini':
                $this->dariTanggal = now()->startOfWeek()->toDateString();
                $this->sampaiTanggal = now()->endOfWeek()->toDateString();
                break;
            case 'bulan_ini':
                $this->dariTanggal = now()->startOfMonth()->toDateString();
                $this->sampaiTanggal = now()->endOfMonth()->toDateString();
                break;
            case 'tahun_ini':
                $this->dariTanggal = now()->startOfYear()->toDateString();
                $this->sampaiTanggal = now()->endOfYear()->toDateString();
                break;
            case 'bulan_lalu':
                $this->dariTanggal = now()->subMonth()->startOfMonth()->toDateString();
                $this->sampaiTanggal = now()->subMonth()->endOfMonth()->toDateString();
                break;
            case 'tahun_lalu':
                $this->dariTanggal = now()->subYear()->startOfYear()->toDateString();
                $this->sampaiTanggal = now()->subYear()->endOfYear()->toDateString();
                break;
            case 'semua':
            case 'semua_waktu':
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
                break;
            default:
                // Untuk kustom, tanggal akan ditetapkan secara manual
                break;
        }
    }

    // Listener untuk event global-filter-changed dari page
    #[On('global-filter-changed')]
    public function handleGlobalFilterChanged($data): void
    {
        if (isset($data['periode'])) {
            $this->periode = $data['periode'];

            // Set tanggal berdasarkan periode
            $this->setPeriodeFilter($this->periode);
        }

        if (isset($data['dariTanggal'])) {
            $this->dariTanggal = $data['dariTanggal'];
        }

        if (isset($data['sampaiTanggal'])) {
            $this->sampaiTanggal = $data['sampaiTanggal'];
        }

        // Debug log untuk memastikan filter berhasil diterima
        Log::info('PendudukStats menerima filter', [
            'periode' => $this->periode,
            'dariTanggal' => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal
        ]);
    }

    // Tambahkan listener untuk event dari Dashboard
    #[On('filter-changed')]
    public function onDashboardFilterChanged(string $dari_tanggal = '', string $sampai_tanggal = '', string $periode = 'semua'): void
    {
        $this->periode = $periode;

        if ($periode === 'kustom' && $dari_tanggal && $sampai_tanggal) {
            $this->dariTanggal = $dari_tanggal;
            $this->sampaiTanggal = $sampai_tanggal;
        } else {
            // Gunakan setPeriodeFilter untuk mengatur tanggal berdasarkan periode
            $this->setPeriodeFilter($periode);
        }

        Log::info('PendudukStats menerima filter dari Dashboard', [
            'periode' => $this->periode,
            'dariTanggal' => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal
        ]);
    }

    protected function getPeriodeDisplayText(): string
    {
        if ($this->periode === 'kustom' && $this->dariTanggal && $this->sampaiTanggal) {
            return Carbon::parse($this->dariTanggal)->format('d/m/Y') . ' - ' . Carbon::parse($this->sampaiTanggal)->format('d/m/Y');
        }

        return match($this->periode) {
            'hari_ini' => 'Hari Ini',
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'tahun_ini' => 'Tahun Ini',
            'bulan_lalu' => 'Bulan Lalu',
            'tahun_lalu' => 'Tahun Lalu',
            'semua', 'semua_waktu' => 'Semua Waktu',
            default => 'Periode'
        };
    }

    protected function getStats(): array
    {
        $tenantId = Filament::getTenant()->id;
        try {
            // Query dasar
             $query = Penduduk::query()->where('company_id', $tenantId);

            // Terapkan filter tanggal jika ada dan bukan 'semua'
            if ($this->dariTanggal && $this->sampaiTanggal && !in_array($this->periode, ['semua', 'semua_waktu'])) {
                $startDateTime = Carbon::parse($this->dariTanggal)->startOfDay();
                $endDateTime = Carbon::parse($this->sampaiTanggal)->endOfDay();

                $query->whereBetween('created_at', [
                    $startDateTime->toDateTimeString(),
                    $endDateTime->toDateTimeString()
                ]);
            }

            // Gunakan clone query untuk menghindari perubahan pada query asli
            $totalPenduduk = $query->count();
            $totalKepalaKeluarga = (clone $query)->where('kepala_keluarga', true)->count();
            $totalAnggotaKeluarga = (clone $query)->where('kepala_keluarga', false)->count();
            $totalLakiLaki = (clone $query)->where('jenis_kelamin', 'L')->count();
            $totalPerempuan = (clone $query)->where('jenis_kelamin', 'P')->count();

            // Hitung persentase
            $persenLakiLaki = $totalPenduduk > 0 ? round(($totalLakiLaki / $totalPenduduk) * 100) : 0;
            $persenPerempuan = $totalPenduduk > 0 ? round(($totalPerempuan / $totalPenduduk) * 100) : 0;

            // Analisis RT/RW
            $rtRwData = $this->hitungRtRw($query);

            // Analisis pendidikan
            $pendidikanData = $this->hitungPendidikan($totalPenduduk, $query);

            // Informasi periode yang digunakan
            $periodeDisplay = $this->getPeriodeDisplayText();

            return [
                Stat::make('Total Penduduk', number_format($totalPenduduk, 0, ',', '.'))
                    ->description(!in_array($this->periode, ['semua', 'semua_waktu']) ? "Periode: {$periodeDisplay}" : "Semua data penduduk")
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('primary'),

                Stat::make('Perbandingan L/P', "{$persenLakiLaki}% : {$persenPerempuan}%")
                    ->description("L: " . number_format($totalLakiLaki) . " | P: " . number_format($totalPerempuan))
                    ->descriptionIcon('heroicon-m-user')
                    ->color('success')
                    ->chart([
                        $totalLakiLaki,
                        $totalPerempuan,
                    ]),

                Stat::make('Total RT', number_format($rtRwData['total_rt'], 0, ',', '.'))
                    ->description('Jumlah RT dalam desa')
                    ->descriptionIcon('heroicon-m-map-pin')
                    ->color('warning'),

                Stat::make('Total RW', number_format($rtRwData['total_rw'], 0, ',', '.'))
                    ->description('Jumlah RW dalam desa')
                    ->descriptionIcon('heroicon-m-map')
                    ->color('info'),

                Stat::make('Kepala Keluarga', number_format($totalKepalaKeluarga, 0, ',', '.'))
                    ->description(($totalPenduduk > 0 ? round(($totalKepalaKeluarga / $totalPenduduk) * 100) : 0) . '% dari total penduduk')
                    ->descriptionIcon('heroicon-m-home')
                    ->color('danger'),

                Stat::make('Pendidikan Terbanyak', $pendidikanData['pendidikan_terbanyak'])
                    ->description($pendidikanData['jumlah_pendidikan_terbanyak'] . ' Orang (' . $pendidikanData['persentase'] . '%)')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->color('success'),
            ];
        } catch (\Exception $e) {
            Log::error('Error di PendudukStats: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Return fallback stats jika terjadi error
            return [
                Stat::make('Error', 'Gagal memuat data')
                    ->description('Terjadi kesalahan saat memuat statistik penduduk')
                    ->color('danger'),
            ];
        }
    }
    
    protected function hitungRtRw($query): array
    {
        // Menghitung jumlah RT unik (distinct) yang tidak kosong
        $totalRt = (clone $query)
            ->whereNotNull('rt')
            ->where('rt', '<>', '')
            ->distinct()
            ->count('rt');

        // Menghitung jumlah RW unik (distinct) yang tidak kosong
        $totalRw = (clone $query)
            ->whereNotNull('rw')
            ->where('rw', '<>', '')
            ->distinct()
            ->count('rw');
      
      protected function hitungRtRw($query = null): array
    {
        $baseQuery = $query ? clone $query : Penduduk::query();

        $totalRt = $baseQuery->clone()->whereNotNull('rt')->distinct()->count('rt');
        $totalRw = $baseQuery->clone()->whereNotNull('rw')->distinct()->count('rw');
        return [
            'total_rt' => $totalRt,
            'total_rw' => $totalRw,
        ];
    }

    protected function hitungPendidikan(int $totalPenduduk, $query = null): array
    {
        $result = [
            'pendidikan_terbanyak' => 'Tidak Ada Data',
            'jumlah_pendidikan_terbanyak' => 0,
            'persentase' => 0
        ];

        // Gunakan query yang diberikan atau buat query baru
        if (!$query) {
            $query = Penduduk::query();

            // Terapkan filter tanggal jika ada dan bukan 'semua'
            if ($this->dariTanggal && $this->sampaiTanggal && $this->periode !== 'semua') {
                $startDateTime = Carbon::parse($this->dariTanggal)->startOfDay();
                $endDateTime = Carbon::parse($this->sampaiTanggal)->endOfDay();

                $query->whereBetween('created_at', [
                    $startDateTime->toDateTimeString(),
                    $endDateTime->toDateTimeString()
                ]);
            }
        }

        // Hitung jumlah penduduk per tingkat pendidikan
        $pendidikanStats = (clone $query)
            ->select('pendidikan', DB::raw('count(*) as total'))
            ->whereNotNull('pendidikan')
            ->where('pendidikan', '<>', '')
            ->groupBy('pendidikan')
            ->orderByDesc('total')
            ->get();

        if ($pendidikanStats->isEmpty()) {
            return $result;
        }

        // Ambil pendidikan dengan jumlah terbanyak
        $terbanyak = $pendidikanStats->first();
        $persentase = $totalPenduduk > 0 ? round(($terbanyak->total / $totalPenduduk) * 100) : 0;

        $result['pendidikan_terbanyak'] = $terbanyak->pendidikan;
        $result['jumlah_pendidikan_terbanyak'] = number_format($terbanyak->total, 0, ',', '.');
        $result['persentase'] = $persentase;

        return $result;
    }
}
