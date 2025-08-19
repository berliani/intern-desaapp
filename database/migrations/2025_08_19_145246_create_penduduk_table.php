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
        Schema::create('penduduk', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('penduduk_company_id_foreign');
            $table->integer('id_desa')->index('penduduk_id_desa_foreign');
            $table->char('nik', 16)->unique();
            $table->char('kk', 16);
            $table->string('rt_rw', 7);
            $table->string('desa_kelurahan', 30);
            $table->string('kecamatan', 50);
            $table->string('kabupaten', 50);
            $table->integer('kepala_keluarga_id')->nullable()->index('penduduk_kepala_keluarga_id_foreign');
            $table->string('nama', 100);
            $table->string('alamat', 100);
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir', 30);
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])->nullable();
            $table->enum('status_perkawinan', ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'])->nullable();
            $table->boolean('kepala_keluarga')->default(false);
            $table->string('pekerjaan', 100)->nullable();
            $table->enum('pendidikan', ['Tidak Sekolah', 'Belum Sekolah', 'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3'])->nullable();
            $table->string('no_hp', 16)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('golongan_darah', ['A', 'A-', 'B', 'B-', 'AB', 'AB-', 'O', 'O-', '-', 'Tidak Tahu'])->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('user_id')->nullable()->index('penduduk_user_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
