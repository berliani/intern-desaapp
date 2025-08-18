<?php

namespace Database\Factories;

use App\Models\LayananDesa;
use App\Models\ProfilDesa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LayananDesa>
 */
class LayananDesaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LayananDesa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = $this->faker->randomElement([
            'Surat', 'Kesehatan', 'Pendidikan', 'Sosial', 'Infrastruktur'
        ]);

        $layananNama = [
            'Surat' => [
                'Pembuatan Kartu Keluarga (KK)',
                'Pembuatan KTP Elektronik',
                'Surat Keterangan Domisili',
                'Surat Keterangan Tidak Mampu',
                'Surat Keterangan Usaha',
                'Surat Izin Keramaian',
                'Pengantar Nikah',
                'Surat Keterangan Kelahiran',
                'Surat Keterangan Kematian',
                'Surat Keterangan Pindah',
            ],
            'Kesehatan' => [
                'Posyandu Balita',
                'Posyandu Lansia',
                'Penyuluhan Kesehatan',
                'Pemeriksaan Kesehatan Gratis',
                'Program KB',
                'Sosialisasi BPJS',
                'Imunisasi Anak',
                'Fogging Nyamuk DB',
            ],
            'Pendidikan' => [
                'Bantuan Pendidikan',
                'Beasiswa Siswa Berprestasi',
                'Taman Bacaan Desa',
                'Pendidikan Anak Usia Dini (PAUD)',
                'Kursus Komputer',
                'Bimbingan Belajar',
            ],
            'Sosial' => [
                'Bantuan Sosial Masyarakat',
                'Program PKH',
                'Bantuan Sembako',
                'Santunan Yatim Piatu',
                'Bakti Sosial',
                'Penyaluran Zakat',
            ],
            'Infrastruktur' => [
                'Pembangunan Jalan Desa',
                'Perbaikan Saluran Air',
                'Pembangunan Jembatan',
                'Penerangan Jalan',
                'Renovasi Balai Desa',
                'Pemeliharaan Sumur Umum',
            ],
        ];

        $nama = $this->faker->randomElement($layananNama[$kategori]);

        // Biaya layanan berdasarkan kategori
        $biayaRange = [
            'Surat' => [0, 50000],
            'Kesehatan' => [0, 100000],
            'Pendidikan' => [0, 500000],
            'Sosial' => [0, 0], // Layanan sosial umumnya gratis
            'Infrastruktur' => [100000, 5000000],
        ];

        $range = $biayaRange[$kategori] ?? [0, 100000];
        $biaya = $this->faker->numberBetween($range[0], $range[1]);

        // Generate persyaratan berdasarkan kategori
        $persyaratan = $this->generatePersyaratan($kategori);

        // Generate prosedur berdasarkan kategori
        $prosedur = $this->generateProsedur($kategori);

        // Generate lokasi layanan berdasarkan kategori
        $lokasiLayanan = [
            'Surat' => ['Kantor Desa', 'Balai Desa', 'Kecamatan'],
            'Kesehatan' => ['Puskesmas Desa', 'Posyandu', 'Balai Kesehatan Desa'],
            'Pendidikan' => ['Sekolah Desa', 'Perpustakaan Desa', 'Aula Desa'],
            'Sosial' => ['Balai Desa', 'Rumah Warga', 'Kantor Desa'],
            'Infrastruktur' => ['Kantor Teknis Desa', 'Lokasi Proyek', 'Balai Desa'],
        ];

        // Generate jadwal pelayanan
        $jadwalOptions = [
            'Senin-Jumat: 08.00-15.00',
            'Senin-Sabtu: 08.00-12.00',
            'Senin, Rabu, Jumat: 09.00-14.00',
            'Setiap hari: 09.00-11.00',
            'Selasa dan Kamis: 09.00-15.00',
        ];

        // Generate kontak layanan
        $kontakNama = ['Pak Budi', 'Bu Siti', 'Pak Hendra', 'Kantor Desa', 'Admin Layanan'];

        return [
            'id_desa' => ProfilDesa::factory(),
            'created_by' => User::factory(),
            'kategori' => $kategori,
            'nama_layanan' => $nama,
            'deskripsi' => '<p>' . implode('</p><p>', $this->faker->paragraphs(3)) . '</p>',
            'biaya' => $biaya,
            'lokasi_layanan' => $this->faker->randomElement($lokasiLayanan[$kategori] ?? ['Kantor Desa']),
            'jadwal_pelayanan' => $this->faker->randomElement($jadwalOptions),
            'kontak_layanan' => $this->faker->phoneNumber() . ' (' . $this->faker->randomElement($kontakNama) . ')',
            'persyaratan' => $persyaratan,
            'prosedur' => $prosedur,
        ];
    }

    /**
     * Generate persyaratan berdasarkan kategori layanan
     */
    protected function generatePersyaratan(string $kategori): array
    {
        $persyaratanUmum = [
            ['dokumen' => 'Kartu Tanda Penduduk (KTP)', 'keterangan' => 'Asli dan fotokopi'],
            ['dokumen' => 'Kartu Keluarga (KK)', 'keterangan' => 'Asli dan fotokopi'],
        ];

        $persyaratanKategori = [
            'Surat' => [
                ['dokumen' => 'Surat Pengantar RT/RW', 'keterangan' => 'Asli'],
                ['dokumen' => 'Pas Foto 3×4', 'keterangan' => '2 lembar (latar belakang merah)'],
                ['dokumen' => 'Materai 10.000', 'keterangan' => 'Untuk surat pernyataan'],
            ],
            'Kesehatan' => [
                ['dokumen' => 'Kartu BPJS (jika ada)', 'keterangan' => 'Asli dan fotokopi'],
                ['dokumen' => 'Surat Rujukan (jika ada)', 'keterangan' => 'Dari puskesmas atau dokter'],
                ['dokumen' => 'Kartu Vaksin (jika diperlukan)', 'keterangan' => 'Asli dan fotokopi'],
            ],
            'Pendidikan' => [
                ['dokumen' => 'Rapor Terakhir', 'keterangan' => 'Asli dan fotokopi'],
                ['dokumen' => 'Surat Keterangan Aktif Sekolah', 'keterangan' => 'Dari sekolah terkait'],
                ['dokumen' => 'Surat Keterangan Tidak Mampu (jika diperlukan)', 'keterangan' => 'Dari desa/kelurahan'],
            ],
            'Sosial' => [
                ['dokumen' => 'Surat Keterangan Tidak Mampu', 'keterangan' => 'Dari desa/kelurahan'],
                ['dokumen' => 'Dokumen Pendukung (sesuai jenis bantuan)', 'keterangan' => 'Asli dan fotokopi'],
            ],
            'Infrastruktur' => [
                ['dokumen' => 'Proposal Kegiatan', 'keterangan' => 'Mencakup rencana dan anggaran'],
                ['dokumen' => 'Surat Pernyataan Warga', 'keterangan' => 'Ditandatangani perwakilan warga'],
                ['dokumen' => 'Dokumentasi Lokasi', 'keterangan' => 'Foto lokasi yang akan dibangun/diperbaiki'],
            ],
        ];

        // Ambil 2 persyaratan umum + persyaratan khusus kategori
        $result = $persyaratanUmum;

        if (isset($persyaratanKategori[$kategori])) {
            // Acak 2-3 persyaratan dari kategori spesifik
            $specific = $this->faker->randomElements(
                $persyaratanKategori[$kategori],
                $this->faker->numberBetween(2, count($persyaratanKategori[$kategori]))
            );

            $result = array_merge($result, $specific);
        }

        return $result;
    }

    /**
     * Generate prosedur berdasarkan kategori layanan
     */
    protected function generateProsedur(string $kategori): array
    {
        $prosedurUmum = [
            ['langkah' => 'Pendaftaran di Kantor Desa', 'keterangan' => 'Mengisi formulir dan menyerahkan berkas'],
            ['langkah' => 'Verifikasi Berkas', 'keterangan' => 'Petugas akan memeriksa kelengkapan berkas'],
            ['langkah' => 'Pembayaran Biaya Administrasi (jika ada)', 'keterangan' => 'Sesuai ketentuan yang berlaku'],
        ];

        $prosedurKategori = [
            'Surat' => [
                ['langkah' => 'Penerbitan Surat', 'keterangan' => 'Diproses oleh petugas desa'],
                ['langkah' => 'Penandatanganan oleh Kepala Desa', 'keterangan' => 'Setelah verifikasi dan pembayaran'],
                ['langkah' => 'Pengambilan Surat', 'keterangan' => 'Sesuai jadwal yang ditetapkan'],
            ],
            'Kesehatan' => [
                ['langkah' => 'Pemeriksaan Awal', 'keterangan' => 'Oleh petugas kesehatan desa'],
                ['langkah' => 'Pelaksanaan Layanan', 'keterangan' => 'Sesuai jadwal atau perjanjian'],
                ['langkah' => 'Evaluasi dan Tindak Lanjut', 'keterangan' => 'Jika diperlukan'],
            ],
            'Pendidikan' => [
                ['langkah' => 'Seleksi Penerima', 'keterangan' => 'Oleh tim seleksi desa'],
                ['langkah' => 'Pengumuman Hasil', 'keterangan' => 'Di papan pengumuman desa dan website'],
                ['langkah' => 'Pelaksanaan Program', 'keterangan' => 'Sesuai jadwal yang ditentukan'],
            ],
            'Sosial' => [
                ['langkah' => 'Survei Kebutuhan', 'keterangan' => 'Petugas akan melakukan kunjungan'],
                ['langkah' => 'Validasi Data', 'keterangan' => 'Pengecekan kebenaran informasi'],
                ['langkah' => 'Penyaluran Bantuan', 'keterangan' => 'Sesuai mekanisme yang berlaku'],
            ],
            'Infrastruktur' => [
                ['langkah' => 'Survei Lokasi', 'keterangan' => 'Oleh tim teknis desa'],
                ['langkah' => 'Musyawarah Perencanaan', 'keterangan' => 'Bersama warga dan perangkat desa'],
                ['langkah' => 'Pelaksanaan Pembangunan', 'keterangan' => 'Sesuai jadwal yang disepakati'],
                ['langkah' => 'Monitoring dan Evaluasi', 'keterangan' => 'Oleh tim pengawas desa'],
            ],
        ];

        // Ambil semua prosedur umum + prosedur khusus kategori
        $result = $prosedurUmum;

        if (isset($prosedurKategori[$kategori])) {
            // Tambahkan semua prosedur spesifik kategori
            $result = array_merge($result, $prosedurKategori[$kategori]);
        }

        // Tambahkan langkah terakhir
        $result[] = ['langkah' => 'Selesai', 'keterangan' => 'Layanan telah diberikan'];

        return $result;
    }

    /**
     * Layanan dengan biaya nol (gratis)
     */
    public function gratis(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'biaya' => 0,
            ];
        });
    }

    /**
     * Layanan dengan persyaratan minimal
     */
    public function persyaratanMinimal(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'persyaratan' => [
                    ['dokumen' => 'Kartu Tanda Penduduk (KTP)', 'keterangan' => 'Asli dan fotokopi'],
                    ['dokumen' => 'Surat Pengantar RT/RW', 'keterangan' => 'Asli'],
                ],
            ];
        });
    }
}