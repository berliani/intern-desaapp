<?php

namespace Database\Factories;

use App\Models\Penduduk;
use App\Models\ProfilDesa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penduduk>
 */
class PendudukFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Penduduk::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $firstName = $this->faker->firstName($gender);
        $lastName = $this->faker->lastName();
        $fullName = $firstName . ' ' . $lastName;

        // Map gender to 'L' or 'P' for jenis_kelamin field
        $jenisKelamin = $gender === 'male' ? 'L' : 'P';

        // Generate tempatLahir
        $tempatLahir = $this->faker->city();

        // Generate agama
        $agama = $this->faker->randomElement([
            'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'
        ]);

        // Generate status perkawinan
        $statusPerkawinan = $this->faker->randomElement([
            'Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'
        ]);

        // Generate alamat lengkap
        $desa = $this->faker->randomElement(['Desa Kedungwungu', 'Desa Sukamaju', 'Desa Cibeureum', 'Desa Karangsari']);
        $kecamatan = $this->faker->randomElement(['Kecamatan Krangkeng', 'Kecamatan Jatibarang', 'Kecamatan Losarang']);
        $kabupaten = $this->faker->randomElement(['Kabupaten Indramayu', 'Kabupaten Cirebon', 'Kabupaten Majalengka']);

        // Generate golongan darah
        $golonganDarah = $this->faker->randomElement(['A', 'B', 'AB', 'O', '-']);

        return [
            'desa_id' => ProfilDesa::factory(),
            'nik' => $this->generateNIK(),
            'kk' => $this->generateKK(),
            'kepala_keluarga_id' => null, // Will be set later
            'nama' => $fullName,
            'alamat' => $this->faker->streetAddress(),
            'rt_rw' => sprintf('%03d/%03d', $this->faker->numberBetween(1, 20), $this->faker->numberBetween(1, 10)),
            'desa_kelurahan' => $desa,
            'kecamatan' => $kecamatan,
            'kabupaten' => $kabupaten,
            'tempat_lahir' => $tempatLahir,
            'tanggal_lahir' => $this->faker->dateTimeBetween('-80 years', '-10 years'),
            'jenis_kelamin' => $jenisKelamin,
            'agama' => $agama,
            'status_perkawinan' => $statusPerkawinan,
            'kepala_keluarga' => false, // Default false, will be set for some records
            'pekerjaan' => $this->faker->randomElement([
                'Petani', 'Nelayan', 'PNS', 'Guru', 'Dokter', 'Wiraswasta',
                'Karyawan Swasta', 'Buruh', 'Pedagang', 'Ibu Rumah Tangga'
            ]),
            'pendidikan' => $this->faker->randomElement([
                'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3', 'Tidak Sekolah'
            ]),
            // Menambahkan informasi kontak
            'no_hp' => $this->faker->randomElement([
                $this->faker->numerify('08##########'),
                $this->faker->numerify('08##########'),
                null
            ]),
            'email' => $this->faker->randomElement([
                $this->faker->safeEmail(),
                null
            ]),
            'golongan_darah' => $golonganDarah,
        ];
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

        $provinsi = $this->faker->numberBetween(11, 94);
        $kabupaten = $this->faker->numberBetween(1, 99);
        $kecamatan = $this->faker->numberBetween(1, 99);
        $tanggal = $this->faker->numberBetween(1, 28);
        $bulan = $this->faker->numberBetween(1, 12);
        $tahun = $this->faker->numberBetween(0, 99);
        $nomor = $this->faker->numberBetween(1, 9999);

        return sprintf('%02d%02d%02d%02d%02d%02d%04d',
            $provinsi, $kabupaten, $kecamatan, $tanggal, $bulan, $tahun, $nomor
        );
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

        $provinsi = $this->faker->numberBetween(11, 94);
        $kabupaten = $this->faker->numberBetween(1, 99);
        $kecamatan = $this->faker->numberBetween(1, 99);
        $nomor = $this->faker->numberBetween(1, 9999999999);

        return sprintf('%02d%02d%02d%010d',
            $provinsi, $kabupaten, $kecamatan, $nomor
        );
    }

    /**
     * Configure the model factory to create kepala keluarga
     */
    public function kepalaKeluarga(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'kepala_keluarga' => true,
            ];
        });
    }

    /**
     * Configure the model factory to create anggota keluarga
     */
    public function anggotaKeluarga(Penduduk $kepalaKeluarga): Factory
    {
        return $this->state(function (array $attributes) use ($kepalaKeluarga) {
            return [
                'desa_id' => $kepalaKeluarga->desa_id,
                'kk' => $kepalaKeluarga->kk,
                'kepala_keluarga_id' => $kepalaKeluarga->id,
                'kepala_keluarga' => false,
                'desa_kelurahan' => $kepalaKeluarga->desa_kelurahan,
                'kecamatan' => $kepalaKeluarga->kecamatan,
                'kabupaten' => $kepalaKeluarga->kabupaten,
            ];
        });
    }

    /**
     * Configure the model to create laki-laki
     */
    public function lakiLaki(): Factory
    {
        return $this->state(function (array $attributes) {
            $firstName = $this->faker->firstName('male');
            $lastName = $this->faker->lastName();

            return [
                'nama' => $firstName . ' ' . $lastName,
                'jenis_kelamin' => 'L',
            ];
        });
    }

    /**
     * Configure the model to create perempuan
     */
    public function perempuan(): Factory
    {
        return $this->state(function (array $attributes) {
            $firstName = $this->faker->firstName('female');
            $lastName = $this->faker->lastName();

            return [
                'nama' => $firstName . ' ' . $lastName,
                'jenis_kelamin' => 'P',
            ];
        });
    }
}
