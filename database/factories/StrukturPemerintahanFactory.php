<?php

namespace Database\Factories;

use App\Models\ProfilDesa;
use App\Models\StrukturPemerintahan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StrukturPemerintahan>
 */
class StrukturPemerintahanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = StrukturPemerintahan::class;

    public function definition(): array
    {
        // Daftar nama kepala desa umum
        $namaKepala = $this->faker->name();
        
        // Periode jabatan acak (5 tahun)
        $tahunMulai = $this->faker->numberBetween(2015, 2022);
        $tahunAkhir = $tahunMulai + 6;
        $periodeJabatan = "{$tahunMulai}-{$tahunAkhir}";

        return [
            'profil_desa_id' => ProfilDesa::factory(),
            'created_by' => User::factory(),
            'nama_kepala_desa' => $namaKepala,
            'periode_jabatan' => $periodeJabatan,
            'foto_kepala_desa' => 'uploads/desa/kepala-desa/default-kades.jpg',
            'sambutan_kepala_desa' => $this->faker->paragraphs(3, true),
            // Program Kerja dalam format HTML (rich editor)
            'program_kerja' => '<p>Fokus pada bidang strategis untuk pengembangan desa:</p>
                <ul>
                    <li>Pembangunan infrastruktur desa yang merata dan berkualitas</li>
                    <li>Digitalisasi administrasi desa untuk pelayanan yang lebih efisien</li>
                    <li>Peningkatan ekonomi masyarakat melalui BUMDES dan UMKM lokal</li>
                    <li>Pengembangan sumber daya manusia melalui pelatihan keterampilan</li>
                </ul>',
            // Prioritas Program dalam format HTML (rich editor)
            'prioritas_program' => '<ol>
                    <li><strong>Pembangunan jalan desa</strong> - Perbaikan akses jalan di 3 dusun</li>
                    <li><strong>Pengembangan BUMDes</strong> - Fokus pada pengelolaan usaha desa bidang pertanian</li>
                    <li><strong>Digitalisasi administrasi desa</strong> - Sistem informasi desa dan layanan online</li>
                    <li><strong>Pelatihan keterampilan pemuda</strong> - Program peningkatan kapasitas generasi muda</li>
                    <li><strong>Renovasi fasilitas umum</strong> - Perbaikan balai desa dan fasilitas publik</li>
                </ol>',
            'bagan_struktur' => 'uploads/desa/struktur/default-struktur.jpg',
        ];
    }

    /**
     * State untuk struktur pemerintahan dengan sambutan kepala desa yang lebih lengkap
     */
    public function denganSambutanLengkap(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'sambutan_kepala_desa' => "<p>Assalamu'alaikum Wr. Wb.</p>
                <p>Puji syukur kita panjatkan kehadirat Allah SWT, karena atas berkat dan rahmat-Nya kita masih diberikan kesehatan dan kesempatan untuk menjalankan tugas sebagai pelayan masyarakat di desa ini.</p>
                <p>Sebagai Kepala Desa, saya bersama dengan perangkat desa dan BPD berkomitmen untuk memajukan desa kita dengan program-program yang inovatif dan tepat sasaran. Kami akan fokus pada pembangunan infrastruktur, peningkatan ekonomi warga, dan pelayanan publik yang prima.</p>
                <p>Website desa ini merupakan salah satu upaya kami untuk meningkatkan transparansi dan kemudahan akses informasi bagi seluruh masyarakat. Melalui website ini, kami berharap masyarakat dapat lebih mudah mendapatkan informasi dan layanan administrasi desa.</p>
                <p>Mari bersama-sama kita bangun desa yang lebih baik, maju, dan sejahtera. Dengan kerjasama dan gotong royong, tidak ada yang tidak mungkin untuk kita wujudkan.</p>
                <p>Wassalamu'alaikum Wr. Wb.</p>",
                
                // Program Kerja yang lebih lengkap
                'program_kerja' => "<p>Sebagai Kepala Desa terpilih, saya memiliki beberapa program kerja utama yang akan menjadi fokus pembangunan desa selama masa jabatan saya:</p>
                <h3>Bidang Infrastruktur</h3>
                <ul>
                    <li>Pembangunan dan perbaikan jalan desa di seluruh wilayah</li>
                    <li>Pengembangan sistem irigasi pertanian</li>
                    <li>Perbaikan fasilitas umum seperti balai desa, posyandu, dan tempat ibadah</li>
                </ul>
                
                <h3>Bidang Ekonomi</h3>
                <ul>
                    <li>Penguatan BUMDes sebagai sumber pendapatan desa</li>
                    <li>Pelatihan kewirausahaan bagi masyarakat</li>
                    <li>Pengembangan sektor pariwisata desa berbasis kearifan lokal</li>
                </ul>
                
                <h3>Bidang Administrasi dan Pelayanan</h3>
                <ul>
                    <li>Digitalisasi sistem administrasi desa</li>
                    <li>Peningkatan kualitas dan kecepatan pelayanan publik</li>
                    <li>Transparansi pengelolaan anggaran desa</li>
                </ul>",
                
                // Prioritas Program yang lebih lengkap
                'prioritas_program' => "<ol>
                    <li><strong>Infrastruktur Jalan dan Irigasi (2023-2024)</strong><br>
                    Perbaikan jalan desa sepanjang 5 km dan sistem irigasi di 3 dusun untuk meningkatkan produktivitas pertanian.</li>
                    
                    <li><strong>Pengembangan BUMDes (2023-2025)</strong><br>
                    Penguatan kapasitas BUMDes dalam pengelolaan hasil pertanian dan perikanan, termasuk pengadaan alat pengolahan dan pemasaran digital.</li>
                    
                    <li><strong>Pemberdayaan UMKM (2023-2026)</strong><br>
                    Pelatihan keterampilan, bantuan permodalan, dan pendampingan pemasaran produk UMKM desa.</li>
                    
                    <li><strong>Sistem Informasi Desa (2024)</strong><br>
                    Pengembangan sistem informasi dan pelayanan digital untuk meningkatkan efisiensi administrasi dan transparansi.</li>
                    
                    <li><strong>Pengembangan SDM (2024-2026)</strong><br>
                    Program pelatihan untuk pemuda desa dan peningkatan kapasitas perangkat desa.</li>
                </ol>"
            ];
        });
    }
} 