<?php

namespace App\Exports;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class KartuKeluargaExporter implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $kkSearchHashes;

    public function __construct(Collection $kkSearchHashes)
    {
        $this->kkSearchHashes = $kkSearchHashes;
    }

    /**
    * Mengambil koleksi data yang akan diekspor.
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua data penduduk yang relevan berdasarkan hash KK,
        // lalu urutkan berdasarkan tanggal lahir.
        return Penduduk::whereIn('kk_search_hash', $this->kkSearchHashes)
            ->get()
            ->sortBy(function($penduduk) {
                try {
                    return Carbon::parse($penduduk->tanggal_lahir)->timestamp;
                } catch (\Exception $e) {
                    return 0; // Fallback jika tanggal lahir tidak valid
                }
            })
            ->sortBy('kk_search_hash');
    }

    /**
     * Menentukan header untuk setiap kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'Nomor KK',
            'NIK',
            'Nama Lengkap',
            'Status Hubungan',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Pendidikan',
            'Pekerjaan',
            'Status Perkawinan',
            'Alamat',
            'RT/RW',
        ];
    }

    /**
     * Memetakan data dari setiap record ke baris di Excel.
     *
     * @param mixed $penduduk
     * @return array
     */
    public function map($penduduk): array
    {
        return [
            "'" . $penduduk->kk, // Tambahkan tanda kutip agar dibaca sebagai string oleh Excel
            "'" . $penduduk->nik,
            $penduduk->nama,
            $penduduk->kepala_keluarga ? 'Kepala Keluarga' : 'Anggota Keluarga',
            $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            $penduduk->tempat_lahir,
            Carbon::parse($penduduk->tanggal_lahir)->format('d-m-Y'),
            $penduduk->agama,
            $penduduk->pendidikan,
            $penduduk->pekerjaan,
            $penduduk->status_perkawinan,
            $penduduk->alamat,
            $penduduk->rt_rw,
        ];
    }
}
