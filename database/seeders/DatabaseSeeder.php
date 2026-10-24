<?php

namespace Database\Seeders;

use App\Models\Aparatur;
use App\Models\JenisSurat;
use App\Models\KartuKeluarga;
use App\Models\KategoriInformasi;
use App\Models\Panduan;
use App\Models\ProfilDesa;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Profil Desa (Sumber data Kop Surat & Halaman About)
        ProfilDesa::firstOrCreate(['nama_desa' => 'Kejawar'], [
            'kode_desa' => '33.02.14.2001',
            'kecamatan' => 'Banyumas',
            'kabupaten' => 'Banyumas',
            'provinsi' => 'Jawa Tengah',
            'kode_pos' => '53192',
            'alamat_kantor' => 'Jl. Kejawar No. 01, Desa Kejawar, Kec. Banyumas',
            'email_desa' => 'pemdes@kejawar.desa.id',
            'telepon_desa' => '0281-796123',
            'website_url' => 'https://kejawar.desa.id',
            'logo_desa' => 'images/logo-desa.png',
            'lambang_daerah' => 'images/lambang-kab-banyumas.png',
            'nama_kepala_desa' => 'Sujatno, S.Sos.',
            'nip_kepala_desa' => '19750512 200212 1 003',
            'sambutan_kades' => 'Selamat datang di Sistem Administrasi dan Informasi Desa (SADESA). Layanan ini hadir untuk memberikan kemudahan pelayanan persuratan dan keterbukaan informasi bagi seluruh warga desa.',
            'sejarah' => 'Desa Kejawar memiliki sejarah panjang yang berakar dari masa Kadipaten Banyumas. Nama Kejawar konon berasal dari pohon Jawaru yang banyak tumbuh di tepian sungai Serayu...',
            'visi' => 'Terwujudnya tata kelola pemerintahan desa yang transparan, akuntabel, dan pelayanan masyarakat berbasis digital menuju desa yang mandiri dan sejahtera.',
            'misi' => "1. Meningkatkan pelayanan publik yang cepat, mudah, dan transparan.\n2. Mengembangkan sarana prasarana desa berbasis kebutuhan masyarakat.\n3. Memberdayakan ekonomi warga dan UMKM desa.",
            'gambaran_wilayah' => 'Desa Kejawar terletak di Kecamatan Banyumas dengan topografi dataran rendah dan perbukitan agraris, dilewati oleh aliran Sungai Serayu.',
            'batas_utara' => 'Desa Danaraja',
            'batas_selatan' => 'Kecamatan Somagede',
            'batas_timur' => 'Desa Sudagaran',
            'batas_barat' => 'Sungai Serayu',
            'luas_wilayah' => '342,5 Hektar',
            'peta_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15822.868779836928!2d109.28!3d-7.52!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6544a0e3!2sKejawar!5e0!3m2!1sid!2sid!4v1700000000000" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
        ]);

        // 2. Aparatur Pemerintahan Desa
        $kadesAparatur = Aparatur::firstOrCreate(['nama' => 'Sujatno, S.Sos.'], [
            'jabatan' => 'Kepala Desa',
            'nip_niap' => '19750512 200212 1 003',
            'pendidikan_terakhir' => 'S1 Ilmu Administrasi Negara',
            'no_sk' => '141/05/SK/2021',
            'tupoksi' => 'Memimpin penyelenggaraan pemerintahan desa, pembinaan kemasyarakatan, dan pemberdayaan masyarakat desa.',
            'urutan' => 1,
            'status_aktif' => true,
        ]);

        Aparatur::firstOrCreate(['nama' => 'Bambang Irawan, S.Kom.'], [
            'jabatan' => 'Sekretaris Desa',
            'nip_niap' => '19820815 200903 1 005',
            'pendidikan_terakhir' => 'S1 Teknik Informatika',
            'no_sk' => '141/08/SK/2021',
            'tupoksi' => 'Membantu Kepala Desa dalam bidang administrasi pemerintahan dan pelayanan surat-menyurat kependudukan.',
            'urutan' => 2,
            'status_aktif' => true,
        ]);

        // 3. Master Kartu Keluarga & Warga
        $kk = KartuKeluarga::firstOrCreate(['no_kk' => '3302141505080001'], [
            'kepala_keluarga' => 'Budi Santoso',
            'alamat' => 'Jl. Mawar RT 02 RW 01 Dusun Krajan',
            'dusun' => 'Dusun Krajan',
            'rt' => '02',
            'rw' => '01',
            'kode_pos' => '53192',
        ]);

        $wargaBudi = Warga::firstOrCreate(['nik' => '3302141505850001'], [
            'kartu_keluarga_id' => $kk->id,
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Banyumas',
            'tanggal_lahir' => '1985-05-15',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'SMA/Sederajat',
            'pekerjaan' => 'Wiraswasta',
            'status_perkawinan' => 'Kawin',
            'status_hubungan_keluarga' => 'Kepala Keluarga',
            'status_warga' => 'Aktif',
            'no_hp' => '081234567890',
        ]);

        // 4. Akun Pengguna (Users)
        User::firstOrCreate(['email' => 'admin@sadesa.id'], [
            'name' => 'Administrator Desa',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::firstOrCreate(['email' => 'kades@sadesa.id'], [
            'name' => 'Sujatno, S.Sos. (Kades)',
            'password' => Hash::make('password'),
            'role' => 'kades',
        ]);

        User::firstOrCreate(['email' => 'budi@warga.id'], [
            'warga_id' => $wargaBudi->id,
            'name' => 'Budi Santoso',
            'password' => Hash::make('password'),
            'role' => 'warga',
        ]);

        // 5. Master Jenis Surat Layanan Warga
        JenisSurat::firstOrCreate(['kode_surat' => 'SKU'], [
            'nama_surat' => 'Surat Keterangan Usaha (SKU)',
            'format_nomor' => '510/{NOMOR}/DS-KJW/{BULAN}/{TAHUN}',
            'deskripsi' => 'Surat keterangan resmi dari desa yang menerangkan bahwa warga memiliki dan menjalankan usaha tertentu.',
            'syarat_dokumen' => "1. Foto KTP Pemohon\n2. Foto Kartu Keluarga\n3. Foto tempat usaha",
            'template_surat' => 'surat.templates.sku',
            'is_aktif' => true,
        ]);

        JenisSurat::firstOrCreate(['kode_surat' => 'SKTM'], [
            'nama_surat' => 'Surat Keterangan Tidak Mampu (SKTM)',
            'format_nomor' => '401/{NOMOR}/DS-KJW/{BULAN}/{TAHUN}',
            'deskripsi' => 'Surat keterangan untuk keperluan bantuan sosial, beasiswa pendidikan, atau keringanan biaya kesehatan/RS.',
            'syarat_dokumen' => "1. Foto KTP Pemohon\n2. Foto Kartu Keluarga\n3. Surat Pengantar RT/RW",
            'template_surat' => 'surat.templates.sktm',
            'is_aktif' => true,
        ]);

        JenisSurat::firstOrCreate(['kode_surat' => 'SK_DOMISILI'], [
            'nama_surat' => 'Surat Keterangan Domisili',
            'format_nomor' => '470/{NOMOR}/DS-KJW/{BULAN}/{TAHUN}',
            'deskripsi' => 'Surat keterangan yang menerangkan bahwa pemohon bertempat tinggal di wilayah Desa Kejawar.',
            'syarat_dokumen' => "1. Foto KTP Pemohon\n2. Foto Kartu Keluarga",
            'template_surat' => 'surat.templates.domisili',
            'is_aktif' => true,
        ]);

        JenisSurat::firstOrCreate(['kode_surat' => 'SK_PENGANTAR_SKCK'], [
            'nama_surat' => 'Surat Pengantar SKCK',
            'format_nomor' => '331/{NOMOR}/DS-KJW/{BULAN}/{TAHUN}',
            'deskripsi' => 'Surat pengantar kelakuan baik dari desa untuk pembuatan Surat Keterangan Catatan Kepolisian di Polsek/Polres.',
            'syarat_dokumen' => "1. Foto KTP Pemohon\n2. Foto Kartu Keluarga\n3. Pas foto 4x6 latar merah",
            'template_surat' => 'surat.templates.pengantar-skck',
            'is_aktif' => true,
        ]);

        // 6. Master Kategori Informasi
        $katBerita = KategoriInformasi::firstOrCreate(['slug' => 'berita-desa'], ['nama_kategori' => 'Berita Desa']);
        KategoriInformasi::firstOrCreate(['slug' => 'pengumuman'], ['nama_kategori' => 'Pengumuman Resmi']);
        KategoriInformasi::firstOrCreate(['slug' => 'pembangunan'], ['nama_kategori' => 'Transparansi Pembangunan']);
        KategoriInformasi::firstOrCreate(['slug' => 'kegiatan-warga'], ['nama_kategori' => 'Kegiatan Warga & Pemuda']);

        // 7. Modul Panduan & Tutorial
        Panduan::firstOrCreate(['slug' => 'panduan-pengajuan-surat-online'], [
            'judul' => 'Panduan Alur Pengajuan Surat Pengantar Online',
            'kategori' => 'pengajuan_surat',
            'ringkasan' => 'Langkah praktis mengajukan surat pengantar resmi tanpa harus antre lama di kantor desa.',
            'konten' => "1. Pastikan Anda telah memiliki akun warga dengan NIK yang terdaftar.\n2. Masuk (Login) ke Portal SADESA.\n3. Pilih menu 'Layanan Surat' -> 'Ajukan Surat Baru'.\n4. Pilih jenis surat yang dibutuhkan (misal: SKU, SKTM, Pengantar SKCK).\n5. Lengkapi formulir isian data tambahan dan unggah berkas syarat (KTP/KK).\n6. Klik tombol 'Kirim Pengajuan'. Pantau status surat di menu Riwayat Surat.\n7. Setelah disetujui admin/kades, surat dengan Kop Resmi dan QR Verifikasi dapat diunduh (PDF) atau diambil di kantor desa.",
            'urutan' => 1,
            'is_aktif' => true,
        ]);

        Panduan::firstOrCreate(['slug' => 'panduan-sampaikan-pengaduan-aspirasi'], [
            'judul' => 'Panduan Menyampaikan Pengaduan & Aspirasi Warga',
            'kategori' => 'pengaduan',
            'ringkasan' => 'Cara melaporkan keluhan infrastruktur, pelayanan publik, atau aspirasi warga secara transparan.',
            'konten' => "1. Buka halaman menu 'Pengaduan Warga' di website SADESA.\n2. Isi identitas pelapor, kategori masalah (Infrastruktur/Bansos/Pelayanan), judul, dan detail kronologi keluhan.\n3. Sertakan bukti foto/dokumen pendukung jika ada.\n4. Anda dapat memilih opsi 'Kirim sebagai Anonim' bila menghendaki identitas dirahasiakan dari publik.\n5. Simpan Kode Tiket Pengaduan untuk melacak tindak lanjut aparatur desa.",
            'urutan' => 2,
            'is_aktif' => true,
        ]);
    }
}
