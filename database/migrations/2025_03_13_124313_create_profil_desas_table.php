<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profil_desa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');

            // Identitas dasar desa
            $table->string('nama_desa', 100);
            $table->string('kecamatan', 100);
            $table->string('kabupaten', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10);
            
            // Hanya satu kolom untuk thumbnails
            $table->json('thumbnails')->nullable(); // Menyimpan array thumbnail
            
            $table->string('logo')->nullable();

            // Data kontak dan profile
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah')->nullable();

            // Informasi luas wilayah (hanya luas, batas dipindahkan)


            // Kolom-kolom batas wilayah dan potensi dihapus karena dipindah ke tabel batas_wilayah_potensi

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_desa');
    }
};