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
        Schema::create('jenis_bansos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_bansos', 100);
            $table->string('deskripsi')->nullable();
            $table->string('instansi_pemberi', 100)->nullable();
            $table->string('kategori', 50);
            $table->string('periode', 50)->nullable();
            $table->string('bentuk_bantuan', 50)->nullable();
            $table->integer('jumlah_per_penerima')->nullable();
            $table->string('satuan', 50)->nullable();
            $table->integer('nominal_standar')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_bansos');
    }
};
