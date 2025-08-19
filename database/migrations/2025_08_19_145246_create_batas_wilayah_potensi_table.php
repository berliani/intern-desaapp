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
        Schema::create('batas_wilayah_potensi', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('batas_wilayah_potensi_company_id_foreign');
            $table->integer('profil_desa_id')->index('batas_wilayah_potensi_profil_desa_id_foreign');
            $table->integer('created_by')->index('batas_wilayah_potensi_created_by_foreign');
            $table->integer('luas_wilayah')->nullable();
            $table->string('batas_utara', 100)->nullable();
            $table->string('batas_timur', 100)->nullable();
            $table->string('batas_selatan', 100)->nullable();
            $table->string('batas_barat', 100)->nullable();
            $table->string('keterangan_batas')->nullable();
            $table->json('potensi_desa')->nullable();
            $table->string('keterangan_potensi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batas_wilayah_potensi');
    }
};
