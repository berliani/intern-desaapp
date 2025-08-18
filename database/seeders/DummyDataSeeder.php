<?php

namespace Database\Seeders;

use App\Models\BatasWilayahPotensi;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use App\Models\User;
use App\Models\VerifikasiPenduduk;
use App\Models\Inventaris;
use App\Models\JenisBansos;
use App\Models\Bansos;
use App\Models\BansosHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    protected $faker;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inisialisasi Faker
        $this->faker = Faker::create('id_ID');

        // 1. Menggunakan admin yang sudah ada
        $admin = User::where('email', 'admin@desaku.com')->first();

        if (!$admin) {
            // Gunakan user admin yang pertama ditemukan
            $admin = User::role('admin')->first() ?? User::role('super_admin')->first();

            if (!$admin) {
                // Jika tidak ada admin, gunakan user apa saja
                $admin = User::first();
            }
        }

        // 2. Create desa profile
        $desa = ProfilDesa::create([
            'created_by' => $admin->id,
            'nama_desa' => 'Desa Sariharjo',
            'kecamatan' => 'Kecamatan Ngaglik',
            'kabupaten' => 'Kabupaten Ngaglik',
            'provinsi' => 'DIY',
            'kode_pos' => '55581',
            'logo' => 'uploads/desa/sariharjo-logo.png',

            // Data kontak dan profil
            'alamat' => 'Jl. Raya Sariharjo No. 123, Kecamatan Ngaglik, Kabupaten Ngaglik',
            'telepon' => '0274-9302400',
            'email' => 'desa-sariharjo@desa.local',
            'website' => 'https://desa.local',
            'visi' => 'Mewujudkan Desa Sariharjo yang Maju, Mandiri, dan Sejahtera dengan Pembangunan Berkelanjutan dan Berbasis Kearifan Lokal',
            'misi' => "1. Meningkatkan pembangunan infrastruktur yang mendukung perekonomian desa\n2. Mengoptimalkan pelayanan publik yang transparan dan akuntabel\n3. Meningkatkan produktivitas pertanian dan perikanan\n4. Mengembangkan potensi pariwisata dan UMKM\n5. Melestarikan budaya dan kearifan lokal",
            'sejarah' => "Desa Sariharjo berdiri sejak tahun 1825, berawal dari pemukiman nelayan dan petani di pesisir utara Jawa. Nama desa berasal dari sebuah Kedung (cekungan air) yang airnya berwarna wungu (ungu) akibat tanah liat yang khas di daerah ini.\n\nPada masa kolonial, desa ini merupakan penghasil garam dan ikan yang penting. Setelah kemerdekaan, Desa Sariharjo berkembang menjadi salah satu desa produktif di Ngaglik dengan pertanian dan tambak sebagai sektor andalan.",
        ]);

        // 2b. Create batas wilayah dan potensi untuk desa
        BatasWilayahPotensi::create([
            'profil_desa_id' => $desa->id,
            'created_by' => $admin->id,

            // Luas dan batas wilayah
            'luas_wilayah' => 4567800, // dalam meter persegi (456.78 hektar)
            'batas_utara' => 'Desa Pakem',
            'batas_timur' => 'Desa Mlati',
            'batas_selatan' => 'Desa Ngemplak',
            'batas_barat' => 'Desa Mlati',
            'keterangan_batas' => 'Batas-batas wilayah sesuai dengan peta desa tahun 2025',

            // Potensi desa dalam format JSON fleksibel
            'potensi_desa' => [
                [
                    'nama' => 'Sumber mata air',
                    'kategori' => 'sda',
                    'lokasi' => 'Dusun Kramat',
                    'deskripsi' => 'Sumber mata air yang digunakan untuk kebutuhan air bersih desa'
                ],
                [
                    'nama' => 'Lahan pertanian subur',
                    'kategori' => 'sda',
                    'lokasi' => 'Seluruh wilayah desa',
                    'deskripsi' => 'Lahan dengan tingkat kesuburan tinggi untuk pertanian'
                ],
                [
                    'nama' => 'Tambak ikan',
                    'kategori' => 'peternakan',
                    'lokasi' => 'Dusun Pesisir',
                    'satuan' => 'Ha',
                    'jumlah' => 50,
                    'deskripsi' => 'Area tambak untuk budidaya ikan dan udang'
                ],
                [
                    'nama' => 'Tanaman padi',
                    'kategori' => 'pertanian',
                    'lokasi' => 'Area persawahan',
                    'satuan' => 'Ha',
                    'jumlah' => 250,
                    'deskripsi' => 'Tanaman padi varietas unggul'
                ],
                [
                    'nama' => 'Palawija',
                    'kategori' => 'pertanian',
                    'lokasi' => 'Area tegalan',
                    'satuan' => 'Ha',
                    'jumlah' => 120,
                    'deskripsi' => 'Jagung, kacang tanah, dan kedelai'
                ],
                [
                    'nama' => 'Peternakan ayam',
                    'kategori' => 'peternakan',
                    'lokasi' => 'Dusun Tengah',
                    'satuan' => 'Ekor',
                    'jumlah' => 5000,
                    'deskripsi' => 'Peternakan ayam broiler dan petelur'
                ],
                [
                    'nama' => 'Wisata kuliner laut',
                    'kategori' => 'pariwisata',
                    'lokasi' => 'Dusun Pesisir',
                    'satuan' => 'Lokasi',
                    'jumlah' => 1,
                    'deskripsi' => 'Pusat kuliner seafood segar hasil tangkapan nelayan'
                ],
                [
                    'nama' => 'Kerajinan anyaman bambu',
                    'kategori' => 'industri',
                    'lokasi' => 'Dusun Kramat',
                    'satuan' => 'Unit',
                    'jumlah' => 15,
                    'deskripsi' => 'Industri rumahan kerajinan bambu'
                ],
                [
                    'nama' => 'Kelompok seni tradisional',
                    'kategori' => 'budaya',
                    'lokasi' => 'Dusun Tengah',
                    'satuan' => 'Kelompok',
                    'jumlah' => 2,
                    'deskripsi' => 'Kelompok seni tradisional yang masih aktif'
                ],
            ],
            'keterangan_potensi' => 'Potensi desa berdasarkan pemetaan tahun 2023',
        ]);

        // 2c. Create struktur pemerintahan desa
        $this->command->info('Membuat data struktur pemerintahan desa...');

        $strukturPemerintahan = \App\Models\StrukturPemerintahan::create([
            'profil_desa_id' => $desa->id,
            'created_by' => $admin->id,
            'nama_kepala_desa' => 'H. Sarbini, S.Sos',
            'periode_jabatan' => '2020-2026',
            'foto_kepala_desa' => 'uploads/desa/kepala-desa/default-kades.jpg',
            'sambutan_kepala_desa' => "<p>Assalamu'alaikum Wr. Wb.</p>
                <p>Puji syukur kita panjatkan kehadirat Allah SWT, karena atas berkat dan rahmat-Nya kita masih diberikan kesehatan dan kesempatan untuk menjalankan tugas sebagai pelayan masyarakat di Desa Sariharjo.</p>
                <p>Sebagai Kepala Desa, saya bersama dengan perangkat desa dan BPD berkomitmen untuk memajukan desa kita dengan program-program yang inovatif dan tepat sasaran. Kami akan fokus pada pembangunan infrastruktur, peningkatan ekonomi warga, dan pelayanan publik yang prima.</p>
                <p>Website desa ini merupakan salah satu upaya kami untuk meningkatkan transparansi dan kemudahan akses informasi bagi seluruh masyarakat. Melalui website ini, kami berharap masyarakat dapat lebih mudah mendapatkan informasi dan layanan administrasi desa.</p>
                <p>Mari bersama-sama kita bangun Desa Sariharjo yang lebih baik, maju, dan sejahtera. Dengan kerjasama dan gotong royong, tidak ada yang tidak mungkin untuk kita wujudkan.</p>
                <p>Wassalamu'alaikum Wr. Wb.</p>",
            'program_kerja' => "<p>Sebagai Kepala Desa Sariharjo, saya berkomitmen untuk memajukan desa kita melalui program-program strategis dalam berbagai bidang:</p>
                <h3>Pertanian Berkelanjutan</h3>
                <p>Pengembangan sistem pertanian modern yang ramah lingkungan dan memaksimalkan potensi lahan pertanian desa.</p>

                <h3>Ekonomi Kreatif</h3>
                <p>Pemberdayaan ekonomi masyarakat melalui pengembangan UMKM dan produk unggulan desa.</p>

                <h3>Digitalisasi Desa</h3>
                <p>Pemanfaatan teknologi untuk meningkatkan kualitas pelayanan publik dan akses informasi bagi seluruh warga.</p>",
            'prioritas_program' => "<ol>
                <li><strong>Pembangunan Infrastruktur Pertanian</strong><br>
                Pengembangan irigasi dan jalan usaha tani untuk mendukung aktivitas pertanian yang lebih produktif.</li>

                <li><strong>Pengembangan BUMDES</strong><br>
                Penguatan kapasitas BUMDES dalam pengolahan hasil pertanian untuk meningkatkan nilai tambah produk desa.</li>

                <li><strong>Pelatihan Digital untuk Pemuda</strong><br>
                Program pengembangan keterampilan digital bagi generasi muda desa untuk menghadapi era ekonomi digital.</li>

                <li><strong>Modernisasi Layanan Administrasi</strong><br>
                Peningkatan sistem administrasi berbasis teknologi untuk pelayanan publik yang lebih efisien.</li>

                <li><strong>Pengembangan Wisata Desa</strong><br>
                Pengembangan potensi wisata desa berbasis kearifan lokal dan keunikan budaya setempat.</li>
              </ol>",
            'bagan_struktur' => 'uploads/desa/struktur/default-struktur.jpg',
        ]);

        // Buat data aparat desa
        $aparatDesa = [
            [
                'nama' => 'Sugiyanto, S.Pd',
                'jabatan' => 'Sekretaris Desa',
                'pendidikan' => 'D4/S1',
                'tanggal_lahir' => '1975-06-15',
                'alamat' => 'Dusun Tengah RT 03/02, Desa Sariharjo',
                'kontak' => '085712345678',
                'periode_jabatan' => '2020-2026',
                'urutan' => 2,
            ],
            [
                'nama' => 'Siti Nur Jannah',
                'jabatan' => 'Kepala Urusan Keuangan',
                'pendidikan' => 'D4/S1',
                'tanggal_lahir' => '1980-11-23',
                'alamat' => 'Dusun Kramat RT 02/01, Desa Sariharjo',
                'kontak' => '081287654321',
                'periode_jabatan' => '2020-2026',
                'urutan' => 3,
            ],
            [
                'nama' => 'Hadi Sutrisno',
                'jabatan' => 'Kepala Urusan Umum',
                'pendidikan' => 'SMA',
                'tanggal_lahir' => '1978-04-10',
                'alamat' => 'Dusun Pesisir RT 01/03, Desa Sariharjo',
                'kontak' => '087823456789',
                'periode_jabatan' => '2020-2026',
                'urutan' => 4,
            ],
            [
                'nama' => 'Ahmad Fauzi, S.T.',
                'jabatan' => 'Kepala Seksi Pemerintahan',
                'pendidikan' => 'D4/S1',
                'tanggal_lahir' => '1982-08-05',
                'alamat' => 'Dusun Tengah RT 05/02, Desa Sariharjo',
                'kontak' => '081234567890',
                'periode_jabatan' => '2020-2026',
                'urutan' => 5,
            ],
            [
                'nama' => 'Dewi Safitri',
                'jabatan' => 'Kepala Seksi Kesejahteraan',
                'pendidikan' => 'D3',
                'tanggal_lahir' => '1985-12-20',
                'alamat' => 'Dusun Kramat RT 04/01, Desa Sariharjo',
                'kontak' => '085678901234',
                'periode_jabatan' => '2020-2026',
                'urutan' => 6,
            ],
            [
                'nama' => 'Mulyono',
                'jabatan' => 'Kepala Seksi Pelayanan',
                'pendidikan' => 'SMA',
                'tanggal_lahir' => '1979-03-17',
                'alamat' => 'Dusun Pesisir RT 02/03, Desa Sariharjo',
                'kontak' => '089876543210',
                'periode_jabatan' => '2020-2026',
                'urutan' => 7,
            ],
            [
                'nama' => 'Joko Widodo',
                'jabatan' => 'Kepala Dusun Kramat',
                'pendidikan' => 'SMA',
                'tanggal_lahir' => '1970-09-30',
                'alamat' => 'Dusun Kramat RT 01/01, Desa Sariharjo',
                'kontak' => '081345678901',
                'periode_jabatan' => '2020-2026',
                'urutan' => 10,
            ],
            [
                'nama' => 'Slamet Riyadi',
                'jabatan' => 'Kepala Dusun Tengah',
                'pendidikan' => 'SMA',
                'tanggal_lahir' => '1972-07-25',
                'alamat' => 'Dusun Tengah RT 01/02, Desa Sariharjo',
                'kontak' => '085234567890',
                'periode_jabatan' => '2020-2026',
                'urutan' => 11,
            ],
            [
                'nama' => 'Abdul Rahman',
                'jabatan' => 'Kepala Dusun Pesisir',
                'pendidikan' => 'SMA',
                'tanggal_lahir' => '1974-05-12',
                'alamat' => 'Dusun Pesisir RT 03/03, Desa Sariharjo',
                'kontak' => '089234567890',
                'periode_jabatan' => '2020-2026',
                'urutan' => 12,
            ],
            [
                'nama' => 'Yulia Maharani',
                'jabatan' => 'Staff Administrasi',
                'pendidikan' => 'D3',
                'tanggal_lahir' => '1990-02-14',
                'alamat' => 'Dusun Tengah RT 02/02, Desa Sariharjo',
                'kontak' => '081789012345',
                'periode_jabatan' => '2020-2026',
                'urutan' => 15,
            ],
            [
                'nama' => 'Agus Setiawan',
                'jabatan' => 'Operator Desa',
                'pendidikan' => 'D4/S1',
                'tanggal_lahir' => '1988-11-05',
                'alamat' => 'Dusun Kramat RT 03/01, Desa Sariharjo',
                'kontak' => '085890123456',
                'periode_jabatan' => '2020-2026',
                'urutan' => 16,
            ],
        ];

        foreach ($aparatDesa as $data) {
            \App\Models\AparatDesa::create(array_merge($data, [
                'struktur_pemerintahan_id' => $strukturPemerintahan->id,
                'foto' => 'uploads/desa/aparat/default-' . rand(1, 5) . '.jpg',
            ]));
        }

        $this->command->info('Selesai membuat data struktur pemerintahan desa.');

        // 3. Create penduduk data (families)
        // Create 5 family heads
        for ($i = 0; $i < 5; $i++) {
            $kk = $this->generateKK();

            // Memastikan kepala keluarga selalu memiliki kontak
            $kepala = Penduduk::factory()->kepalaKeluarga()->create([
                'id_desa' => $desa->id,
                'kk' => $kk,
                'desa_kelurahan' => 'Desa Sariharjo',
                'kecamatan' => 'Kecamatan Ngaglik',
                'kabupaten' => 'Kabupaten Ngaglik',
                'no_hp' => $this->faker->numerify('08##########'),
                'email' => $this->faker->optional(0.7)->safeEmail(), // 70% kemungkinan memiliki email
            ]);

            // Self-reference for kepala
            $kepala->kepala_keluarga_id = $kepala->id;
            $kepala->save();

            // Create 2-5 family members for each kepala
            $familySize = rand(2, 5);
            for ($j = 0; $j < $familySize; $j++) {
                // 50% anggota keluarga memiliki nomor HP sendiri
                $noHp = rand(0, 1) ? $this->faker->numerify('08##########') : null;
                // 30% anggota keluarga memiliki email
                $email = rand(0, 100) < 30 ? $this->faker->safeEmail() : null;

                Penduduk::factory()->anggotaKeluarga($kepala)->create([
                    'no_hp' => $noHp,
                    'email' => $email,
                ]);
            }
        }

        // 4. Kaitkan penduduk dengan user yang sudah ada (role warga)
        $wargaUsers = User::role('warga')->limit(3)->get();
        $pendudukToLink = Penduduk::whereDoesntHave('user')->whereNull('deleted_at')->inRandomOrder()->limit($wargaUsers->count())->get();

        foreach ($wargaUsers as $index => $user) {
            if (isset($pendudukToLink[$index])) {
                $penduduk = $pendudukToLink[$index];
                // Update user dengan penduduk_id
                $user->penduduk_id = $penduduk->id;
                $user->save();
            }
        }

        // 5. Gunakan user unverified yang sudah ada
        $unverifiedUsers = User::role('unverified')->get();

        // 6. Create verification requests
        if ($unverifiedUsers->count() > 0) {
            // Pending requests
            foreach ($unverifiedUsers->take(3) as $user) {
                VerifikasiPenduduk::factory()->pending()->create([
                    'id_desa' => $desa->id,
                    'user_id' => $user->id,
                    'nik' => $user->nik ?? $this->generateNIK(),
                    'nama' => $user->name,
                ]);
            }

            // Jika masih ada user unverified, buat approved dan rejected
            if ($unverifiedUsers->count() > 3) {
                // Approved requests
                foreach ($unverifiedUsers->slice(3, 2) as $user) {
                    VerifikasiPenduduk::factory()->approved()->create([
                        'id_desa' => $desa->id,
                        'user_id' => $user->id,
                        'nik' => $user->nik ?? $this->generateNIK(),
                        'nama' => $user->name,
                    ]);
                }

                // Rejected requests
                if ($unverifiedUsers->count() > 5) {
                    VerifikasiPenduduk::factory()->rejected()->create([
                        'id_desa' => $desa->id,
                        'user_id' => $unverifiedUsers[5]->id,
                        'nik' => $unverifiedUsers[5]->nik ?? $this->generateNIK(),
                        'nama' => $unverifiedUsers[5]->name,
                    ]);
                }
            }
        }

        // 7. Create Layanan Desa
        $this->command->info('Membuat data layanan desa...');

        // Buat beberapa layanan dengan kategori yang berbeda
        $kategoriCount = [
            'Surat' => 3,
            'Kesehatan' => 2,
            'Pendidikan' => 1,
            'Sosial' => 1,
            'Infrastruktur' => 1
        ];

        foreach ($kategoriCount as $kategori => $count) {
            for ($i = 0; $i < $count; $i++) {
                // Tentukan apakah layanan gratis atau berbayar
                $isGratis = $kategori == 'Sosial' || rand(0, 1) == 1;

                if ($isGratis) {
                    \App\Models\LayananDesa::factory()->gratis()->create([
                        'id_desa' => $desa->id,
                        'created_by' => $admin->id,
                        'kategori' => $kategori,
                    ]);
                } else {
                    \App\Models\LayananDesa::factory()->create([
                        'id_desa' => $desa->id,
                        'created_by' => $admin->id,
                        'kategori' => $kategori,
                    ]);
                }
            }
        }

        // Tambahkan layanan dengan persyaratan minimal
        \App\Models\LayananDesa::factory()->persyaratanMinimal()->gratis()->create([
            'id_desa' => $desa->id,
            'created_by' => $admin->id,
            'kategori' => 'Surat',
            'nama_layanan' => 'Legalisasi Dokumen',
            'deskripsi' => '<p>Layanan pengesahan dokumen (legalisasi) untuk keperluan administrasi. Layanan ini tidak dipungut biaya.</p>',
        ]);

        // 8. Create Berita
        $beritaCount = 10;
        for ($i = 0; $i < $beritaCount; $i++) {
            if ($i < 2) {
                // Buat beberapa pengumuman
                \App\Models\Berita::factory()->pengumuman()->create([
                    'id_desa' => $desa->id,
                    'created_by' => $admin->id,
                    'gambar' => 'uploads/berita/default-announcement.jpg', // Gambar default
                ]);
            } elseif ($i < 5) {
                // Buat beberapa kegiatan
                \App\Models\Berita::factory()->kegiatan()->create([
                    'id_desa' => $desa->id,
                    'created_by' => $admin->id,
                    'gambar' => 'uploads/berita/default-activity.jpg', // Gambar default
                ]);
            } else {
                // Buat berita umum
                \App\Models\Berita::factory()->create([
                    'id_desa' => $desa->id,
                    'created_by' => $admin->id,
                    'gambar' => 'uploads/berita/default-news.jpg', // Gambar default
                ]);
            }
        }

        // 9. Create Keuangan Desa
        // Pemasukan
        $pemasukanCount = 8;
        for ($i = 0; $i < $pemasukanCount; $i++) {
            \App\Models\KeuanganDesa::factory()->pemasukan()->create([
                'id_desa' => $desa->id,
                'created_by' => $admin->id,
                'tanggal' => now()->subDays(rand(1, 300)),
            ]);
        }

        // Pengeluaran
        $pengeluaranCount = 12;
        for ($i = 0; $i < $pengeluaranCount; $i++) {
            \App\Models\KeuanganDesa::factory()->pengeluaran()->create([
                'id_desa' => $desa->id,
                'created_by' => $admin->id,
                'tanggal' => now()->subDays(rand(1, 300)),
            ]);
        }

        // 10. Create Inventaris Desa
        $this->command->info('Membuat data inventaris desa...');

        // Distribusi per kategori
        $kategoriDistribusi = [
            'Elektronik' => 3,
            'Furnitur' => 4,
            'Kendaraan' => 1,
            'ATK' => 2,
            'Komputer' => 2,
            'Peralatan' => 2,
            'Lainnya' => 1,
        ];

        foreach ($kategoriDistribusi as $kategori => $jumlah) {
            for ($i = 0; $i < $jumlah; $i++) {
                Inventaris::factory()->create([
                    'id_desa' => $desa->id,
                    'created_by' => $admin->id,
                    'kategori' => $kategori,
                ]);
            }
        }

        // Tambahan inventaris dengan kondisi rusak
        Inventaris::factory()->rusak()->create([
            'id_desa' => $desa->id,
            'created_by' => $admin->id,
            'kategori' => 'Elektronik',
            'jumlah' => rand(1, 3),
        ]);

        Inventaris::factory()->rusak()->create([
            'id_desa' => $desa->id,
            'created_by' => $admin->id,
            'kategori' => 'Peralatan',
            'jumlah' => rand(1, 5),
        ]);

        // 11. Create Jenis Bantuan Sosial
        $this->command->info('Membuat data jenis bantuan sosial...');

        // Bantuan Tunai (Uang)
        $jenisBantuanTunai = [
            [
                'nama_bansos' => 'Bantuan Langsung Tunai (BLT)',
                'deskripsi' => 'Bantuan tunai langsung kepada masyarakat miskin dan rentan untuk meningkatkan daya beli dan pemenuhan kebutuhan dasar.',
                'kategori' => 'Tunai',
                'instansi_pemberi' => 'Kementerian Sosial',
                'periode' => 'Bulanan',
                'bentuk_bantuan' => 'uang',
                'nominal_standar' => 300000,
                'is_active' => true,
            ],
            [
                'nama_bansos' => 'Program Keluarga Harapan (PKH)',
                'deskripsi' => 'Program bantuan bersyarat untuk keluarga miskin dengan ibu hamil, balita, anak sekolah, lansia, atau disabilitas. Bantuan diberikan per 3 bulan.',
                'kategori' => 'Tunai',
                'instansi_pemberi' => 'Kementerian Sosial',
                'periode' => 'Triwulan',
                'bentuk_bantuan' => 'uang',
                'nominal_standar' => 2000000,
                'is_active' => true,
            ],
        ];

        // Bantuan Sembako
        $jenisBantuanBarang = [
            [
                'nama_bansos' => 'Bantuan Pangan Non-Tunai (BPNT)',
                'deskripsi' => 'Bantuan pangan dalam bentuk bahan makanan pokok seperti beras, telur, dan minyak goreng yang diberikan setiap bulan.',
                'kategori' => 'Pangan',
                'instansi_pemberi' => 'Kementerian Sosial',
                'periode' => 'Bulanan',
                'bentuk_bantuan' => 'barang',
                'jumlah_per_penerima' => 10,
                'satuan' => 'kg',
                'is_active' => true,
            ],
            [
                'nama_bansos' => 'Sembako untuk Lansia',
                'deskripsi' => 'Paket sembako khusus untuk warga lanjut usia yang meliputi bahan pangan bergizi dan suplemen.',
                'kategori' => 'Sembako',
                'instansi_pemberi' => 'Pemerintah Desa',
                'periode' => 'Bulanan',
                'bentuk_bantuan' => 'barang',
                'jumlah_per_penerima' => 1,
                'satuan' => 'paket',
                'is_active' => true,
            ],
        ];

        // Bantuan Pendidikan
        $jenisBantuanPendidikan = [
            [
                'nama_bansos' => 'Program Indonesia Pintar (PIP)',
                'deskripsi' => 'Bantuan pendidikan bagi siswa kurang mampu untuk biaya sekolah, seragam, dan perlengkapan belajar.',
                'kategori' => 'Pendidikan',
                'instansi_pemberi' => 'Kementerian Pendidikan',
                'periode' => 'Semester',
                'bentuk_bantuan' => 'uang',
                'nominal_standar' => 750000,
                'is_active' => true,
            ],
        ];

        // Bantuan Kesehatan
        $jenisBantuanKesehatan = [
            [
                'nama_bansos' => 'BPJS PBI (Penerima Bantuan Iuran)',
                'deskripsi' => 'Bantuan iuran BPJS Kesehatan untuk masyarakat tidak mampu agar mendapatkan akses layanan kesehatan.',
                'kategori' => 'Kesehatan',
                'instansi_pemberi' => 'BPJS Kesehatan',
                'periode' => 'Bulanan',
                'bentuk_bantuan' => 'jasa',
                'jumlah_per_penerima' => 1,
                'satuan' => 'paket',
                'is_active' => true,
            ],
        ];

        // Bantuan UMKM
        $jenisBantuanUMKM = [
            [
                'nama_bansos' => 'Bantuan Produktif Usaha Mikro (BPUM)',
                'deskripsi' => 'Bantuan modal usaha untuk pelaku UMKM yang terdampak pandemi untuk memulai kembali atau mengembangkan usaha.',
                'kategori' => 'UMKM',
                'instansi_pemberi' => 'Kementerian UMKM',
                'periode' => 'Sekali',
                'bentuk_bantuan' => 'bantuan_modal',
                'jumlah_per_penerima' => 1,
                'satuan' => 'paket',
                'nominal_standar' => 1200000,
                'is_active' => true,
            ],
        ];

        // Gabungkan semua jenis bantuan
        $jenisBantuan = array_merge(
            $jenisBantuanTunai,
            $jenisBantuanBarang,
            $jenisBantuanPendidikan,
            $jenisBantuanKesehatan,
            $jenisBantuanUMKM
        );

        $jenisBansosCreated = [];
        foreach ($jenisBantuan as $data) {
            $jenisBansosCreated[] = JenisBansos::create($data);
        }

        // Tambahkan factory untuk variasi lebih banyak
        JenisBansos::factory()->bantuanTunai()->count(2)->create();
        JenisBansos::factory()->bantuanSembako()->count(2)->create();
        JenisBansos::factory()->bantuanPendidikan()->count(1)->create();
        JenisBansos::factory()->count(3)->create(); // Acak

        // 12. Create Bantuan Sosial Data
        $this->command->info('Membuat data bantuan sosial...');

        // Ambil penduduk yang belum terhubung dengan user
        $pendudukAll = Penduduk::where('id_desa', $desa->id)->get();

        // 1. Bantuan yang diajukan dari admin
        $this->command->info('Membuat data bantuan yang diajukan oleh admin...');
        foreach ($pendudukAll->random(3) as $p) {
            $tanggal_pengajuan = now()->subDays(rand(1, 7));

            $alasan_pengajuan = [
                'Termasuk dalam keluarga tidak mampu berdasarkan hasil pendataan',
                'Kehilangan pekerjaan utama dan perlu bantuan sementara',
                'Lansia yang hidup sendiri dan membutuhkan bantuan',
                'Biaya pengobatan keluarga yang memberatkan',
                'Memiliki tanggungan anak sekolah yang banyak'
            ];

            $bansos = Bansos::create([
                'id_desa' => $desa->id,
                'penduduk_id' => $p->id,
                'jenis_bansos_id' => $jenisBansosCreated[array_rand($jenisBansosCreated)]->id,
                'status' => 'Diajukan',
                'prioritas' => rand(0, 1) ? 'Sedang' : 'Rendah',
                'sumber_pengajuan' => 'admin',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'keterangan' => 'Pengajuan bantuan sosial oleh petugas desa',
                'alasan_pengajuan' => $alasan_pengajuan[array_rand($alasan_pengajuan)],
                'notifikasi_terkirim' => false,
                'diubah_oleh' => $admin->id,
            ]);

            // Tambahkan history
            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => null,
                'status_baru' => 'Diajukan',
                'keterangan' => 'Pengajuan bantuan baru oleh admin',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_pengajuan,
            ]);
        }

        // 2. Bantuan yang diajukan dari warga
        $this->command->info('Membuat data bantuan yang diajukan oleh warga...');
        foreach ($pendudukAll->random(3) as $p) {
            $tanggal_pengajuan = now()->subDays(rand(1, 5));

            $alasan_pengajuan = [
                'Kesulitan memenuhi kebutuhan pokok sehari-hari',
                'Biaya pendidikan anak yang memberatkan',
                'Biaya pengobatan yang tidak tercukupi',
                'Penghasilan tidak mencukupi karena PHK',
                'Rumah dalam kondisi tidak layak huni'
            ];

            $bansos = Bansos::factory()->dariWarga()->create([
                'id_desa' => $desa->id,
                'penduduk_id' => $p->id,
                'jenis_bansos_id' => $jenisBansosCreated[array_rand($jenisBansosCreated)]->id,
                'prioritas' => rand(0, 2) == 0 ? 'Tinggi' : 'Sedang',
                'sumber_pengajuan' => 'warga',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'keterangan' => 'Pengajuan bantuan sosial oleh warga',
                'alasan_pengajuan' => $alasan_pengajuan[array_rand($alasan_pengajuan)],
                'notifikasi_terkirim' => true,
                'is_urgent' => rand(0, 3) == 0, // 25% kemungkinan urgent
                'diubah_oleh' => $admin->id,
            ]);

            // Tambahkan history
            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => null,
                'status_baru' => 'Diajukan',
                'keterangan' => 'Pengajuan bantuan baru oleh warga',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_pengajuan,
            ]);
        }

        // 3. Bantuan yang sedang dalam verifikasi
        $this->command->info('Membuat data bantuan yang dalam verifikasi...');
        foreach ($pendudukAll->random(3) as $p) {
            $tanggal_pengajuan = now()->subDays(rand(10, 15));
            $tanggal_mulai_verifikasi = now()->subDays(rand(1, 3));

            $bansos = Bansos::create([
                'id_desa' => $desa->id,
                'penduduk_id' => $p->id,
                'jenis_bansos_id' => $jenisBansosCreated[array_rand($jenisBansosCreated)]->id,
                'status' => 'Dalam Verifikasi',
                'prioritas' => rand(0, 1) ? 'Tinggi' : 'Sedang',
                'sumber_pengajuan' => 'admin',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'keterangan' => 'Pengajuan sedang dalam proses verifikasi',
                'alasan_pengajuan' => 'Keluarga dengan kondisi ekonomi sulit dan membutuhkan bantuan',
                'notifikasi_terkirim' => true,
                'is_urgent' => rand(0, 5) == 0, // 20% kemungkinan urgent
                'diubah_oleh' => $admin->id,
            ]);

            // Tambahkan history
            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => null,
                'status_baru' => 'Diajukan',
                'keterangan' => 'Pengajuan bantuan baru',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_pengajuan,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Diajukan',
                'status_baru' => 'Dalam Verifikasi',
                'keterangan' => 'Pengajuan diproses untuk verifikasi',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_mulai_verifikasi,
            ]);
        }

        // 4. Bantuan yang diverifikasi
        $this->command->info('Membuat data bantuan yang diverifikasi...');
        foreach ($pendudukAll->random(4) as $p) {
            $tanggal_pengajuan = now()->subDays(rand(20, 25));
            $tanggal_mulai_verifikasi = now()->subDays(rand(10, 15));
            $tanggal_verifikasi = now()->subDays(rand(3, 8));

            $bansos = Bansos::create([
                'id_desa' => $desa->id,
                'penduduk_id' => $p->id,
                'jenis_bansos_id' => $jenisBansosCreated[array_rand($jenisBansosCreated)]->id,
                'status' => 'Diverifikasi',
                'prioritas' => collect(['Tinggi', 'Sedang', 'Rendah'])->random(),
                'sumber_pengajuan' => 'admin',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'keterangan' => 'Data telah diverifikasi dan valid sesuai dengan kriteria penerima bantuan',
                'alasan_pengajuan' => 'Keluarga dengan tanggungan banyak dan penghasilan tidak mencukupi',
                'notifikasi_terkirim' => true,
                'diubah_oleh' => $admin->id,
            ]);

            // Tambahkan history
            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => null,
                'status_baru' => 'Diajukan',
                'keterangan' => 'Pengajuan bantuan baru',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_pengajuan,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Diajukan',
                'status_baru' => 'Dalam Verifikasi',
                'keterangan' => 'Pengajuan diproses untuk verifikasi',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_mulai_verifikasi,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Dalam Verifikasi',
                'status_baru' => 'Diverifikasi',
                'keterangan' => 'Data telah diverifikasi dan valid',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_verifikasi,
            ]);
        }

        // 5. Bantuan yang disetujui
        $this->command->info('Membuat data bantuan yang disetujui...');
        foreach ($pendudukAll->random(3) as $p) {
            $tanggal_pengajuan = now()->subDays(rand(40, 45));
            $tanggal_mulai_verifikasi = now()->subDays(rand(30, 35));
            $tanggal_verifikasi = now()->subDays(rand(20, 25));
            $tanggal_persetujuan = now()->subDays(rand(5, 10));

            $bansos = Bansos::create([
                'id_desa' => $desa->id,
                'penduduk_id' => $p->id,
                'jenis_bansos_id' => $jenisBansosCreated[array_rand($jenisBansosCreated)]->id,
                'status' => 'Disetujui',
                'prioritas' => collect(['Tinggi', 'Sedang'])->random(),
                'sumber_pengajuan' => 'admin',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'tenggat_pengambilan' => now()->addDays(rand(3, 14)),
                'keterangan' => 'Pengajuan disetujui untuk menerima bantuan',
                'alasan_pengajuan' => 'Keluarga dengan kondisi ekonomi sulit akibat PHK dan memiliki tanggungan anak sekolah',
                'diubah_oleh' => $admin->id,
                'notifikasi_terkirim' => true,
            ]);

            // Tambahkan history (dengan urutan status yang sesuai)
            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => null,
                'status_baru' => 'Diajukan',
                'keterangan' => 'Pengajuan bantuan baru',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_pengajuan,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Diajukan',
                'status_baru' => 'Dalam Verifikasi',
                'keterangan' => 'Pengajuan diproses untuk verifikasi',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_mulai_verifikasi,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Dalam Verifikasi',
                'status_baru' => 'Diverifikasi',
                'keterangan' => 'Data telah diverifikasi dan valid',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_verifikasi,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Diverifikasi',
                'status_baru' => 'Disetujui',
                'keterangan' => 'Pengajuan disetujui untuk menerima bantuan',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_persetujuan,
            ]);
        }

        // 6. Bantuan yang sudah diterima
        $this->command->info('Membuat data bantuan yang sudah diterima...');
        foreach ($pendudukAll->random(6) as $p) {
            $jenisBansos = $jenisBansosCreated[array_rand($jenisBansosCreated)];
            $tanggal_pengajuan = now()->subDays(rand(60, 90));
            $tanggal_mulai_verifikasi = now()->subDays(rand(50, 60));
            $tanggal_verifikasi = now()->subDays(rand(40, 50));
            $tanggal_persetujuan = now()->subDays(rand(30, 40));
            $tanggal_penerimaan = now()->subDays(rand(5, 20));

            $bansos = Bansos::create([
                'id_desa' => $desa->id,
                'penduduk_id' => $p->id,
                'jenis_bansos_id' => $jenisBansos->id,
                'status' => 'Sudah Diterima',
                'prioritas' => collect(['Tinggi', 'Sedang', 'Rendah'])->random(),
                'sumber_pengajuan' => 'admin',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'tanggal_penerimaan' => $tanggal_penerimaan,
                'tenggat_pengambilan' => $tanggal_persetujuan->copy()->addDays(rand(5, 15)),
                'keterangan' => 'Bantuan telah diterima oleh penerima',
                'alasan_pengajuan' => 'Keluarga tidak mampu dengan kondisi rumah yang tidak layak huni',
                'bukti_penerimaan' => 'bansos/bukti/bukti-dummy-' . rand(1, 5) . '.jpg',
                'diubah_oleh' => $admin->id,
                'notifikasi_terkirim' => true,
            ]);

            // Tambahkan history
            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => null,
                'status_baru' => 'Diajukan',
                'keterangan' => 'Pengajuan bantuan baru',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_pengajuan,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Diajukan',
                'status_baru' => 'Dalam Verifikasi',
                'keterangan' => 'Pengajuan diproses untuk verifikasi',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_mulai_verifikasi,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Dalam Verifikasi',
                'status_baru' => 'Diverifikasi',
                'keterangan' => 'Data telah diverifikasi dan valid',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_verifikasi,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Diverifikasi',
                'status_baru' => 'Disetujui',
                'keterangan' => 'Pengajuan disetujui untuk menerima bantuan',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_persetujuan,
            ]);

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => 'Disetujui',
                'status_baru' => 'Sudah Diterima',
                'keterangan' => 'Bantuan telah diterima oleh penerima',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_penerimaan,
            ]);
        }

        // 7. Bantuan yang ditolak
        $this->command->info('Membuat data bantuan yang ditolak...');
        $alasanPenolakan = [
            'Data tidak lengkap',
            'Tidak memenuhi syarat',
            'Sudah menerima bantuan lain',
            'Data tidak sesuai dengan kondisi di lapangan',
            'Duplikasi pengajuan',
        ];

        foreach ($pendudukAll->random(3) as $p) {
            $tanggal_pengajuan = now()->subDays(rand(20, 30));
            $tanggal_proses = $tanggal_pengajuan->copy()->addDays(rand(3, 7));
            $status_terakhir = 'Diajukan';

            // 70% kemungkinan melalui proses verifikasi sebelum ditolak
            if (rand(0, 100) < 70) {
                $tanggal_proses = now()->subDays(rand(5, 15));
                $status_terakhir = rand(0, 1) ? 'Dalam Verifikasi' : 'Diverifikasi';
            }

            $alasan = $alasanPenolakan[array_rand($alasanPenolakan)];
            $alasan_pengajuan = [
                'Keluarga kesulitan biaya sekolah anak',
                'Baru kehilangan pekerjaan karena PHK',
                'Perlu bantuan untuk biaya berobat',
                'Membutuhkan bantuan untuk memperbaiki rumah',
                'Kesulitan membayar kebutuhan pokok'
            ];

            $bansos = Bansos::create([
                'id_desa' => $desa->id,
                'penduduk_id' => $p->id,
                'jenis_bansos_id' => $jenisBansosCreated[array_rand($jenisBansosCreated)]->id,
                'status' => 'Ditolak',
                'prioritas' => 'Rendah',
                'sumber_pengajuan' => 'admin',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'keterangan' => 'Ditolak: ' . $alasan,
                'alasan_pengajuan' => $alasan_pengajuan[array_rand($alasan_pengajuan)],
                'notifikasi_terkirim' => true,
                'is_urgent' => false,
                'diubah_oleh' => $admin->id,
            ]);

            // Tambahkan history
            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => null,
                'status_baru' => 'Diajukan',
                'keterangan' => 'Pengajuan bantuan baru',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_pengajuan,
            ]);

            if ($status_terakhir !== 'Diajukan') {
                BansosHistory::create([
                    'bansos_id' => $bansos->id,
                    'status_lama' => 'Diajukan',
                    'status_baru' => 'Dalam Verifikasi',
                    'keterangan' => 'Pengajuan diproses untuk verifikasi',
                    'diubah_oleh' => $admin->id,
                    'waktu_perubahan' => $tanggal_proses->subDays(rand(2, 4)),
                ]);
            }

            if ($status_terakhir === 'Diverifikasi') {
                BansosHistory::create([
                    'bansos_id' => $bansos->id,
                    'status_lama' => 'Dalam Verifikasi',
                    'status_baru' => 'Diverifikasi',
                    'keterangan' => 'Data telah diverifikasi',
                    'diubah_oleh' => $admin->id,
                    'waktu_perubahan' => $tanggal_proses->subDays(rand(1, 2)),
                ]);
            }

            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => $status_terakhir,
                'status_baru' => 'Ditolak',
                'keterangan' => $alasan,
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_proses,
            ]);
        }

        // 8. Bantuan urgent/mendesak
        $this->command->info('Membuat data bantuan mendesak...');
        foreach ($pendudukAll->random(2) as $p) {
            $tanggal_pengajuan = now()->subDays(rand(1, 3));

            $alasan_urgent = [
                'Keluarga dalam kondisi darurat ekonomi',
                'Kepala keluarga sakit parah',
                'Rumah rusak parah akibat bencana',
                'Lansia tanpa penghasilan dan tanpa keluarga',
                'Keluarga dengan balita malnutrisi',
            ];

            $bansos = Bansos::factory()->dariWarga()->urgent()->create([
                'id_desa' => $desa->id,
                'penduduk_id' => $p->id,
                'jenis_bansos_id' => $jenisBansosCreated[array_rand($jenisBansosCreated)]->id,
                'status' => 'Diajukan',
                'prioritas' => 'Tinggi',
                'sumber_pengajuan' => 'warga',
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'keterangan' => $alasan_urgent[array_rand($alasan_urgent)],
                'alasan_pengajuan' => $alasan_urgent[array_rand($alasan_urgent)],
                'notifikasi_terkirim' => true,
                'is_urgent' => true,
                'diubah_oleh' => $admin->id,
            ]);

            // Tambahkan history
            BansosHistory::create([
                'bansos_id' => $bansos->id,
                'status_lama' => null,
                'status_baru' => 'Diajukan',
                'keterangan' => 'Pengajuan bantuan darurat/mendesak',
                'diubah_oleh' => $admin->id,
                'waktu_perubahan' => $tanggal_pengajuan,
            ]);
        }

        $this->command->info('Selesai membuat data dummy bantuan sosial.');

        // 13. Create Pengaduan Warga
        $this->command->info('Membuat data pengaduan warga...');

        // Pengaduan belum ditangani (10 data)
        \App\Models\Pengaduan::factory()
            ->belumDitangani()
            ->count(10)
            ->create([
                'id_desa' => $desa->id,
            ]);

        // Pengaduan belum ditangani prioritas tinggi (5 data)
        \App\Models\Pengaduan::factory()
            ->belumDitangani()
            ->prioritasTinggi()
            ->count(5)
            ->create([
                'id_desa' => $desa->id,
            ]);

        // Pengaduan sedang diproses (8 data)
        \App\Models\Pengaduan::factory()
            ->sedangDiproses()
            ->count(8)
            ->create([
                'id_desa' => $desa->id,
                'ditangani_oleh' => $admin->id,
            ]);

        // Pengaduan selesai (15 data)
        \App\Models\Pengaduan::factory()
            ->selesai()
            ->count(15)
            ->create([
                'id_desa' => $desa->id,
                'ditangani_oleh' => $admin->id,
            ]);

        // Pengaduan ditolak (4 data)
        \App\Models\Pengaduan::factory()
            ->ditolak()
            ->count(4)
            ->create([
                'id_desa' => $desa->id,
                'ditangani_oleh' => $admin->id,
            ]);

        // Beberapa pengaduan dengan foto (acak)
        \App\Models\Pengaduan::factory()
            ->denganFoto()
            ->count(10)
            ->create([
                'id_desa' => $desa->id,
            ]);

        $this->command->info('Selesai membuat data pengaduan warga.');

        // 14. Create UMKM
        $this->command->info('Membuat data UMKM warga...');

        // UMKM terverifikasi (15 data)
        \App\Models\Umkm::factory()
            ->terverifikasi()
            ->count(15)
            ->create([
                'id_desa' => $desa->id,
            ]);

        // UMKM belum terverifikasi (5 data)
        \App\Models\Umkm::factory()
            ->belumTerverifikasi()
            ->count(5)
            ->create([
                'id_desa' => $desa->id,
            ]);

        $this->command->info('Selesai membuat data UMKM warga.');
    }

    /**
     * Generate a valid format KK (16 digits)
     */
    private function generateKK(): string
    {
        // Format: PPRRSSXXXXXXXXXX
        // PP = Kode Provinsi (2 digit)
        // RR = Kode Kabupaten/Kota (2 digit)
        // SS = Kode Kecamatan (2 digit)
        // XXXXXXXXXX = Nomor Urut (10 digit)

        $provinsi = rand(11, 94);
        $kabupaten = rand(1, 99);
        $kecamatan = rand(1, 99);
        $nomor = rand(1, 9999999999);

        return sprintf('%02d%02d%02d%010d',
            $provinsi, $kabupaten, $kecamatan, $nomor
        );
    }

    /**
     * Generate a valid format NIK (16 digits)
     */
    private function generateNIK(): string
    {
        // Format: PPRRSSDDMMYYXXXX
        // PP = Kode Provinsi (2 digit)
        // RR = Kode Kabupaten/Kota (2 digit)
        // SS = Kode Kecamatan (2 digit)
        // DDMMYY = Tanggal Lahir (6 digit)
        // XXXX = Nomor Urut (4 digit)

        $provinsi = rand(11, 94);
        $kabupaten = rand(1, 99);
        $kecamatan = rand(1, 99);
        $tanggal = rand(1, 28);
        $bulan = rand(1, 12);
        $tahun = rand(0, 99);
        $nomor = rand(1, 9999);

        return sprintf('%02d%02d%02d%02d%02d%02d%04d',
            $provinsi, $kabupaten, $kecamatan, $tanggal, $bulan, $tahun, $nomor
        );
    }
}
