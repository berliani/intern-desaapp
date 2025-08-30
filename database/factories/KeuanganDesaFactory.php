<?php

namespace Database\Factories;

use App\Models\KeuanganDesa;
use App\Models\ProfilDesa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KeuanganDesa>
 */
class KeuanganDesaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = KeuanganDesa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenis = $this->faker->randomElement(['Pemasukan', 'Pengeluaran']);

        $deskripsiPemasukan = [
            'Dana Desa',
            'Alokasi Dana Desa (ADD)',
            'Bagi Hasil Pajak dan Retribusi',
            'Bantuan Keuangan Kabupaten/Kota',
            'Pendapatan Asli Desa',
            'Sumbangan Pihak Ketiga',
            'Hibah Desa',
            'Hasil Usaha Desa',
            'Swadaya dan Partisipasi Masyarakat',
            'Dana Pembangunan',
        ];

        $deskripsiPengeluaran = [
            'Pembangunan Infrastruktur',
            'Operasional Pemerintah Desa',
            'Pembinaan Kemasyarakatan',
            'Pemberdayaan Masyarakat',
            'Belanja Pegawai',
            'Perawatan Aset Desa',
            'Penyelenggaraan Kegiatan Desa',
            'Bantuan Sosial',
            'Biaya Tak Terduga',
            'Kegiatan Pendidikan',
        ];

        $deskripsi = $jenis === 'Pemasukan' ?
            $this->faker->randomElement($deskripsiPemasukan) :
            $this->faker->randomElement($deskripsiPengeluaran);

        // Nilai sebagai integer tanpa desimal (dalam Rupiah)
        $jumlah = $jenis === 'Pemasukan' ?
            $this->faker->numberBetween(1000000, 50000000) :
            $this->faker->numberBetween(500000, 30000000);

        return [
            'desa_id' => ProfilDesa::factory(),
            'created_by' => User::factory(),
            'jenis' => $jenis,
            'deskripsi' => $deskripsi,
            'jumlah' => $jumlah, // Nilai disimpan sebagai integer
            'tanggal' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * State untuk pemasukan.
     */
    public function pemasukan()
    {
        return $this->state(function (array $attributes) {
            $deskripsiPemasukan = [
                'Dana Desa',
                'Alokasi Dana Desa (ADD)',
                'Bagi Hasil Pajak dan Retribusi',
                'Bantuan Keuangan Kabupaten/Kota',
                'Pendapatan Asli Desa',
                'Sumbangan Pihak Ketiga',
                'Hibah Desa',
                'Hasil Usaha Desa',
            ];

            return [
                'jenis' => 'Pemasukan',
                'deskripsi' => $this->faker->randomElement($deskripsiPemasukan),
                'jumlah' => $this->faker->numberBetween(1000000, 50000000),
            ];
        });
    }

    /**
     * State untuk pengeluaran.
     */
    public function pengeluaran()
    {
        return $this->state(function (array $attributes) {
            $deskripsiPengeluaran = [
                'Pembangunan Infrastruktur',
                'Operasional Pemerintah Desa',
                'Pembinaan Kemasyarakatan',
                'Pemberdayaan Masyarakat',
                'Belanja Pegawai',
                'Perawatan Aset Desa',
                'Penyelenggaraan Kegiatan Desa',
                'Bantuan Sosial',
            ];

            return [
                'jenis' => 'Pengeluaran',
                'deskripsi' => $this->faker->randomElement($deskripsiPengeluaran),
                'jumlah' => $this->faker->numberBetween(500000, 30000000),
            ];
        });
    }
}
