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
        Schema::create('berita', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('berita_company_id_foreign');
            $table->integer('id_desa')->index('berita_id_desa_foreign');
            $table->integer('created_by')->index('berita_created_by_foreign');
            $table->string('judul');
            $table->text('isi');
            $table->string('kategori', 50);
            $table->string('gambar');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
