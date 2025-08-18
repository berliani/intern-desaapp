<?php

namespace Database\Factories;

use App\Models\JenisBansos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisBansos>
 */
class JenisBansosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JenisBansos::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = $this->faker->randomElement(array_keys(JenisBansos::getKategoriOptions()));

        $bantuan = [
            'Sembako' => [
                'Bantuan Sembako', 'Bansos Sembako Covid-19', 'Sembako Ramadhan',
                'Paket Sembako Miskin Ekstrem', 'Bantuan Sembako Bencana'
            ],
            'Tunai' => [
                'Bantuan Langsung Tunai (BLT)', 'BLT Dana Desa', 'Program Keluarga Harapan (PKH)',
                'Bantuan Pangan Non-Tunai (BPNT)', 'BLT Minyak Goreng', 'Subsidi Tunai'
            ],
            'Kesehatan' => [
                'BPJS PBI', 'Kartu Indonesia Sehat', 'Bantuan Kesehatan Masyarakat',
                'Bantuan Alat Kesehatan', 'Program Kesehatan Ibu dan Anak'
            ],
            'Pendidikan' => [
                'Program Indonesia Pintar', 'Beasiswa Anak Desa', 'Bantuan Seragam Sekolah',
                'Bantuan Biaya Pendidikan', 'Beasiswa Prestasi', 'Dana BOS'
            ],
            'Perumahan' => [
                'Rumah Tidak Layak Huni (RTLH)', 'Bantuan Perbaikan Rumah', 'Bedah Rumah',
                'Bantuan Material Bangunan', 'Subsidi Listrik'
            ],
            'Pangan' => [
                'Beras untuk Rakyat Miskin (Raskin)', 'Bantuan Pangan', 'Paket Pangan Lokal',
                'Subsidi Bahan Pangan', 'Pangan untuk Lansia'
            ],
            'Pertanian' => [
                'Bantuan Bibit Pertanian', 'Pupuk Bersubsidi', 'Alsintan',
                'Bantuan Saprodi', 'Program Ketahanan Pangan'
            ],
            'UMKM' => [
                'Modal Usaha UMKM', 'Bantuan Produktif Usaha Mikro', 'Pelatihan UMKM',
                'Bantuan Peralatan UMKM', 'Pembiayaan Ultra Mikro'
            ],
            'Lainnya' => [
                'Bantuan Korban Bencana', 'Bantuan Modal UMKM', 'Bantuan Peralatan Kerja',
                'Bantuan Sosial Umum', 'Modal Usaha Keluarga'
            ],
        ];

        $institusi = [
            'Kementerian Sosial', 'Dinas Sosial', 'Pemerintah Daerah', 'Pemerintah Desa',
            'Kementerian Pendidikan', 'Dinas Kesehatan', 'BPJS Kesehatan', 'BAZNAS',
            'Kementerian Pertanian', 'Kementerian UMKM', 'Lembaga Swadaya Masyarakat', 'Perusahaan (CSR)'
        ];

        $bentukBantuan = $this->faker->randomElement(array_keys(JenisBansos::getBentukBantuanOptions()));
        $namaProgram = $this->faker->randomElement($bantuan[$kategori] ?? ['Bantuan Sosial']);

        $data = [
            'nama_bansos' => $namaProgram,
            'deskripsi' => $this->faker->paragraph(),
            'kategori' => $kategori,
            'instansi_pemberi' => $this->faker->randomElement($institusi),
            'periode' => $this->faker->randomElement(array_keys(JenisBansos::getPeriodeOptions())),
            'bentuk_bantuan' => $bentukBantuan,
            'is_active' => $this->faker->boolean(80), // 80% kemungkinan aktif
        ];

        // Tambahkan data spesifik berdasarkan bentuk bantuan
        if ($bentukBantuan === 'uang') {
            $data['nominal_standar'] = $this->faker->randomElement([300000, 500000, 1000000, 1500000, 2000000]);
        } else {
            $data['jumlah_per_penerima'] = $this->faker->randomFloat(2, 1, 100);

            $satuanMapping = [
                'barang' => ['paket', 'unit', 'lembar', 'karung'],
                'jasa' => ['sesi', 'bulan', 'paket', 'unit'],
                'voucher' => ['lembar', 'unit'],
                'bantuan_modal' => ['paket', 'unit'],
                'pelatihan' => ['sesi', 'paket', 'bulan'],
                'lainnya' => ['unit', 'paket', 'kg']
            ];

            $defaultSatuan = ['paket', 'unit', 'kg'];
            $satuanOpsi = $satuanMapping[$bentukBantuan] ?? $defaultSatuan;
            $data['satuan'] = $this->faker->randomElement($satuanOpsi);
        }

        return $data;
    }

    /**
     * Configure the model factory for bantuan tunai
     */
    public function bantuanTunai(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'kategori' => 'Tunai',
                'bentuk_bantuan' => 'uang',
                'nominal_standar' => $this->faker->numberBetween(300000, 2000000),
            ];
        });
    }

    /**
     * Configure the model factory for bantuan sembako
     */
    public function bantuanSembako(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'kategori' => 'Sembako',
                'bentuk_bantuan' => 'barang',
                'jumlah_per_penerima' => $this->faker->randomFloat(2, 5, 50),
                'satuan' => $this->faker->randomElement(['kg', 'paket', 'karung']),
            ];
        });
    }

    /**
     * Configure the model factory for bantuan pendidikan
     */
    public function bantuanPendidikan(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'kategori' => 'Pendidikan',
                'bentuk_bantuan' => $this->faker->randomElement(['uang', 'barang']),
            ];
        });
    }
}