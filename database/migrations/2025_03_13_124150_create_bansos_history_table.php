<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bansos_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bansos_id')->constrained('bansos');
            $table->string('status_lama')->nullable();
            $table->string('status_baru');
            $table->text('keterangan')->nullable();
            $table->foreignId('diubah_oleh')->constrained('users');
            $table->timestamp('waktu_perubahan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bansos_history');
    }
};