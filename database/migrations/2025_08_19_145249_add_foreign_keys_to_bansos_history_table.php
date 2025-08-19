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
        Schema::table('bansos_history', function (Blueprint $table) {
            $table->foreign(['company_id'], 'fk_bansos_history_company')->references(['id'])->on('companies')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bansos_history', function (Blueprint $table) {
            $table->dropForeign('fk_bansos_history_company');
        });
    }
};
