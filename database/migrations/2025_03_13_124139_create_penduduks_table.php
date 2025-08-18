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
        // Nonaktifkan foreign key constraints sementara
        Schema::disableForeignKeyConstraints();

        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->string('nik', 16)->unique();
            $table->string('kk', 16);
            $table->foreignId('kepala_keluarga_id')->nullable()->constrained('penduduk')->nullOnDelete();
            $table->string('nama', 100);
            $table->text('alamat');
            $table->string('rt_rw', 10);
            $table->string('desa_kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kabupaten', 100)->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir');
            $table->string('jenis_kelamin')->nullable();
            $table->string('agama')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->boolean('kepala_keluarga')->default(false);
            $table->string('pekerjaan', 100)->nullable();
            $table->string('pendidikan', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        });

        // Aktifkan kembali foreign key constraints
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};