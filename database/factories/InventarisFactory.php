<?php

namespace Database\Factories;

use App\Models\Inventaris;
use App\Models\ProfilDesa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventaris>
 */
class InventarisFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Inventaris::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = $this->faker->randomElement(array_keys(Inventaris::getKategoriOptions()));

        // Buat array nama barang berdasarkan kategori
        $namaBarang = [
            'Elektronik' => [
                'TV LED 32 inch', 'Amplifier TOA', 'Sound System', 'Microphone Wireless',
                'Kipas Angin Dinding', 'Kipas Angin Duduk', 'Speaker Aktif', 'Mesin Laminating',
                'Alat Perekam', 'Mesin Penghitung Uang', 'Printer Thermal', 'Mesin Absensi',
            ],
            'Furnitur' => [
                'Meja Kerja', 'Kursi Lipat', 'Lemari Arsip', 'Bangku Panjang',
                'Meja Rapat', 'Kursi Tamu', 'Meja Tamu', 'Papan Pengumuman',
                'Rak Buku', 'Loker', 'Meja Pendaftaran', 'Etalase Kaca',
            ],
            'Kendaraan' => [
                'Motor Dinas', 'Mobil Operasional', 'Sepeda Petugas', 'Ambulance Desa',
                'Becak Motor', 'Perahu Karet', 'Motor Roda Tiga', 'Mobil Jenazah',
            ],
            'ATK' => [
                'Kertas HVS A4', 'Stempel Desa', 'Pulpen', 'Tinta Printer',
                'Stepler', 'Map Arsip', 'Buku Agenda', 'Binder Clips',
                'Spidol Whiteboard', 'Papan Tulis', 'Kalender Dinding', 'Bantalan Stempel',
            ],
            'Komputer' => [
                'Laptop ASUS', 'PC Desktop', 'Printer Epson', 'Scanner Dokumen',
                'UPS', 'Mouse Wireless', 'Keyboard USB', 'Monitor LED',
                'Router WiFi', 'Hard Disk External', 'Flash Disk', 'RAM Komputer',
            ],
            'Peralatan' => [
                'Gergaji Mesin', 'Mesin Potong Rumput', 'Genset', 'Tangga Aluminium',
                'Pompa Air', 'Gerobak Sampah', 'Peralatan Kebersihan', 'Sabit',
                'Cangkul', 'Sekop', 'Palu', 'Tang',
            ],
            'Lainnya' => [
                'Tenda Pesta', 'Tikar', 'Meja Panjang', 'Kursi Plastik',
                'Terpal Besar', 'Payung Besar', 'Alat Pemadam Api', 'Kotak P3K',
                'Lampu Darurat', 'Kostum Tari', 'Alat Musik Tradisional', 'Piala Pajangan',
            ],
        ];

        // Pilih barang yang sesuai dengan kategori terpilih
        $nama = $this->faker->randomElement($namaBarang[$kategori] ?? ['Barang Umum']);

        // Generate nominal harga sesuai kategori
        $nominalHarga = match($kategori) {
            'Elektronik' => $this->faker->numberBetween(500000, 5000000),
            'Furnitur' => $this->faker->numberBetween(200000, 2000000),
            'Kendaraan' => $this->faker->numberBetween(5000000, 50000000),
            'ATK' => $this->faker->numberBetween(10000, 500000),
            'Komputer' => $this->faker->numberBetween(1000000, 10000000),
            'Peralatan' => $this->faker->numberBetween(100000, 3000000),
            default => $this->faker->numberBetween(50000, 1000000),
        };

        // Perbaikan untuk pemilihan kondisi dengan probabilitas
        $kondisiOptions = [
            'Baik' => 70,
            'Rusak Ringan' => 20,
            'Rusak Berat' => 8,
            'Hilang' => 2,
        ];

        // Gunakan weighted random untuk memilih kondisi
        $randomNumber = $this->faker->numberBetween(1, 100);
        $sum = 0;
        $kondisi = 'Baik'; // Default

        foreach ($kondisiOptions as $option => $weight) {
            $sum += $weight;
            if ($randomNumber <= $sum) {
                $kondisi = $option;
                break;
            }
        }

        // Status disesuaikan dengan kondisi
        $status = match($kondisi) {
            'Baik' => $this->faker->randomElement(['Tersedia', 'Dipinjam']),
            'Rusak Ringan' => $this->faker->randomElement(['Dalam Perbaikan', 'Tersedia']),
            'Rusak Berat' => $this->faker->randomElement(['Dalam Perbaikan', 'Tidak Aktif']),
            'Hilang' => 'Tidak Aktif',
            default => 'Tersedia',
        };

        // Lokasi berdasarkan penggunaan umum
        $lokasi = $this->faker->randomElement([
            'Kantor Desa Lantai 1', 'Kantor Desa Lantai 2', 'Aula Desa', 'Gudang Desa',
            'Posyandu', 'Balai Pertemuan', 'Perpustakaan Desa', 'Posko Keamanan'
        ]);

        return [
            'id_desa' => ProfilDesa::factory(),
            'created_by' => User::factory(),
            'nama_barang' => $nama,
            'kategori' => $kategori,
            'jumlah' => $this->faker->numberBetween(1, 20),
            'kondisi' => $kondisi,
            'status' => $status,
            'tanggal_perolehan' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'nominal_harga' => $nominalHarga,
            'sumber_dana' => $this->faker->randomElement(array_keys(Inventaris::getSumberDanaOptions())),
            'lokasi' => $lokasi,
            'keterangan' => $this->faker->optional(0.7)->sentence(),
        ];
    }

    /**
     * State untuk barang elektronik
     */
    public function elektronik()
    {
        return $this->state(function () {
            return [
                'kategori' => 'Elektronik',
            ];
        });
    }

    /**
     * State untuk barang furnitur
     */
    public function furnitur()
    {
        return $this->state(function () {
            return [
                'kategori' => 'Furnitur',
            ];
        });
    }

    /**
     * State untuk barang dengan kondisi baik
     */
    public function kondisiBaik()
    {
        return $this->state(function () {
            return [
                'kondisi' => 'Baik',
                'status' => 'Tersedia',
            ];
        });
    }

    /**
     * State untuk barang dengan kondisi rusak
     */
    public function rusak()
    {
        return $this->state(function () {
            return [
                'kondisi' => $this->faker->randomElement(['Rusak Ringan', 'Rusak Berat']),
                'status' => 'Dalam Perbaikan',
            ];
        });
    }
}
