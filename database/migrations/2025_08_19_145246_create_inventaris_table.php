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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('inventaris_company_id_foreign');
            $table->integer('id_desa')->index('inventaris_id_desa_foreign');
            $table->integer('created_by')->index('inventaris_created_by_foreign');
            $table->string('kode_barang', 25)->unique();
            $table->string('nama_barang', 100);
            $table->string('kategori', 30);
            $table->integer('jumlah');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang']);
            $table->date('tanggal_perolehan')->nullable();
            $table->integer('nominal_harga')->default(0);
            $table->string('sumber_dana', 30)->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Dalam Perbaikan', 'Rusak Berat', 'Rusak', 'Hilang'])->default('Tersedia');
            $table->string('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
