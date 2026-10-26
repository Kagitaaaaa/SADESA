<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panduans', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->string('slug', 150)->unique();
            $table->string('kategori', 50); // pengajuan_surat, pengaduan, kependudukan, umum
            $table->text('ringkasan')->nullable();
            $table->longText('konten'); // Langkah-langkah penjelasan alur
            $table->string('ikon_atau_gambar')->nullable();
            $table->string('file_unduhan')->nullable(); // Misal form offline/lampiran PDF
            $table->integer('urutan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panduans');
    }
};
