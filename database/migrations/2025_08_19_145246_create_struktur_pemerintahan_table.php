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
        Schema::create('struktur_pemerintahan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('struktur_pemerintahan_company_id_foreign');
            $table->integer('profil_desa_id')->index('struktur_pemerintahan_profil_desa_id_foreign');
            $table->integer('created_by')->index('struktur_pemerintahan_created_by_foreign');
            $table->text('sambutan_kepala_desa')->nullable();
            $table->string('foto_kepala_desa')->nullable();
            $table->string('nama_kepala_desa', 100)->nullable();
            $table->string('periode_jabatan', 9)->nullable();
            $table->text('program_kerja')->nullable();
            $table->text('prioritas_program')->nullable();
            $table->string('bagan_struktur')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struktur_pemerintahan');
    }
};
