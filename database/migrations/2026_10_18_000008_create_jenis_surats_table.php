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
            $table->string('kode_surat', 30)->unique(); // SKU, SKTM, SK_DOMISILI, SK_PENGANTAR_SKCK, dll.
            $table->string('nama_surat', 100);
            $table->string('format_nomor', 100)->nullable(); // 470/{NOMOR}/KJW/{BULAN}/{TAHUN}
            $table->text('deskripsi')->nullable();
            $table->text('syarat_dokumen')->nullable();
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
