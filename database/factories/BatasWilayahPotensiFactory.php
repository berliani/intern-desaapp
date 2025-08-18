<?php

namespace Database\Factories;

use App\Models\BatasWilayahPotensi;
use App\Models\ProfilDesa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BatasWilayahPotensi>
 */
class BatasWilayahPotensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = BatasWilayahPotensi::class;

    public function definition(): array
    {
        // Daftar nama desa umum untuk batas
        $namaDesa = [
            'Sukamaju', 'Sukamakmur', 'Cibinong', 'Cianjur', 'Sindanglaya',
            'Margahayu', 'Sukamekar', 'Cikalong', 'Cipatujah', 'Panumbangan',
            'Sumberejo', 'Tanjungsari', 'Sukaresmi', 'Cibeureum', 'Karangsari',
            'Bojongsoang', 'Panyingkiran', 'Cihideung', 'Sindangbarang', 'Cikaret'
        ];

        // Generate luas wilayah antara 500.000 m² (50 Ha) dan 10.000.000 m² (1000 Ha)
        $luasWilayah = $this->faker->numberBetween(500000, 10000000);

        // Kategori potensi
        $kategoriPotensi = [
            'sda', 'pertanian', 'peternakan', 'pariwisata',
            'industri', 'budaya', 'lingkungan', 'pendidikan',
            'kesehatan', 'lainnya'
        ];

        // Satuan untuk berbagai kategori
        $satuanMap = [
            'sda' => ['Ha', 'Km²', 'Mata Air', 'Lokasi'],
            'pertanian' => ['Ha', 'Ton/Tahun', 'Jenis'],
            'peternakan' => ['Ekor', 'Kandang', 'Kelompok'],
            'pariwisata' => ['Lokasi', 'Objek', 'Paket'],
            'industri' => ['Unit', 'Kelompok', 'Sentra'],
            'budaya' => ['Kelompok', 'Tradisi', 'Event/Tahun'],
            'pendidikan' => ['Lembaga', 'Siswa', 'Unit'],
            'kesehatan' => ['Fasilitas', 'Tenaga', 'Unit'],
            'lingkungan' => ['Lokasi', 'Ha', 'Titik'],
            'lainnya' => ['Unit', 'Lokasi', 'Item']
        ];

        // Generate potensi desa (5-10 item)
        $potensiDesa = [];
        $jumlahPotensi = $this->faker->numberBetween(5, 10);

        for ($i = 0; $i < $jumlahPotensi; $i++) {
            $kategori = $this->faker->randomElement($kategoriPotensi);

            $potensiDesa[] = [
                'nama' => ucfirst($this->faker->words(3, true)),
                'kategori' => $kategori,
                'lokasi' => $this->faker->optional(0.8)->sentence(3),
                'deskripsi' => $this->faker->optional(0.7)->sentence(10),
            ];
        }

        return [
            'created_by' => User::factory(),

            // Informasi Wilayah
            'luas_wilayah' => $luasWilayah,
            'batas_utara' => 'Desa ' . $this->faker->randomElement($namaDesa),
            'batas_timur' => 'Desa ' . $this->faker->randomElement($namaDesa),
            'batas_selatan' => 'Desa ' . $this->faker->randomElement($namaDesa),
            'batas_barat' => 'Desa ' . $this->faker->randomElement($namaDesa),
            'keterangan_batas' => $this->faker->optional(0.7)->sentence(10),

            // Potensi Desa (format JSON fleksibel)
            'potensi_desa' => $potensiDesa,
            'keterangan_potensi' => $this->faker->optional(0.5)->sentence(10),
        ];
    }
}