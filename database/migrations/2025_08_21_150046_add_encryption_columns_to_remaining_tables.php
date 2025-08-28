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
        // 1. Tabel profil_desa
        Schema::table('profil_desa', function (Blueprint $table) {
            if (!Schema::hasColumn('profil_desa', 'telepon_encrypted')) {
                $table->text('telepon_encrypted')->nullable()->after('telepon');
            }
            if (!Schema::hasColumn('profil_desa', 'telepon_search_hash')) {
                $table->string('telepon_search_hash', 64)->nullable()->index()->after('telepon_encrypted');
            }
            if (!Schema::hasColumn('profil_desa', 'email_encrypted')) {
                $table->text('email_encrypted')->nullable()->after('email');
            }
            if (!Schema::hasColumn('profil_desa', 'email_search_hash')) {
                $table->string('email_search_hash', 64)->nullable()->index()->after('email_encrypted');
            }
        });

        // 2. Tabel penduduk
        Schema::table('penduduk', function (Blueprint $table) {
            if (!Schema::hasColumn('penduduk', 'nik_encrypted')) {
                $table->text('nik_encrypted')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('penduduk', 'nik_search_hash')) {
                $table->string('nik_search_hash', 64)->nullable()->unique()->after('nik_encrypted');
            }
            if (!Schema::hasColumn('penduduk', 'nik_prefix_hash')) {
                $table->string('nik_prefix_hash', 64)->nullable()->index()->after('nik_search_hash');
            }
    
            if (!Schema::hasColumn('penduduk', 'kk_encrypted')) {
                $table->text('kk_encrypted')->nullable()->after('kk');
            }
            if (!Schema::hasColumn('penduduk', 'kk_search_hash')) {
                $table->string('kk_search_hash', 64)->nullable()->index()->after('kk_encrypted');
            }
            if (!Schema::hasColumn('penduduk', 'no_hp_encrypted')) {
                $table->text('no_hp_encrypted')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('penduduk', 'no_hp_search_hash')) {
                $table->string('no_hp_search_hash', 64)->nullable()->index()->after('no_hp_encrypted');
            }
            if (!Schema::hasColumn('penduduk', 'email_encrypted')) {
                $table->text('email_encrypted')->nullable()->after('email');
            }
            if (!Schema::hasColumn('penduduk', 'email_search_hash')) {
                $table->string('email_search_hash', 64)->nullable()->index()->after('email_encrypted');
            }
        });

        // 3. Tabel kartu_keluarga 
        Schema::table('kartu_keluarga', function (Blueprint $table) {
            if (!Schema::hasColumn('kartu_keluarga', 'nomor_kk_encrypted')) {
                $table->text('nomor_kk_encrypted')->nullable()->after('nomor_kk');
            }
            if (!Schema::hasColumn('kartu_keluarga', 'nomor_kk_search_hash')) {
                $table->string('nomor_kk_search_hash', 64)->nullable()->unique()->after('nomor_kk_encrypted');
            }
        });

        // 4. Tabel aparat_desa
        Schema::table('aparat_desa', function (Blueprint $table) {
            if (!Schema::hasColumn('aparat_desa', 'kontak_encrypted')) {
                $table->text('kontak_encrypted')->nullable()->after('kontak');
            }
            if (!Schema::hasColumn('aparat_desa', 'kontak_search_hash')) {
                $table->string('kontak_search_hash', 64)->nullable()->index()->after('kontak_encrypted');
            }
        });

        // 5. Tabel verifikasi_penduduk
        Schema::table('verifikasi_penduduk', function (Blueprint $table) {
            if (!Schema::hasColumn('verifikasi_penduduk', 'nik_encrypted')) {
                $table->text('nik_encrypted')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('verifikasi_penduduk', 'nik_search_hash')) {
                $table->string('nik_search_hash', 64)->nullable()->index()->after('nik_encrypted');
            }
            // PERBAIKAN: Menggunakan nama kolom 'kk' yang benar
            if (!Schema::hasColumn('verifikasi_penduduk', 'kk_encrypted')) {
                $table->text('kk_encrypted')->nullable()->after('kk');
            }
            if (!Schema::hasColumn('verifikasi_penduduk', 'kk_search_hash')) {
                $table->string('kk_search_hash', 64)->nullable()->index()->after('kk_encrypted');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Logika untuk membatalkan perubahan jika diperlukan
    }
};