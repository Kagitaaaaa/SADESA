<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_informasi_id')
                ->constrained('kategori_informasis')
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('judul', 200);
            $table->string('slug', 200)->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->string('gambar_thumbnail')->nullable();
            $table->boolean('is_published')->default(true);
            $table->dateTime('tanggal_publish')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasis');
    }
};
