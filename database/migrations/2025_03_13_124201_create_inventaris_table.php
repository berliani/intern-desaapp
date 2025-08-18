<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->foreignId('created_by')->constrained('users');

            // Informasi dasar barang
            $table->string('kode_barang', 50)->unique(); // Kode otomatis
            $table->string('nama_barang', 255);
            $table->string('kategori', 50); // Elektronik, Furnitur, ATK, dll
            $table->integer('jumlah');
            $table->enum('kondisi', ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang']);

            // Informasi pengadaan dan lokasi
            $table->date('tanggal_perolehan')->nullable();
            $table->bigInteger('nominal_harga')->default(0); // Tetap gunakan bigInteger untuk nilai uang
            $table->string('sumber_dana', 100)->nullable(); // APBD, Hibah, dll
            $table->string('lokasi', 150)->nullable(); // Di mana barang disimpan

            // Status dan keterangan
            $table->string('status', 50)->default('Tersedia'); // Tersedia, Dipinjam, Dalam Perbaikan
            $table->text('keterangan')->nullable();
            $table->string('foto', 255)->nullable(); // Path ke foto

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};