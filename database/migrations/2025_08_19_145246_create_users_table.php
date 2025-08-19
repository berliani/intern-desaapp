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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('fk_users_company');
            $table->string('name', 100);
            $table->string('email', 100)->unique('email');
            $table->char('nik', 16)->nullable()->unique('nik');
            $table->integer('penduduk_id')->nullable()->index('users_penduduk_id_foreign');
            $table->timestamp('email_verified_at')->nullable();
            $table->char('password', 60);
            $table->string('profile_photo_path')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->unique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
