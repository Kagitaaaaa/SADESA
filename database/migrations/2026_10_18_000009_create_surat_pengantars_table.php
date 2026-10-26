<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_pengantars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 100)->nullable()->unique();
            $table->foreignId('warga_id')
                ->constrained('wargas')
                ->cascadeOnDelete();
            $table->foreignId('jenis_surat_id')
                ->constrained('jenis_surats')
                ->restrictOnDelete();
            $table->foreignId('diproses_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('ttd_aparatur_id')
                ->nullable()
                ->constrained('aparaturs')
                ->nullOnDelete();
            $table->text('keperluan');
            $table->json('data_isian')->nullable(); // Isian formulir khusus per jenis surat (usaha, tujuan, keterangan tambahan)
            $table->string('file_lampiran')->nullable(); // Berkas KTP/KK/Pengantar RT pendukung
            $table->string('file_surat_selesai')->nullable(); // Berkas PDF hasil generate / scan TTD
            $table->string('qr_token', 64)->nullable()->unique(); // Token verifikasi QR Code keabsahan surat
            $table->string('status', 30)->default('pending'); // pending, diproses, disetujui, ditolak, selesai
            $table->text('catatan_admin')->nullable();
            $table->dateTime('tanggal_pengajuan');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pengantars');
    }
};
