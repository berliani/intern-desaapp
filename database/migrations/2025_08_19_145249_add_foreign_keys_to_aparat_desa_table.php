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
        Schema::table('aparat_desa', function (Blueprint $table) {
            $table->foreign(['company_id'])->references(['id'])->on('companies')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['struktur_pemerintahan_id'])->references(['id'])->on('struktur_pemerintahan')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aparat_desa', function (Blueprint $table) {
            $table->dropForeign('aparat_desa_company_id_foreign');
            $table->dropForeign('aparat_desa_struktur_pemerintahan_id_foreign');
        });
    }
};
