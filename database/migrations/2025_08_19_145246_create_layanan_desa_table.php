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
        Schema::create('layanan_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('layanan_desa_company_id_foreign');
            $table->integer('id_desa')->index('layanan_desa_id_desa_foreign');
            $table->integer('created_by')->index('layanan_desa_created_by_foreign');
            $table->string('kategori', 30);
            $table->string('nama_layanan', 100);
            $table->text('deskripsi');
            $table->decimal('biaya', 10)->default(0);
            $table->string('lokasi_layanan', 50)->nullable();
            $table->string('jadwal_pelayanan', 50)->nullable();
            $table->string('kontak_layanan', 50)->nullable();
            $table->text('persyaratan')->nullable();
            $table->text('prosedur')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_desa');
    }
};
