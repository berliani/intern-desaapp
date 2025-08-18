<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_desa')->constrained('profil_desa');
            $table->foreignId('created_by')->constrained('users');
            $table->string('judul');
            $table->text('isi');
            $table->string('kategori', 50);
            $table->string('gambar');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};