<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('bansos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->foreignId('penduduk_id')->constrained('penduduk');
            $table->foreignId('jenis_bansos_id')->constrained('jenis_bansos');

            // Status dengan tambahan 'Dalam Verifikasi'
            $table->enum('status', ['Diajukan', 'Dalam Verifikasi', 'Diverifikasi', 'Disetujui', 'Ditolak', 'Sudah Diterima', 'Dibatalkan']);

            // Kolom prioritas untuk menunjukkan tingkat kemendesakan
            $table->enum('prioritas', ['Tinggi', 'Sedang', 'Rendah'])->default('Sedang');

            // Sumber pengajuan (admin atau warga)
            $table->enum('sumber_pengajuan', ['admin', 'warga'])->default('admin');

            // Timestamp untuk tracking proses
            $table->timestamp('tanggal_pengajuan');
            $table->timestamp('tanggal_penerimaan')->nullable();
            $table->timestamp('tenggat_pengambilan')->nullable(); // Batas waktu pengambilan bantuan
            $table->string('lokasi_pengambilan')->nullable(); // Lokasi pengambilan bantuan

            // Alasan dan keterangan
            $table->text('alasan_pengajuan'); // Alasan pengajuan bantuan - dibuat required
            $table->text('keterangan')->nullable(); // Keterangan umum (termasuk catatan verifikasi)

            // Dokumen dan bukti
            $table->string('dokumen_pendukung', 255)->nullable(); // Path ke dokumen pendukung
            $table->string('bukti_penerimaan', 255)->nullable(); // Path ke bukti penerimaan
            $table->string('foto_rumah', 255)->nullable(); // Path ke foto rumah

            // Referensi petugas (disederhanakan)
            $table->foreignId('diubah_oleh')->nullable()->constrained('users'); // Petugas yang terakhir mengubah

            // Status dan penanda
            $table->boolean('notifikasi_terkirim')->default(false); // Status notifikasi
            $table->boolean('is_urgent')->default(false); // Penanda kasus mendesak

            // Indeks untuk meningkatkan performa query
            $table->index(['id_desa', 'penduduk_id']);
            $table->index(['jenis_bansos_id']);
            $table->index(['status']);
            $table->index(['tanggal_pengajuan']);
            $table->index(['is_urgent']);

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('bansos');
    }
};