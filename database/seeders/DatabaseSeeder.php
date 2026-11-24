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
        // 1. Profil Desa
        ProfilDesa::query()->delete();
        ProfilDesa::create([
            'nama_desa' => 'Pabelan',
            'kode_desa' => '33.11.12.2005',
            'kecamatan' => 'Kartasura',
            'kabupaten' => 'Sukoharjo',
            'provinsi' => 'Jawa Tengah',
            'kode_pos' => '57169',
            'alamat_kantor' => 'Jl. Ahmad Yani No. 12, Desa Pabelan, Kec. Kartasura',
            'email_desa' => 'pemdes@pabelan.desa.id',
            'telepon_desa' => '0271-780123',
            'website_url' => 'https://pabelan.desa.id',
            'logo_desa' => 'images/logo-desa-pabelan.png',
            'lambang_daerah' => 'images/lambang-kab-sukoharjo.png',
            'nama_kepala_desa' => 'Sujatno, S.Sos.',
            'nip_kepala_desa' => '19750512 200212 1 003',
            'sambutan_kades' => 'Selamat datang di Sistem Administrasi dan Informasi Desa (SADESA) Pemerintah Desa Pabelan. Layanan ini hadir untuk memberikan kemudahan pelayanan persuratan dan keterbukaan informasi bagi seluruh warga desa.',
            'sejarah' => "Desa Pabelan terletak di Kecamatan Kartasura, Kabupaten Sukoharjo dan berkembang sebagai pusat pendidikan, perdagangan, serta pemukiman masyarakat yang dinamis.\n\nSeiring perkembangan kawasan Kartasura sebagai jalur penghubung Surakarta dan sekitarnya, Desa Pabelan tumbuh menjadi wilayah dengan beragam kegiatan ekonomi dan layanan pendidikan.\n\nKini pemerintah desa terus berbenah melalui pelayanan publik berbasis digital agar warga lebih mudah memperoleh layanan administrasi.",
            'visi' => 'Terwujudnya tata kelola pemerintahan Desa Pabelan yang transparan, akuntabel, dan pelayanan masyarakat berbasis digital menuju desa yang mandiri dan sejahtera.',
            'misi' => "1. Meningkatkan pelayanan publik yang cepat, mudah, dan transparan.\n2. Mengembangkan sarana prasarana desa berbasis kebutuhan masyarakat.\n3. Memberdayakan ekonomi warga dan UMKM desa.",
            'gambaran_wilayah' => 'Desa Pabelan terletak di jalur strategis Kecamatan Kartasura, Kabupaten Sukoharjo.',
            'batas_utara' => 'Desa Gonilan',
            'batas_selatan' => 'Desa Makamhaji',
            'batas_timur' => 'Kota Surakarta',
            'batas_barat' => 'Desa Gumpang',
            'luas_wilayah' => '218,4 Hektar',
            'peta_embed' => 'https://www.google.com/maps?q=Pabelan,+Kartasura,+Sukoharjo&output=embed',
        ]);

        // 2. Aparatur Pemerintahan Desa
        Aparatur::firstOrCreate(['nama' => 'Sujatno, S.Sos.'], [
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

                $aparaturTambahan = [
            ['Siti Nurhaliza, S.E.', 'Kepala Seksi Pemerintahan', 'S1 Ekonomi', '141/09/SK/2021', 'Melaksanakan manajemen tata praja pemerintahan, menyusun rancangan regulasi desa, serta pembinaan ketenteraman dan ketertiban.', 3],
            ['Agus Prasetyo, S.Pd.', 'Kepala Seksi Kesejahteraan', 'S1 Pendidikan', '141/10/SK/2021', 'Melaksanakan pembangunan sarana prasarana perdesaan, pemberdayaan masyarakat, serta pelayanan sosial dan kesehatan.', 4],
            ['Dwi Lestari', 'Kepala Seksi Pelayanan', 'SMA/Sederajat', '141/11/SK/2021', 'Melaksanakan penyuluhan dan pelayanan masyarakat, serta memfasilitasi pelayanan administrasi kependudukan.', 5],
            ['Rina Kusumawati, S.E.', 'Kepala Urusan Keuangan', 'S1 Akuntansi', '141/12/SK/2021', 'Melaksanakan pengelolaan keuangan desa, meliputi penatausahaan, pembukuan, dan pelaporan realisasi anggaran.', 6],
            ['Eko Wahyudi', 'Kepala Urusan Tata Usaha dan Umum', 'D3 Administrasi', '141/13/SK/2021', 'Melaksanakan ketatausahaan, penataan administrasi surat-menyurat, arsip, serta pengelolaan inventaris kantor desa.', 7],
            ['Slamet Riyadi', 'Kepala Dusun I', 'SMA/Sederajat', '141/14/SK/2021', 'Membantu kepala desa dalam melaksanakan tugas pada wilayah dusun, termasuk pembinaan RT/RW dan pendataan warga.', 8],
            ['Joko Susilo', 'Kepala Dusun II', 'SMA/Sederajat', '141/15/SK/2021', 'Membantu kepala desa dalam melaksanakan tugas pada wilayah dusun, termasuk pembinaan RT/RW dan pendataan warga.', 9],
        ];

        foreach ($aparaturTambahan as [$nama, $jabatan, $pendidikan, $sk, $tupoksi, $urutan]) {
            Aparatur::firstOrCreate(['nama' => $nama], [
                'jabatan' => $jabatan,
                'pendidikan_terakhir' => $pendidikan,
                'no_sk' => $sk,
                'tupoksi' => $tupoksi,
                'urutan' => $urutan,
                'status_aktif' => true,
            ]);
        }

        // 3. Master Kartu Keluarga & Warga
        $kk = KartuKeluarga::firstOrCreate(['no_kk' => '3311121505080001'], [
            'kepala_keluarga' => 'Budi Santoso',
            'alamat' => 'Jl. Ahmad Yani RT 02 RW 01 Dusun Pabelan',
            'dusun' => 'Dusun Pabelan',
            'rt' => '02',
            'rw' => '01',
            'kode_pos' => '57169',
        ]);

        $wargaBudi = Warga::firstOrCreate(['nik' => '3311121505850001'], [
            'kartu_keluarga_id' => $kk->id,
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Sukoharjo',
            'tanggal_lahir' => '1985-05-15',
            'agama' => 'Islam',
            'pendidikan_terakhir' => 'SMA/Sederajat',
            'pekerjaan' => 'Wiraswasta',
            'status_perkawinan' => 'Kawin',
            'status_hubungan_keluarga' => 'Kepala Keluarga',
            'status_warga' => 'Aktif',
            'no_hp' => '081234567890',
        ]);

        // 4. Akun Pengguna
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

        // 5. Master Jenis Surat
        $this->call(JenisSuratSeeder::class);

        // 6. Master Kategori Informasi
        KategoriInformasi::firstOrCreate(
            ['slug' => 'berita-desa'],
            ['nama_kategori' => 'Berita Desa']
        );

        KategoriInformasi::firstOrCreate(
            ['slug' => 'pengumuman'],
            ['nama_kategori' => 'Pengumuman Resmi']
        );

        KategoriInformasi::firstOrCreate(
            ['slug' => 'pembangunan'],
            ['nama_kategori' => 'Transparansi Pembangunan']
        );

        KategoriInformasi::firstOrCreate(
            ['slug' => 'kegiatan-warga'],
            ['nama_kategori' => 'Kegiatan Warga & Pemuda']
        );

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

        // 8. Menjalankan InformasiSeeder
        $this->call(InformasiSeeder::class);
    }
}
