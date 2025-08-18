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
        Schema::create('struktur_pemerintahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profil_desa_id')->constrained('profil_desa')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            
            // Sambutan kepala desa
            $table->text('sambutan_kepala_desa')->nullable();
            $table->string('foto_kepala_desa')->nullable();
            $table->string('nama_kepala_desa')->nullable();
            $table->string('periode_jabatan')->nullable();
            
            // Ganti dengan program kerja kepala desa (tidak duplikat dengan visi misi desa)
            $table->text('program_kerja')->nullable();
            $table->text('prioritas_program')->nullable();
            
            // Bagan struktur (gambar)
            $table->string('bagan_struktur')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Tabel untuk menyimpan detail aparat desa
        Schema::create('aparat_desa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('struktur_pemerintahan_id')->constrained('struktur_pemerintahan')->onDelete('cascade');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('foto')->nullable();
            $table->string('pendidikan')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->string('periode_jabatan')->nullable();
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
        Schema::dropIfExists('struktur_pemerintahan');
    }
}; 