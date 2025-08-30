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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->string('email_encrypted')->nullable();
            $table->char('email_search_hash', 64)->nullable()->unique();
            $table->string('telepon_encrypted')->nullable();
            $table->char('telepon_search_hash', 64)->nullable()->unique();
            $table->string('nik_encrypted')->nullable();
            $table->char('nik_search_hash', 64)->nullable()->unique()->comment('Hash untuk pencarian NIK lengkap');
            $table->char('nik_prefix_hash', 64)->nullable()->comment('Hash untuk pencarian 8 digit pertama NIK');
            $table->foreignId('penduduk_id')->nullable()->constrained('penduduk')->nullOnDelete();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('profile_photo_path')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('username', 20)->nullable(); // Menyesuaikan dengan gambar Anda
        });

        // Aktifkan kembali foreign key constraints
        Schema::enableForeignKeyConstraints();

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
