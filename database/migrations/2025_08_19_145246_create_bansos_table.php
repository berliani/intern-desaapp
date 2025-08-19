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
        Schema::create('bansos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('bansos_company_id_foreign');
            $table->integer('id_desa');
            $table->integer('penduduk_id')->index('bansos_penduduk_id_foreign');
            $table->integer('jenis_bansos_id')->index();
            $table->enum('status', ['Diajukan', 'Dalam Verifikasi', 'Diverifikasi', 'Disetujui', 'Ditolak', 'Sudah Diterima', 'Dibatalkan'])->index();
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->default('Sedang');
            $table->enum('sumber_pengajuan', ['admin', 'warga'])->default('admin');
            $table->timestamp('tanggal_pengajuan')->useCurrentOnUpdate()->useCurrent()->index();
            $table->timestamp('tanggal_penerimaan')->nullable();
            $table->timestamp('tenggat_pengambilan')->nullable();
            $table->string('lokasi_pengambilan', 100)->nullable();
            $table->string('alasan_pengajuan');
            $table->string('keterangan')->nullable();
            $table->string('dokumen_pendukung')->nullable();
            $table->string('bukti_penerimaan')->nullable();
            $table->string('foto_rumah')->nullable();
            $table->integer('diubah_oleh')->nullable()->index('bansos_diubah_oleh_foreign');
            $table->boolean('notifikasi_terkirim')->default(false);
            $table->boolean('is_urgent')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id_desa', 'penduduk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bansos');
    }
};
