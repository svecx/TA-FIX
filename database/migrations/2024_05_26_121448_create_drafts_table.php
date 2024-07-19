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
        Schema::create('draft', function (Blueprint $table) {
            $table->id();
            $table->string('judul_dokumen');
            $table->text('deskripsi_dokumen');
            $table->string('kategori_dokumen');
            $table->string('validasi_dokumen');
            $table->date('tahun_dokumen');
            $table->string('dokumen_file');
            $table->string('tags')->nullable();
            $table->string('status')->default('draft');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft');
    }
};
