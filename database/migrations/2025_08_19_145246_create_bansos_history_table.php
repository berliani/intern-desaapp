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
        Schema::create('bansos_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('bansos_id')->index('bansos_history_bansos_id_foreign');
            $table->enum('status_lama', ['Diajukan', 'Dalam Verifikasi', 'Diverifikasi', 'Disetujui', 'Ditolak', 'Sudah Diterima', 'Dibatalkan'])->nullable();
            $table->enum('status_baru', ['Diajukan', 'Dalam Verifikasi', 'Diverifikasi', 'Disetujui', 'Ditolak', 'Sudah Diterima', 'Dibatalkan']);
            $table->string('keterangan')->nullable();
            $table->integer('diubah_oleh')->index('bansos_history_diubah_oleh_foreign');
            $table->integer('company_id')->nullable()->index('fk_bansos_history_company');
            $table->timestamp('waktu_perubahan')->useCurrentOnUpdate()->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bansos_history');
    }
};
