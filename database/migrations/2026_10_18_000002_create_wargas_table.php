<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wargas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kartu_keluarga_id')
                ->constrained('kartu_keluargas')
                ->cascadeOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('jenis_kelamin', 20); // Laki-laki / Perempuan
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->string('agama', 30);
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('pekerjaan', 50)->nullable();
            $table->string('status_perkawinan', 30);
            $table->string('status_hubungan_keluarga', 50);
            $table->string('status_warga', 20)->default('Aktif'); // Aktif / Pindah / Meninggal
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wargas');
    }
};
