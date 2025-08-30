<?php

namespace Database\Factories;

use App\Models\ProfilDesa;
use App\Models\User;
use App\Models\VerifikasiPenduduk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VerifikasiPenduduk>
 */
class VerifikasiPendudukFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VerifikasiPenduduk::class;

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

        // Map gender to 'L' or 'P'
        $jenisKelamin = $gender === 'male' ? 'L' : 'P';

        return [
            'user_id' => User::factory(),
            'desa_id' => ProfilDesa::factory(),
            'nik' => $this->faker->numerify('################'),
            'kk' => $this->faker->numerify('################'),
            'nama' => $fullName,
            'alamat' => $this->faker->address(),
            'rt_rw' => sprintf('%03d/%03d', $this->faker->numberBetween(1, 20), $this->faker->numberBetween(1, 10)),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'jenis_kelamin' => $jenisKelamin,
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']),
            'status_perkawinan' => $this->faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']),
            'kepala_keluarga' => $this->faker->boolean(20),
            'pekerjaan' => $this->faker->jobTitle(),
            'pendidikan' => $this->faker->randomElement(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']),
            'status' => 'pending',
        ];
    }

    /**
     * Configure the model factory to create pending verifications
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
            ];
        });
    }

    /**
     * Configure the model factory to create approved verifications
     */
    public function approved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
                'catatan' => 'Verifikasi disetujui',
            ];
        });
    }

    /**
     * Configure the model factory to create rejected verifications
     */
    public function rejected(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'catatan' => $this->faker->randomElement([
                    'Data tidak lengkap',
                    'NIK tidak sesuai KTP',
                    'Alamat tidak ditemukan',
                    'Dokumen pendukung tidak valid',
                ]),
            ];
        });
    }
}
