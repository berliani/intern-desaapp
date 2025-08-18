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
        Schema::create('verifikasi_penduduk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('penduduk_id')->nullable()->constrained('penduduk')->onDelete('set null');
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->string('nik', 16);
            $table->string('kk', 16);
            $table->foreignId('kepala_keluarga_id')->nullable()->constrained('penduduk');
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('rt_rw', 10);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin')->nullable();
            $table->string('agama')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->boolean('kepala_keluarga')->default(false);
            $table->string('pekerjaan', 100)->nullable();
            $table->string('pendidikan', 100)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_penduduk');
    }
};