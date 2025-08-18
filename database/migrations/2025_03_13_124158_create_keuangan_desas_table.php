<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('keuangan_desa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->foreignId('created_by')->constrained('users');
            $table->string('jenis', 20);
            $table->text('deskripsi');
            $table->bigInteger('jumlah');
            $table->date('tanggal');
            $table->timestamps();
            $table->softDeletes(); // Tambahkan soft deletes
        });
        Schema::enableForeignKeyConstraints();

        Schema::table('keuangan_desa', function (Blueprint $table) {
            // Tambahkan index untuk meningkatkan performa query
            $table->index('jenis');
            $table->index('tanggal');
            $table->index(['id_desa', 'jenis']);
            $table->index(['jenis', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan_desa');

        Schema::table('keuangan_desa', function (Blueprint $table) {
            $table->dropIndex(['jenis']);
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['id_desa', 'jenis']);
            $table->dropIndex(['jenis', 'tanggal']);
        });
    }
};