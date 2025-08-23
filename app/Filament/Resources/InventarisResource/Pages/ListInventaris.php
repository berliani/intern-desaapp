<?php

namespace App\Filament\Resources\InventarisResource\Pages;

use App\Filament\Resources\InventarisResource;
use App\Filament\Resources\InventarisResource\Widgets\InventarisStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Carbon\Carbon;
use Filament\Forms;

class ListInventaris extends ListRecords
{
    protected static string $resource = InventarisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Inventaris')
                ->icon('heroicon-o-plus'),
  // Filter periode
  Actions\Action::make('filterPeriode')
  ->label('Filter Periode')
  ->icon('heroicon-o-funnel')
  ->form([
      Select::make('periode_opsi')
          ->label('Pilih Periode')
          ->options([
              'semua' => 'Semua Data',
              'kustom' => 'Kustom (Pilih Tanggal)',
              'hari_ini' => 'Hari Ini',
              'minggu_ini' => 'Minggu Ini',
              'bulan_ini' => 'Bulan Ini',
              'tahun_ini' => 'Tahun Ini',
              'bulan_lalu' => 'Bulan Lalu',
              'tahun_lalu' => 'Tahun Lalu',
          ])
          ->default('semua')
          ->live()
          ->afterStateUpdated(function ($state, callable $set) {
              switch ($state) {
                  case 'hari_ini':
                      $set('dari_tanggal', now()->toDateString());
                      $set('sampai_tanggal', now()->toDateString());
                      break;
                  case 'minggu_ini':
                      $set('dari_tanggal', now()->startOfWeek()->toDateString());
                      $set('sampai_tanggal', now()->endOfWeek()->toDateString());
                      break;
                  case 'bulan_ini':
                      $set('dari_tanggal', now()->startOfMonth()->toDateString());
                      $set('sampai_tanggal', now()->endOfMonth()->toDateString());
                      break;
                  case 'tahun_ini':
                      $set('dari_tanggal', now()->startOfYear()->toDateString());
                      $set('sampai_tanggal', now()->endOfYear()->toDateString());
                      break;
                  case 'bulan_lalu':
                      $set('dari_tanggal', now()->subMonth()->startOfMonth()->toDateString());
                      $set('sampai_tanggal', now()->subMonth()->endOfMonth()->toDateString());
                      break;
                  case 'tahun_lalu':
                      $set('dari_tanggal', now()->subYear()->startOfYear()->toDateString());
                      $set('sampai_tanggal', now()->subYear()->endOfYear()->toDateString());
                      break;
                  case 'semua':
                      $set('dari_tanggal', null);
                      $set('sampai_tanggal', null);
                      break;
              }
          }),
      DatePicker::make('dari_tanggal')
          ->label('Dari Tanggal')
          ->required()
          ->visible(fn ($get) => $get('periode_opsi') === 'kustom')
          ->default(now()->startOfMonth()),
      DatePicker::make('sampai_tanggal')
          ->label('Sampai Tanggal')
          ->required()
          ->visible(fn ($get) => $get('periode_opsi') === 'kustom')
          ->default(now()->endOfMonth()),
  ])
  ->action(function (array $data): void {
      // Mengirim event ke InventarisStats widget
      $dari = $data['dari_tanggal'] ?? null;
      $sampai = $data['sampai_tanggal'] ?? null;
      $periode = $data['periode_opsi'] ?? 'semua';

      // Jika bukan kustom, hitung tanggal berdasarkan opsi
      if ($periode !== 'kustom') {
          switch ($periode) {
              case 'hari_ini':
                  $dari = now()->toDateString();
                  $sampai = now()->toDateString();
                  break;
              case 'minggu_ini':
                  $dari = now()->startOfWeek()->toDateString();
                  $sampai = now()->endOfWeek()->toDateString();
                  break;
              case 'bulan_ini':
                  $dari = now()->startOfMonth()->toDateString();
                  $sampai = now()->endOfMonth()->toDateString();
                  break;
              case 'tahun_ini':
                  $dari = now()->startOfYear()->toDateString();
                  $sampai = now()->endOfYear()->toDateString();
                  break;
              case 'bulan_lalu':
                  $dari = now()->subMonth()->startOfMonth()->toDateString();
                  $sampai = now()->subMonth()->endOfMonth()->toDateString();
                  break;
              case 'tahun_lalu':
                  $dari = now()->subYear()->startOfYear()->toDateString();
                  $sampai = now()->subYear()->endOfYear()->toDateString();
                  break;
              case 'semua':
                  $dari = null;
                  $sampai = null;
                  break;
          }
      }

      $this->dispatch('inventaris-filter-changed',
          periode: $periode,
          dari: $dari,
          sampai: $sampai
      );
  }),
            // Aksi export
            Actions\Action::make('export')
                ->label('Ekspor Semua')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    Select::make('kategori')
                        ->label('Kategori')
                        ->options([
                            'Semua Kategori' => 'Semua Kategori',
                            'Elektronik' => 'Elektronik',
                            'Furniture' => 'Furniture',
                            'Kendaraan' => 'Kendaraan',
                            'Alat Kantor' => 'Alat Kantor',
                            'Lainnya' => 'Lainnya',
                        ])
                        ->default('Semua Kategori'),

