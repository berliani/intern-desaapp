<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('layanan_desa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->foreignId('created_by')->constrained('users');

            // Informasi dasar layanan
            $table->string('kategori', 50);
            $table->string('nama_layanan', 100);
            $table->text('deskripsi');

            // Informasi biaya
            $table->bigInteger('biaya')->default(0);

            // Informasi lokasi dan jadwal (opsional untuk beberapa jenis layanan)
            $table->string('lokasi_layanan', 150)->nullable();
            $table->text('jadwal_pelayanan')->nullable();

            // Informasi kontak penanggung jawab (opsional)
            $table->string('kontak_layanan', 100)->nullable();

            // Persyaratan dan prosedur
            $table->json('persyaratan')->nullable();
            $table->json('prosedur')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan_desa');
    }
};