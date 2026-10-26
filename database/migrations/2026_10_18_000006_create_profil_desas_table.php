<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profil_desas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa', 100);
            $table->string('kode_desa', 30)->nullable();
            $table->string('kecamatan', 100);
            $table->string('kabupaten', 100);
            $table->string('provinsi', 100);
            $table->string('kode_pos', 10)->nullable();
            $table->text('alamat_kantor');
            $table->string('email_desa', 100)->nullable();
            $table->string('telepon_desa', 25)->nullable();
            $table->string('website_url', 150)->nullable();
            $table->string('logo_desa')->nullable();       // Logo instansi desa
            $table->string('lambang_daerah')->nullable();   // Lambang kabupaten untuk Kop Surat
            $table->string('nama_kepala_desa', 100);
            $table->string('nip_kepala_desa', 50)->nullable();
            $table->text('sambutan_kades')->nullable();
            $table->string('foto_kades')->nullable();
            $table->longText('sejarah')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->longText('gambaran_wilayah')->nullable();
            $table->string('batas_utara', 100)->nullable();
            $table->string('batas_selatan', 100)->nullable();
            $table->string('batas_timur', 100)->nullable();
            $table->string('batas_barat', 100)->nullable();
            $table->string('luas_wilayah', 50)->nullable();
            $table->text('peta_embed')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_desas');
    }
};
