<?php

namespace Database\Factories;

use App\Models\BatasWilayahPotensi;
use App\Models\ProfilDesa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfilDesa>
 */
class ProfilDesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ProfilDesa::class;

    public function definition(): array
    {
        // Daftar nama desa umum di Indonesia
        $namaDesa = $this->faker->randomElement([
            'Sukamaju', 'Sukamakmur', 'Cibinong', 'Cianjur', 'Sindanglaya',
            'Margahayu', 'Sukamekar', 'Cikalong', 'Cipatujah', 'Panumbangan',
            'Sumberejo', 'Tanjungsari', 'Sukaresmi', 'Cibeureum', 'Karangsari',
            'Bojongsoang', 'Panyingkiran', 'Cihideung', 'Sindangbarang', 'Cikaret'
        ]);

        // Daftar kecamatan umum di Indonesia
        $kecamatan = $this->faker->randomElement([
            'Cianjur', 'Cibinong', 'Cileungsi', 'Cicalengka', 'Lembang',
            'Padalarang', 'Ciwidey', 'Soreang', 'Ciparay', 'Majalaya',
            'Rancaekek', 'Dayeuhkolot', 'Banjaran', 'Pangalengan', 'Cikancung'
        ]);

        // Daftar kabupaten di Jawa Barat
        $kabupaten = $this->faker->randomElement([
            'Bandung', 'Bekasi', 'Bogor', 'Ciamis', 'Cianjur',
            'Cirebon', 'Garut', 'Indramayu', 'Karawang', 'Kuningan',
            'Majalengka', 'Pangandaran', 'Purwakarta', 'Subang', 'Sukabumi',
            'Sumedang', 'Tasikmalaya'
        ]);

        // Tentukan provinsi (tetap Jawa Barat untuk konsistensi)
        $provinsi = 'Jawa Barat';

        // Generate kode pos yang masuk akal (4xxxxx untuk Jawa Barat)
        $kodePos = '4' . $this->faker->randomNumber(4, true);

        // Generate luas wilayah antara 500.000 m² (50 Ha) dan 10.000.000 m² (1000 Ha)
        $luasWilayah = $this->faker->numberBetween(500000, 10000000);

        return [
            'created_by' => User::factory(),
            'nama_desa' => $namaDesa,
            'kecamatan' => $kecamatan,
            'kabupaten' => $kabupaten,
            'provinsi' => $provinsi,
            'kode_pos' => $kodePos,
            'alamat' => $this->faker->address(),
            'telepon' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'website' => $this->faker->optional(0.7)->url(),
            'visi' => 'Mewujudkan ' . $namaDesa . ' sebagai desa yang mandiri, sejahtera, dan berbudaya berbasis teknologi dan pertanian berkelanjutan pada tahun 2025.',
            'misi' => "1. Meningkatkan pembangunan infrastruktur yang mendukung perekonomian desa\n2. Meningkatkan pemberdayaan masyarakat\n3. Meningkatkan pelayanan kesehatan masyarakat\n4. Meningkatkan kualitas pendidikan\n5. Meningkatkan keamanan dan ketertiban masyarakat",
            'sejarah' => $this->faker->paragraphs(5, true),
            'luas_wilayah' => $luasWilayah,
            'logo' => 'uploads/desa/default-logo.png',
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (ProfilDesa $profilDesa) {
            // Secara default tidak membuat batas wilayah dan potensi
        });
    }

    /**
     * Tambahkan data batas wilayah dan potensi setelah pembuatan ProfilDesa
     */
    public function withBatasWilayahPotensi(): static
    {
        return $this->afterCreating(function (ProfilDesa $profilDesa) {
            BatasWilayahPotensi::factory()->create([
                'profil_desa_id' => $profilDesa->id,
                'created_by' => $profilDesa->created_by,
            ]);
        });
    }
}