                    Select::make('periode')
                        ->label('Periode')
                        ->options([
                            'semua' => 'Semua Waktu',
                            'hari_ini' => 'Hari Ini',
                            'minggu_ini' => 'Minggu Ini',
                            'bulan_ini' => 'Bulan Ini',
                            'tahun_ini' => 'Tahun Ini',
                            'kustom' => 'Kustom',
                        ])
                        ->default('bulan_ini')
                        ->reactive()
                        ->afterStateUpdated(function($state, Forms\Set $set) {
                            if ($state !== 'kustom') {
                                $set('dari_tanggal', null);
                                $set('sampai_tanggal', null);
                            }
                        }),

                    // Menggunakan dua DatePicker terpisah
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('dari_tanggal')
                                ->label('Dari Tanggal')
                                ->visible(fn(Forms\Get $get) => $get('periode') === 'kustom'),

                            Forms\Components\DatePicker::make('sampai_tanggal')
                                ->label('Sampai Tanggal')
                                ->visible(fn(Forms\Get $get) => $get('periode') === 'kustom'),
                        ]),

                    // Format ekspor
                    Forms\Components\Radio::make('format')
                        ->label('Format Ekspor')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                ])
                ->action(function (array $data): void {
                    // Proses periode ke tanggal
                    $dariTanggal = null;
                    $sampaiTanggal = null;

                    if ($data['periode'] === 'hari_ini') {
                        $dariTanggal = today()->format('Y-m-d');
                        $sampaiTanggal = today()->format('Y-m-d');
                    } elseif ($data['periode'] === 'minggu_ini') {
                        $dariTanggal = today()->startOfWeek()->format('Y-m-d');
                        $sampaiTanggal = today()->endOfWeek()->format('Y-m-d');
                    } elseif ($data['periode'] === 'bulan_ini') {
                        $dariTanggal = today()->startOfMonth()->format('Y-m-d');
                        $sampaiTanggal = today()->endOfMonth()->format('Y-m-d');
                    } elseif ($data['periode'] === 'tahun_ini') {
                        $dariTanggal = today()->startOfYear()->format('Y-m-d');
                        $sampaiTanggal = today()->endOfYear()->format('Y-m-d');
                    } elseif ($data['periode'] === 'kustom') {
                        $dariTanggal = isset($data['dari_tanggal']) ? Carbon::parse($data['dari_tanggal'])->format('Y-m-d') : null;
                        $sampaiTanggal = isset($data['sampai_tanggal']) ? Carbon::parse($data['sampai_tanggal'])->format('Y-m-d') : null;
                    }

                    // Redirect ke controller dengan parameter
                    $url = route('inventaris.export.all', [
                        'kategori' => $data['kategori'] !== 'Semua Kategori' ? $data['kategori'] : null,
                        'dari_tanggal' => $dariTanggal,
                        'sampai_tanggal' => $sampaiTanggal,
                        'format' => $data['format'],
                    ]);

                    redirect()->away($url);
                }),


        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            InventarisStats::class,
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}