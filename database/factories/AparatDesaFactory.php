<?php

namespace Database\Factories;

use App\Models\AparatDesa;
use App\Models\StrukturPemerintahan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AparatDesa>
 */
class AparatDesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AparatDesa::class;

    public function definition(): array
    {
        // List jabatan umum di pemerintahan desa
        $jabatan = $this->faker->randomElement([
            'Sekretaris Desa',
            'Kepala Urusan Keuangan',
            'Kepala Urusan Umum',
            'Kepala Urusan Perencanaan',
            'Kepala Seksi Pemerintahan',
            'Kepala Seksi Kesejahteraan',
            'Kepala Seksi Pelayanan',
            'Kepala Dusun',
            'Staff Administrasi',
            'Operator Desa',
            'Bendahara Desa',
        ]);

        // Pendidikan
        $pendidikan = $this->faker->randomElement([
            'SMP',
            'SMA/SMK',
            'D3',
            'S1',
            'S2'
        ]);

        return [
            'struktur_pemerintahan_id' => StrukturPemerintahan::factory(),
            'nama' => $this->faker->name(),
            'jabatan' => $jabatan,
            'foto' => 'uploads/desa/aparat/default-' . rand(1, 5) . '.jpg',
            'pendidikan' => $pendidikan,
            'tanggal_lahir' => $this->faker->dateTimeBetween('-60 years', '-25 years'),
            'alamat' => $this->faker->address(),
            'kontak' => $this->faker->phoneNumber(),
            'periode_jabatan' => '2020-2025',
            'urutan' => $this->getUrutanByJabatan($jabatan),
        ];
    }

    /**
     * State untuk aparat dengan jabatan Sekretaris Desa
     */
    public function sekretarisDesa(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'jabatan' => 'Sekretaris Desa',
                'urutan' => 2,
            ];
        });
    }

    /**
     * State untuk aparat dengan jabatan Kepala Dusun
     */
    public function kepalaDusun(): static
    {
        return $this->state(function (array $attributes) {
            $dusun = $this->faker->randomElement(['Dusun Kramat', 'Dusun Tengah', 'Dusun Pesisir']);
            return [
                'jabatan' => 'Kepala ' . $dusun,
                'urutan' => 10,
            ];
        });
    }

    /**
     * Helper method untuk menentukan urutan berdasarkan jabatan
     */
    private function getUrutanByJabatan(string $jabatan): int
    {
        return match($jabatan) {
            'Sekretaris Desa' => 2,
            'Kepala Urusan Keuangan', 'Bendahara Desa' => 3,
            'Kepala Urusan Umum' => 4,
            'Kepala Urusan Perencanaan' => 5,
            'Kepala Seksi Pemerintahan' => 6,
            'Kepala Seksi Kesejahteraan' => 7,
            'Kepala Seksi Pelayanan' => 8,
            'Kepala Dusun' => 10,
            'Staff Administrasi', 'Operator Desa' => 15,
            default => rand(5, 20),
        };
    }
} 