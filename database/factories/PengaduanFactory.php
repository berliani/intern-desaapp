<?php

namespace Database\Factories;

use App\Models\Pengaduan;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengaduan>
 */
class PengaduanFactory extends Factory
{
    protected $model = Pengaduan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = $this->faker->randomElement(array_keys(Pengaduan::getKategoriOptions()));
        $status = $this->faker->randomElement(array_keys(Pengaduan::getStatusOptions()));
        $prioritas = $this->faker->randomElement(array_keys(Pengaduan::getPrioritasOptions()));

        $judulPer = [
            'Keamanan' => [
                'Pencurian di rumah warga', 'Premanisme di pasar desa', 'Peredaran narkoba',
                'Balap liar di jalan desa', 'Pemalakan terhadap siswa sekolah',
                'Keributan di malam hari', 'Penjambretan di pasar', 'Kekerasan rumah tangga'
            ],
            'Infrastruktur' => [
                'Jalan berlubang di RT 02', 'Jembatan rusak di dusun timur', 'Lampu jalan padam',
                'Saluran air tersumbat', 'Longsor di tebing desa', 'Banjir di RT 04',
                'Bangunan sekolah rusak', 'Perbaikan drainase mendesak'
            ],
            'Sosial' => [
                'Konflik antar warga', 'Kesulitan ekonomi keluarga janda', 'Anak putus sekolah',
                'Keluarga sakit tidak mampu berobat', 'Bantuan untuk difabel',
                'Pembagian bantuan tidak merata', 'Konflik perebutan lahan'
            ],
            'Lingkungan' => [
                'Tumpukan sampah tidak diangkut', 'Pencemaran air sungai', 'Penebangan liar',
                'Lahan pertanian tercemar', 'Polusi udara dari pabrik', 'Pembuangan limbah sembarangan',
                'Kebakaran hutan'
            ],
            'Pelayanan Publik' => [
                'Pelayanan KTP lambat', 'Prosedur perizinan rumit', 'Petugas desa tidak ramah',
                'Informasi bantuan tidak transparan', 'Jam buka kantor desa tidak konsisten',
                'Pungutan liar untuk layanan'
            ],
            'Kesehatan' => [
                'Wabah diare di RT 03', 'Kekurangan tenaga kesehatan', 'Obat di puskesmas habis',
                'Ambulans desa rusak', 'Layanan posyandu tidak berjalan', 'Penyakit kulit mewabah',
                'Sanitasi buruk menyebabkan penyakit'
            ],
            'Lainnya' => [
                'Gangguan listrik terus menerus', 'Sinyal telepon buruk', 'Koneksi internet lemah',
                'Kebutuhan guru tambahan', 'Harga sembako melambung', 'Pertanian gagal panen',
                'Layanan antar surat tidak berjalan'
            ]
        ];

        $judul = $this->faker->randomElement($judulPer[$kategori] ?? ['Masalah di desa']);

        $ditangani = null;
        $tanggapan = null;
        $tanggal_tanggapan = null;

        if ($status !== 'Belum Ditangani') {
            $ditangani = User::inRandomOrder()->first()?->id;

            $tanggapan = $this->faker->paragraph();
            $tanggal_tanggapan = now()->subDays(rand(1, 10));
        }

        return [
            'desa_id' => ProfilDesa::inRandomOrder()->first()?->id ?? ProfilDesa::factory(),
            'penduduk_id' => Penduduk::inRandomOrder()->first()?->id ?? Penduduk::factory(),
            'judul' => $judul,
            'kategori' => $kategori,
            'prioritas' => $prioritas,
            'deskripsi' => $this->faker->paragraphs(rand(1, 3), true),
            'status' => $status,
            'is_public' => $this->faker->boolean(80),
            'tanggapan' => $tanggapan,
            'ditangani_oleh' => $ditangani,
            'tanggal_tanggapan' => $tanggal_tanggapan,
            'created_at' => now()->subDays(rand(1, 30)),
        ];
    }

    /**
     * Pengaduan yang belum ditangani
     */
    public function belumDitangani()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Belum Ditangani',
                'tanggapan' => null,
                'ditangani_oleh' => null,
                'tanggal_tanggapan' => null,
            ];
        });
    }

    /**
     * Pengaduan yang sedang diproses
     */
    public function sedangDiproses()
    {
        return $this->state(function (array $attributes) {
            $admin = User::inRandomOrder()->first()?->id;

            return [
                'status' => 'Sedang Diproses',
                'tanggapan' => $this->faker->paragraph(),
                'ditangani_oleh' => $admin,
                'tanggal_tanggapan' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Pengaduan yang sudah selesai
     */
    public function selesai()
    {
        return $this->state(function (array $attributes) {
            $admin = User::inRandomOrder()->first()?->id;

            return [
                'status' => 'Selesai',
                'tanggapan' => 'Pengaduan telah diselesaikan. ' . $this->faker->paragraph(),
                'ditangani_oleh' => $admin,
                'tanggal_tanggapan' => now()->subDays(rand(1, 3)),
            ];
        });
    }

    /**
     * Pengaduan yang ditolak
     */
    public function ditolak()
    {
        return $this->state(function (array $attributes) {
            $admin = User::inRandomOrder()->first()?->id;

            return [
                'status' => 'Ditolak',
                'tanggapan' => 'Pengaduan ini ditolak karena ' . $this->faker->sentence(),
                'ditangani_oleh' => $admin,
                'tanggal_tanggapan' => now()->subDays(rand(1, 5)),
            ];
        });
    }

    /**
     * Pengaduan dengan prioritas tinggi
     */
    public function prioritasTinggi()
    {
        return $this->state(function (array $attributes) {
            return [
                'prioritas' => 'Tinggi',
            ];
        });
    }

    /**
     * Pengaduan dengan foto
     */
    public function denganFoto()
    {
        return $this->state(function (array $attributes) {
            // Pilih satu contoh nama file gambar
            $contohGambar = [
                'uploads/pengaduan/jalan-rusak.jpg',
                'uploads/pengaduan/sampah.jpg',
                'uploads/pengaduan/banjir.jpg',
                'uploads/pengaduan/lampu-jalan.jpg',
                'uploads/pengaduan/infrastruktur.jpg',
            ];

            return [
                'foto' => $this->faker->randomElement($contohGambar),
            ];
        });
    }
}
