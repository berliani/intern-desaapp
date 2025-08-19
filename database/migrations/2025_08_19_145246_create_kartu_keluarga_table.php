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
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('kartu_keluarga_company_id_foreign');
            $table->integer('id_desa')->index('kartu_keluarga_id_desa_foreign');
            $table->char('nomor_kk', 16)->unique();
            $table->string('alamat');
            $table->char('rt_rw', 7);
            $table->integer('kepala_keluarga_id')->nullable()->index('kartu_keluarga_kepala_keluarga_id_foreign');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga');
    }
};
