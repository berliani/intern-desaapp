<?php

namespace Database\Factories;

use App\Models\Bansos;
use App\Models\BansosHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BansosHistory>
 */
class BansosHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BansosHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusOptions = ['Diajukan', 'Dalam Verifikasi', 'Diverifikasi', 'Disetujui', 'Ditolak', 'Sudah Diterima', 'Dibatalkan'];
        $statusBaru = $this->faker->randomElement($statusOptions);
        $statusLamaOptions = array_filter($statusOptions, fn($status) => $status !== $statusBaru);
        $statusLama = count($statusLamaOptions) > 0 ? $this->faker->randomElement($statusLamaOptions) : null;

        $keterangan = match($statusBaru) {
            'Diajukan' => 'Pengajuan bantuan baru',
            'Dalam Verifikasi' => 'Sedang dalam proses verifikasi data',
            'Diverifikasi' => 'Data telah diverifikasi dan valid',
            'Disetujui' => 'Pengajuan disetujui untuk menerima bantuan',
            'Ditolak' => $this->faker->randomElement([
                'Data tidak lengkap',
                'Tidak memenuhi syarat',
                'Sudah menerima bantuan lain',
                'Data tidak sesuai dengan kondisi di lapangan',
            ]),
            'Sudah Diterima' => 'Bantuan telah diterima oleh penerima',
            'Dibatalkan' => 'Pengajuan dibatalkan oleh pemohon',
            default => 'Perubahan status',
        };

        return [
            'bansos_id' => Bansos::factory(),
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'keterangan' => $keterangan,
            'diubah_oleh' => User::factory(),
            'waktu_perubahan' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}