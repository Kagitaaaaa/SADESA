<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aparaturs', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('jabatan', 100); // Kepala Desa, Sekdes, Kasi, Kaur, Kadus
            $table->string('nip_niap', 50)->nullable();
            $table->string('tempat_lahir', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('no_sk', 100)->nullable();
            $table->text('tupoksi')->nullable(); // Tugas Pokok & Fungsi
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0); // Urutan posisi di struktur bagan
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aparaturs');
    }
};
