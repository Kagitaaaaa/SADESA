<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->string('slug', 150)->unique();
            $table->string('kategori', 50)->default('Kegiatan');
            $table->date('tanggal_kegiatan');
            $table->string('lokasi', 150)->nullable();
            $table->string('cover_image')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
