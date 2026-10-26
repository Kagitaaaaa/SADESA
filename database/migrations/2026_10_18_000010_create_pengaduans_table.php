<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengaduan', 30)->unique(); // Contoh: ADU-202610-001
            $table->foreignId('warga_id')
                ->nullable()
                ->constrained('wargas')
                ->nullOnDelete();
            $table->string('nama_pelapor', 100);
            $table->string('no_hp', 25);
            $table->string('kategori', 50); // Pelayanan, Infrastruktur, Keamanan, Bansos, Lingkungan, Lainnya
            $table->string('judul_pengaduan', 150);
            $table->text('isi_pengaduan');
            $table->string('foto_bukti')->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->string('status', 30)->default('menunggu'); // menunggu, diproses, selesai, ditolak
            $table->text('tanggapan')->nullable();
            $table->foreignId('ditanggapi_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->dateTime('tanggal_tanggapan')->nullable();
            $table->boolean('is_anonim')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
