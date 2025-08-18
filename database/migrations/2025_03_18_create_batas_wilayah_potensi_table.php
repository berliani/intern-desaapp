<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batas_wilayah_potensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profil_desa_id')->constrained('profil_desa');
            $table->foreignId('created_by')->constrained('users');

            // Informasi Wilayah
            $table->float('luas_wilayah')->nullable();
            $table->string('batas_utara')->nullable();
            $table->string('batas_timur')->nullable();
            $table->string('batas_selatan')->nullable();
            $table->string('batas_barat')->nullable();
            $table->text('keterangan_batas')->nullable();

            // Potensi Desa
            $table->json('potensi_desa')->nullable();
            $table->text('keterangan_potensi')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batas_wilayah_potensi');
    }
};