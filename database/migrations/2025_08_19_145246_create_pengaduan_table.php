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
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('pengaduan_company_id_foreign');
            $table->integer('id_desa')->index('pengaduan_id_desa_foreign');
            $table->integer('penduduk_id')->index('pengaduan_penduduk_id_foreign');
            $table->string('judul');
            $table->string('kategori', 50);
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->nullable();
            $table->text('deskripsi');
            $table->string('foto')->nullable();
            $table->string('status', 50)->default('Belum Ditangani');
            $table->boolean('is_public')->default(true);
            $table->text('tanggapan')->nullable();
            $table->integer('ditangani_oleh')->nullable()->index('pengaduan_ditangani_oleh_foreign');
            $table->timestamp('tanggal_tanggapan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
