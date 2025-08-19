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
        Schema::create('aparat_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('aparat_desa_company_id_foreign');
            $table->integer('struktur_pemerintahan_id')->index('aparat_desa_struktur_pemerintahan_id_foreign');
            $table->string('nama', 100);
            $table->string('jabatan', 100);
            $table->string('foto')->nullable();
            $table->enum('pendidikan', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4/S1', 'S2', 'S3'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat', 100)->nullable();
            $table->string('kontak', 16)->nullable();
            $table->string('periode_jabatan', 9)->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aparat_desa');
    }
};
