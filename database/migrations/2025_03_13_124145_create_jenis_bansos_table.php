<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_bansos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bansos', 100);
            $table->text('deskripsi')->nullable();
            $table->string('instansi_pemberi', 100)->nullable(); // Kementerian Sosial, BPJS, Pemda, dll
            $table->string('kategori', 50); // Sembako, Tunai, Kesehatan, Pendidikan, dll
            $table->string('periode', 50)->nullable(); // Bulanan, Tahunan, Sekali, dll
            $table->string('bentuk_bantuan', 50)->nullable(); // Uang, Barang, Jasa, Voucher, dll
            $table->decimal('jumlah_per_penerima', 10, 2)->nullable(); // Jumlah/nilai bantuan per penerima
            $table->string('satuan', 50)->nullable(); // Kg, Paket, Unit, Liter, dll
            $table->bigInteger('nominal_standar')->default(0); // Nilai standar bantuan dalam rupiah
            $table->boolean('is_active')->default(true); // Status aktif/tidak aktif
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_bansos');
    }
};