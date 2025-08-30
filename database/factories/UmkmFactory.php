<?php

namespace Database\Factories;

use App\Models\Umkm;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Umkm>
 */
class UmkmFactory extends Factory
{
    protected $model = Umkm::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = $this->faker->randomElement([
            'Kuliner', 'Kerajinan', 'Fashion', 'Pertanian', 'Jasa', 'Lainnya'
        ]);

        $namaUsaha = match ($kategori) {
            'Kuliner' => $this->faker->randomElement([
                'Warung ' . $this->faker->lastName(),
                'Rumah Makan ' . $this->faker->lastName(),
                'Bakso ' . $this->faker->firstName(),
                'Warteg ' . $this->faker->lastName(),
                'Catering ' . $this->faker->lastName(),
                'Sate ' . $this->faker->lastName()
            ]),
            'Kerajinan' => $this->faker->randomElement([
                'Kerajinan ' . $this->faker->word(),
                'Anyaman ' . $this->faker->lastName(),
                'Ukiran ' . $this->faker->lastName(),
                'Batik ' . $this->faker->lastName(),
                'Tenun ' . $this->faker->lastName()
            ]),
            'Fashion' => $this->faker->randomElement([
                'Butik ' . $this->faker->lastName(),
                'Jahitan ' . $this->faker->lastName(),
                'Konveksi ' . $this->faker->lastName(),
                'Fashion ' . $this->faker->lastName()
            ]),
            'Pertanian' => $this->faker->randomElement([
                'Tani ' . $this->faker->lastName(),
                'Peternakan ' . $this->faker->lastName(),
                'Pertanian ' . $this->faker->word(),
                'Kebun ' . $this->faker->word()
            ]),
            'Jasa' => $this->faker->randomElement([
                'Bengkel ' . $this->faker->lastName(),
                'Salon ' . $this->faker->lastName(),
                'Jasa ' . $this->faker->word(),
                'Servis ' . $this->faker->lastName()
            ]),
            default => 'UMKM ' . $this->faker->lastName(),
        };

        $produk = match ($kategori) {
            'Kuliner' => $this->faker->randomElement([
                'Nasi Goreng dan Mie Ayam', 'Bakso dan Soto', 'Masakan Padang',
                'Ayam Goreng dan Bakar', 'Kue dan Roti', 'Pisang Goreng', 'Martabak',
                'Gorengan dan Pecel', 'Sate dan Gule'
            ]),
            'Kerajinan' => $this->faker->randomElement([
                'Anyaman Bambu', 'Ukiran Kayu', 'Gerabah', 'Tas Rajut',
                'Kerajinan Rotan', 'Hiasan Dinding', 'Patung'
            ]),
            'Fashion' => $this->faker->randomElement([
                'Baju dan Celana', 'Gamis dan Hijab', 'Batik', 'Jasa Jahit',
                'Kemeja dan Rok', 'Kaos Sablon', 'Tas dan Sepatu'
            ]),
            'Pertanian' => $this->faker->randomElement([
                'Sayuran Organik', 'Buah-buahan', 'Bibit Tanaman', 'Pupuk',
                'Telur Ayam', 'Daging Ayam dan Sapi', 'Beras'
            ]),
            'Jasa' => $this->faker->randomElement([
                'Service Motor', 'Salon Kecantikan', 'Laundry', 'Jasa Antar',
                'Tukang Bangunan', 'Jasa Desain', 'Service Elektronik'
            ]),
            default => 'Produk ' . $this->faker->word() . ' dan ' . $this->faker->word(),
        };

        $lokasi = 'RT ' . rand(1, 10) . ' RW ' . rand(1, 5) . ', Dusun ' . $this->faker->word() . ', ' . $this->faker->city();

        $deskripsi = match ($kategori) {
            'Kuliner' => 'Menyediakan makanan ' . strtolower($produk) . ' dengan cita rasa khas rumahan. Buka setiap hari dari jam 8 pagi sampai 8 malam.',
            'Kerajinan' => 'Memproduksi ' . strtolower($produk) . ' dengan bahan berkualitas dan dikerjakan oleh pengrajin terampil. Menerima pesanan partai besar dan kecil.',
            'Fashion' => 'Menghadirkan ' . strtolower($produk) . ' dengan kualitas terbaik dan harga terjangkau. Tersedia berbagai model dan ukuran untuk semua kalangan.',
            'Pertanian' => 'Menjual ' . strtolower($produk) . ' segar langsung dari kebun. Dijamin kualitas terbaik dan harga bersaing.',
            'Jasa' => 'Melayani ' . strtolower($produk) . ' dengan profesional dan harga terjangkau. Kepuasan pelanggan adalah prioritas kami.',
            default => 'UMKM yang bergerak di bidang ' . $kategori . ' menjual ' . strtolower($produk) . '. Melayani pesanan untuk berbagai kebutuhan.',
        };

        return [
            'desa_id' => ProfilDesa::inRandomOrder()->first()?->id ?? ProfilDesa::factory(),
            'penduduk_id' => Penduduk::inRandomOrder()->first()?->id ?? Penduduk::factory(),
            'nama_usaha' => $namaUsaha,
            'produk' => $produk,
            'kontak_whatsapp' => '628' . $this->faker->numberBetween(10000000, 99999999),
            'lokasi' => $lokasi,
            'deskripsi' => $deskripsi,
            'kategori' => $kategori,
            'is_verified' => $this->faker->boolean(70), // 70% terverifikasi
            'foto_usaha' => null,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    /**
     * UMKM yang terverifikasi
     */
    public function terverifikasi()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_verified' => true,
            ];
        });
    }

    /**
     * UMKM yang belum terverifikasi
     */
    public function belumTerverifikasi()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_verified' => false,
            ];
        });
    }
}
