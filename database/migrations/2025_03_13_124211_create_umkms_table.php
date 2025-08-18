<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->foreignId('penduduk_id')->constrained('penduduk');
            $table->string('nama_usaha', 255);
            $table->string('produk', 255);
            $table->string('kontak_whatsapp', 15);
            $table->string('lokasi', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('kategori')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('foto_usaha')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};
