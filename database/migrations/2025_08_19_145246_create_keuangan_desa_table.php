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
        Schema::create('keuangan_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('keuangan_desa_company_id_foreign');
            $table->integer('id_desa');
            $table->integer('created_by')->index('keuangan_desa_created_by_foreign');
            $table->enum('jenis', ['Pemasukan', 'Pengeluaran'])->index();
            $table->string('deskripsi');
            $table->integer('jumlah');
            $table->date('tanggal')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['id_desa', 'jenis']);
            $table->index(['jenis', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_desa');
    }
};
