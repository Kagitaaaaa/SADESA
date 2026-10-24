<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Master Kartu Keluarga
        Schema::create('kartu_keluargas', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16)->unique();
            $table->string('kepala_keluarga', 100);
            $table->text('alamat');
            $table->string('dusun', 50);
            $table->string('rt', 5);
            $table->string('rw', 5);
            $table->string('kode_pos', 10)->nullable();
            $table->timestamps();
        });

        // 2. Master Warga / Penduduk
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

        // 3. Autentikasi Pengguna & Hak Akses
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warga_id')
                ->nullable()
                ->unique()
                ->constrained('wargas')
                ->nullOnDelete();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('role', 20)->default('warga'); // admin, kades, sekdes, staf, warga
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 4. Profil Desa (Untuk Halaman About Desa & Sumber Kop Surat Otomatis)
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

        // 5. Struktur Aparatur / Perangkat Desa (Untuk Halaman About & Penandatangan Surat)
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

        // 6. Master Jenis Surat
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

        // 7. Transaksi Pengajuan Surat Pengantar (Layanan E-Surat Ber-Kop Otomatis)
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

        // 8. Pengaduan & Aspirasi Warga
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

        // 9. Master Kategori Informasi
        Schema::create('kategori_informasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->string('slug', 100)->unique();
            $table->timestamps();
        });

        // 10. Konten Berita & Pengumuman
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

        // 11. Agenda Kegiatan Desa
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_agenda', 150);
            $table->string('slug', 150)->unique();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi', 150);
            $table->string('koordinator', 100)->nullable();
            $table->string('poster')->nullable();
            $table->string('status', 30)->default('Akan Datang');
            $table->timestamps();
        });

        // 12. Dokumentasi Galeri Kegiatan / Acara Desa
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

        // 13. Foto Item Dokumentasi Galeri
        Schema::create('galeri_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('galeri_id')
                ->constrained('galeris')
                ->cascadeOnDelete();
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });

        // 14. Modul Tutorial & Panduan Warga
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

        // Tabel bawaan Laravel Auth
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('panduans');
        Schema::dropIfExists('galeri_fotos');
        Schema::dropIfExists('galeris');
        Schema::dropIfExists('agendas');
        Schema::dropIfExists('informasis');
        Schema::dropIfExists('kategori_informasis');
        Schema::dropIfExists('pengaduans');
        Schema::dropIfExists('surat_pengantars');
        Schema::dropIfExists('jenis_surats');
        Schema::dropIfExists('aparaturs');
        Schema::dropIfExists('profil_desas');
        Schema::dropIfExists('users');
        Schema::dropIfExists('wargas');
        Schema::dropIfExists('kartu_keluargas');
    }
};
