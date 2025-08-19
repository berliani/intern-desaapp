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
        Schema::create('umkm', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('umkm_company_id_foreign');
            $table->integer('id_desa')->index('umkm_id_desa_foreign');
            $table->integer('penduduk_id')->index('umkm_penduduk_id_foreign');
            $table->string('nama_usaha', 30);
            $table->string('produk', 30);
            $table->string('kontak_whatsapp', 16);
            $table->string('lokasi', 100)->nullable();
            $table->string('deskripsi')->nullable();
            $table->string('kategori', 100);
            $table->integer('is_verified')->default(0);
            $table->string('foto_usaha')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};
