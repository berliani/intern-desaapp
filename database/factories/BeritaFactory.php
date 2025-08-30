<?php

namespace Database\Factories;

use App\Models\Berita;
use App\Models\ProfilDesa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Berita>
 */
class BeritaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Berita::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = $this->faker->randomElement([
            'Umum', 'Pemerintahan', 'Kesehatan', 'Sosial',
            'Pendidikan', 'Pengumuman', 'Kegiatan', 'Infrastruktur'
        ]);

        return [
            'desa_id' => ProfilDesa::factory(),
            'created_by' => User::factory(),
            'judul' => $this->faker->sentence(6),
            'isi' => '<p>' . implode('</p><p>', $this->faker->paragraphs(5)) . '</p>',
            'kategori' => $kategori,
            'gambar' => 'uploads/berita/dummy-'.rand(1, 5).'.jpg', // Dummy path, perlu menyiapkan gambar
        ];
    }

    /**
     * State untuk berita pengumuman.
     */
    public function pengumuman()
    {
        return $this->state(function (array $attributes) {
            return [
                'kategori' => 'Pengumuman',
                'judul' => 'Pengumuman: ' . $this->faker->sentence(5),
            ];
        });
    }

    /**
     * State untuk berita kegiatan.
     */
    public function kegiatan()
    {
        return $this->state(function (array $attributes) {
            return [
                'kategori' => 'Kegiatan',
                'judul' => 'Kegiatan: ' . $this->faker->sentence(5),
            ];
        });
    }
}
