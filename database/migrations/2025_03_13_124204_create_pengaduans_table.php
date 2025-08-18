<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->foreignId('penduduk_id')->constrained('penduduk');
            $table->string('judul');
            $table->string('kategori');
            $table->string('prioritas')->default('Sedang');
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->string('status')->default('Belum Ditangani');
            $table->boolean('is_public')->default(true);
            $table->text('tanggapan')->nullable();
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users');
            $table->timestamp('tanggal_tanggapan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};