<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_surats', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 100)->default('Kategori A: Kependudukan & Catatan Sipil');
            $table->string('kode_surat', 30)->unique(); // 470, 472.11, 401, 503, 331, dll.
            $table->string('nama_surat', 150);
            $table->string('format_nomor', 100)->nullable(); // 470/{NOMOR}/DS-PBL/{BULAN}/{TAHUN}
            $table->text('deskripsi_kegunaan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('syarat_dokumen')->nullable();
            $table->json('syarat_tambahan')->nullable(); // Konfigurasi JSON daftar upload syarat berkas khusus
            $table->json('form_input_tambahan')->nullable(); // Konfigurasi JSON field input dinamis khusus
            $table->text('template_narasi')->nullable(); // Narasi isi surat resmi
            $table->text('template_surat')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_surats');
    }
};
