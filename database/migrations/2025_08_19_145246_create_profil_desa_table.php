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
        Schema::create('profil_desa', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('company_id')->nullable()->index('profil_desa_company_id_foreign');
            $table->integer('created_by')->index('profil_desa_created_by_foreign');
            $table->string('nama_desa', 100);
            $table->string('kecamatan', 50);
            $table->string('kabupaten', 50);
            $table->string('provinsi', 50);
            $table->string('kode_pos', 5);
            $table->string('thumbnails')->nullable();
            $table->string('logo')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon', 16)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_desa');
    }
};
