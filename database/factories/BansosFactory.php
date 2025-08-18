<?php

namespace Database\Factories;

use App\Models\Bansos;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bansos>
 */
class BansosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bansos::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['Diajukan', 'Dalam Verifikasi', 'Diverifikasi', 'Disetujui', 'Ditolak', 'Sudah Diterima']),
            'prioritas' => $this->faker->randomElement(['Tinggi', 'Sedang', 'Rendah']),
            'sumber_pengajuan' => $this->faker->randomElement(['admin', 'warga']),
            'is_urgent' => $this->faker->boolean(20), // 20% kemungkinan urgent
            'tanggal_pengajuan' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'tanggal_penerimaan' => $this->faker->optional(0.2)->dateTimeBetween('-3 months', 'now'),
            'tenggat_pengambilan' => $this->faker->optional(0.3)->dateTimeBetween('now', '+2 weeks'),
            'diubah_oleh' => $this->faker->optional(0.7)->randomElement(User::all()->pluck('id')->toArray()) ?: User::factory(),
            'notifikasi_terkirim' => $this->faker->boolean(80),
            'keterangan' => $this->faker->optional(0.5)->text(200),
            'alasan_pengajuan' => $this->faker->paragraph(1),
            'id_desa' => $this->faker->randomElement([1, 2, 3]),
            'penduduk_id' => $this->faker->randomElement([1, 2, 3]),
            'jenis_bansos_id' => $this->faker->randomElement([1, 2, 3]),
        ];
    }

    /**
     * Bantuan yang diajukan
     */
    public function diajukan()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'Diajukan',
                'tanggal_pengajuan' => now()->subDays(rand(1, 7)),
                'tanggal_penerimaan' => null,
                'tenggat_pengambilan' => null,
                'diubah_oleh' => null,
                'prioritas' => $this->faker->randomElement(['Sedang', 'Rendah']),
                'is_urgent' => $this->faker->boolean(10),
                'alasan_pengajuan' => $this->faker->paragraph(2),
            ];
        });
    }

    /**
     * Bantuan yang dalam verifikasi
     */
    public function dalamVerifikasi()
    {
        return $this->state(function (array $attributes) {
            $tanggal_pengajuan = now()->subDays(rand(8, 15));

            return [
                'status' => 'Dalam Verifikasi',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'tanggal_penerimaan' => null,
                'tenggat_pengambilan' => null,
                'diubah_oleh' => User::factory(),
                'prioritas' => $this->faker->randomElement(['Tinggi', 'Sedang']),
                'is_urgent' => $this->faker->boolean(30),
                'keterangan' => 'Sedang dalam proses verifikasi dan pengecekan data',
                'alasan_pengajuan' => $this->faker->paragraph(2),
            ];
        });
    }

    /**
     * Bantuan yang diverifikasi
     */
    public function diverifikasi()
    {
        return $this->state(function (array $attributes) {
            $tanggal_pengajuan = now()->subDays(rand(10, 20));

            return [
                'status' => 'Diverifikasi',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'tanggal_penerimaan' => null,
                'tenggat_pengambilan' => null,
                'diubah_oleh' => User::factory(),
                'keterangan' => $this->faker->randomElement([
                    'Data telah diverifikasi dan valid',
                    'Verifikasi lapangan telah dilakukan, penerima memenuhi kriteria',
                    'Berdasarkan kunjungan ke lokasi, keluarga layak mendapat bantuan',
                ]),
                'prioritas' => $this->faker->randomElement(['Tinggi', 'Sedang', 'Rendah']),
                'alasan_pengajuan' => $this->faker->paragraph(2),
            ];
        });
    }

    /**
     * Bantuan yang disetujui
     */
    public function disetujui()
    {
        return $this->state(function (array $attributes) {
            $tanggal_pengajuan = now()->subDays(rand(20, 30));

            return [
                'status' => 'Disetujui',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'tanggal_penerimaan' => null,
                'tenggat_pengambilan' => now()->addDays(rand(3, 14)),
                'diubah_oleh' => User::factory(),
                'keterangan' => 'Data telah diverifikasi dan valid. Bantuan disetujui untuk diteruskan.',
                'prioritas' => $this->faker->randomElement(['Tinggi', 'Sedang']),
                'alasan_pengajuan' => $this->faker->paragraph(2),
            ];
        });
    }

    /**
     * Bantuan yang sudah diterima
     */
    public function diterima()
    {
        return $this->state(function (array $attributes) {
            $tanggal_pengajuan = now()->subDays(rand(40, 60));
            $tanggal_penerimaan = now()->subDays(rand(1, 10));

            return [
                'status' => 'Sudah Diterima',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'tanggal_penerimaan' => $tanggal_penerimaan,
                'tenggat_pengambilan' => now()->subDays(rand(5, 20)),
                'diubah_oleh' => User::factory(),
                'notifikasi_terkirim' => true,
                'bukti_penerimaan' => 'bukti/penerimaan-dummy.jpg',
                'keterangan' => 'Bantuan telah diterima oleh penerima pada tanggal ' . $tanggal_penerimaan->format('d M Y'),
                'prioritas' => $this->faker->randomElement(['Tinggi', 'Sedang', 'Rendah']),
                'alasan_pengajuan' => $this->faker->paragraph(1),
            ];
        });
    }

    /**
     * Bantuan yang ditolak
     */
    public function ditolak()
    {
        return $this->state(function (array $attributes) {
            $tanggal_pengajuan = now()->subDays(rand(10, 30));

            return [
                'status' => 'Ditolak',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'tanggal_penerimaan' => null,
                'tenggat_pengambilan' => null,
                'keterangan' => $this->faker->randomElement([
                    'Data tidak lengkap',
                    'Tidak memenuhi syarat',
                    'Sudah menerima bantuan lain',
                    'Data tidak sesuai dengan kondisi di lapangan',
                    'Duplikasi pengajuan',
                ]),
                'diubah_oleh' => User::factory(),
                'prioritas' => 'Rendah',
                'is_urgent' => false,
                'alasan_pengajuan' => $this->faker->paragraph(1),
            ];
        });
    }

    /**
     * Bantuan dari pengajuan warga
     */
    public function dariWarga()
    {
        return $this->state(function (array $attributes) {
            $alasan_pengajuan = [
                'Keluarga kesulitan memenuhi kebutuhan sehari-hari',
                'Membutuhkan bantuan biaya pendidikan anak',
                'Membutuhkan bantuan biaya berobat untuk anggota keluarga',
                'Kehilangan pekerjaan dan tidak ada penghasilan',
                'Biaya sekolah anak tidak tercukupi'
            ];

            return [
                'status' => 'Diajukan', // Status selalu 'Diajukan' untuk pengajuan warga
                'sumber_pengajuan' => 'warga',
                'tanggal_pengajuan' => now(),
                'prioritas' => $this->faker->randomElement(['Sedang', 'Rendah']),
                'keterangan' => $this->faker->randomElement([
                    'Pengajuan dari warga untuk membantu kebutuhan ekonomi keluarga',
                    'Mengajukan untuk bantuan pendidikan anak',
                    'Permohonan bantuan untuk biaya pengobatan',
                    'Mengajukan bantuan untuk renovasi rumah',
                    'Permohonan bantuan untuk modal usaha kecil',
                ]),
                'notifikasi_terkirim' => true,
                'is_urgent' => false,
                'alasan_pengajuan' => $this->faker->randomElement($alasan_pengajuan),
            ];
        });
    }

    /**
     * Bantuan dari admin
     */
    public function dariAdmin()
    {
        return $this->state(function (array $attributes) {
            $alasan_pengajuan = [
                'Berdasarkan hasil survei, keluarga termasuk kategori miskin ekstrem',
                'Keluarga masuk dalam database DTKS pemerintah pusat',
                'Hasil validasi lapangan memenuhi kriteria penerima bantuan',
                'Rekomendasi dari RT/RW setempat tentang kondisi keluarga',
                'Termasuk dalam daftar prioritas bantuan dari kecamatan'
            ];

            return [
                'sumber_pengajuan' => 'admin',
                'keterangan' => $this->faker->randomElement([
                    'Pendataan dari petugas desa berdasarkan survei',
                    'Pengusulan petugas sosial berdasarkan kondisi lapangan',
                    'Penunjukan langsung dari program pemerintah',
                    'Rekomendasi dari RT/RW setempat',
                    'Usulan dari petugas kecamatan',
                ]),
                'alasan_pengajuan' => $this->faker->randomElement($alasan_pengajuan),
            ];
        });
    }

    /**
     * Pengajuan urgent/mendesak
     */
    public function urgent()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_urgent' => true,
                'prioritas' => 'Tinggi',
                'keterangan' => $this->faker->randomElement([
                    'MENDESAK: Keluarga dalam kondisi darurat ekonomi',
                    'MENDESAK: Kepala keluarga sakit parah',
                    'MENDESAK: Rumah rusak akibat bencana',
                    'MENDESAK: Lansia tanpa penghasilan dan tanpa keluarga',
                    'MENDESAK: Keluarga dengan balita malnutrisi',
                ]),
            ];
        });
    }

    /**
     * Pengajuan dengan dokumen pendukung
     */
    public function denganDokumen()
    {
        return $this->state(function (array $attributes) {
            return [
                'foto_rumah' => 'bansos/rumah/foto-rumah-dummy-' . rand(1, 5) . '.jpg',
                'dokumen_pendukung' => 'bansos/dokumen/dokumen-dummy-' . rand(1, 3) . '.pdf',
            ];
        });
    }
}