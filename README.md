# 🏛️ SADESA (Sistem Administrasi dan Informasi Desa)

> **Tugas Akhir Praktikum Pemrograman 2**  
> **Program Studi Pendidikan Teknik Informatika dan Komputer (PTIK)**

---

## 📌 1. Latar Belakang & Deskripsi Proyek

**SADESA (Sistem Administrasi dan Informasi Desa)** adalah aplikasi berbasis web yang dirancang untuk mengintegrasikan tata kelola kependudukan, transparansi publikasi desa, dan pelayanan surat pengantar warga secara digital.

Aplikasi ini memfasilitasi kebutuhan dua belah pihak:
1. **Pemerintah Desa**: Mengelola data induk kependudukan (Kartu Keluarga & Warga), mencetak surat keterangan resmi ber-kop otomatis, menindaklanjuti pengaduan warga, serta mempublikasikan berita, agenda, dan dokumentasi kegiatan desa.
2. **Masyarakat / Warga**: Mengajukan surat pengantar secara mandiri dari rumah tanpa antre, melacak status pengajuan, mengirimkan aspirasi/pengaduan masalah desa, serta mengakses informasi seputar desa.

---

## 🌟 2. Fitur Utama Sistem (Hasil Revisi & Evaluasi)

SADESA telah disempurnakan dengan fitur-fitur berikut:

1. **Master Kependudukan**: Pengelolaan data Kartu Keluarga (`kartu_keluargas`) dan data penduduk desa (`wargas`) dengan pencarian cepat via NIK dan No KK.
2. **Pelayanan E-Surat & Kop Otomatis**:
   - Berbagai format surat: Surat Keterangan Usaha (SKU), Keterangan Tidak Mampu (SKTM), Domisili, dan Pengantar SKCK.
   - **Kop Surat Otomatis**: Kop surat (Lambang Kabupaten, Nama Pemkab, Kecamatan, Desa, Alamat Kantor, dan Garis Ganda Kedinasan) terisi otomatis dari database desa.
   - **Biodata Otomatis**: Data pemohon otomatis ditarik dari data warga.
   - **QR Code Verifikasi**: Setiap surat dilengkapi token unik untuk validasi keaslian dokumen via scan smartphone di halaman publik.
3. **Portal Pengaduan & Aspirasi Warga**: Formulir pengaduan publik dilengkapi unggahan foto bukti, kode tiket pelacakan (misal `ADU-202610-001`), opsi anonim/rahasia, dan panel tanggapan aparatur desa.
4. **Halaman About Desa & Profil Aparatur**: Informasi sejarah desa, visi-misi, peta koordinat wilayah, serta struktur organisasi pamong desa (Kepala Desa, Sekdes, Kasi, Kaur, Kadus) beserta foto dan tupoksi.
5. **Publikasi, Dokumentasi & Agenda**: Berita desa dinamis, album galeri kegiatan desa (multi-foto), dan kalender agenda kegiatan desa mendatang.
6. **Modul Panduan & Tutorial**: Halaman panduan langkah demi langkah cara mengajukan surat online dan alur pengaduan untuk memudahkan warga.

---

## 👥 3. Pembagian Tugas Tim 6 Orang (Work Breakdown Structure)

Untuk menghindari bentrok pengodingan di GitHub, setiap anggota memegang tanggung jawab modul dan folder yang terisolasi:

| No | Anggota | Fokus Modul | File / Folder yang Dikerjakan |
|---|---|---|---|
| 1 | **Anggota 1** *(Lead)* | **Auth & Master Kependudukan** | • `app/Http/Controllers/Admin/KependudukanController.php`<br>• `resources/views/admin/kependudukan/*`<br>• Hak akses (Role: Admin vs Warga) |
| 2 | **Anggota 2** | **Layanan E-Surat & Kop Otomatis** | • `app/Http/Controllers/Warga/PermohonanSuratController.php`<br>• `app/Http/Controllers/Admin/LayananSuratController.php`<br>• `resources/views/warga/surat/*`<br>• `resources/views/admin/surat/*`<br>• `resources/views/surat/cetak/*` |
| 3 | **Anggota 3** | **Aspirasi & Pengaduan Warga** | • `app/Http/Controllers/Public/PengaduanPublikController.php`<br>• `app/Http/Controllers/Admin/PengaduanController.php`<br>• `resources/views/public/pengaduan/*`<br>• `resources/views/admin/pengaduan/*` |
| 4 | **Anggota 4** | **Informasi, Agenda & Dokumentasi** | • `app/Http/Controllers/Public/PortalBeritaController.php`<br>• `app/Http/Controllers/Admin/InformasiController.php`<br>• `resources/views/public/informasi/*`<br>• `resources/views/admin/informasi/*` |
| 5 | **Anggota 5** | **About Desa & Profil Aparatur** | • `app/Http/Controllers/Public/AboutDesaController.php`<br>• `app/Http/Controllers/Admin/ProfilDesaController.php`<br>• `resources/views/public/about/*`<br>• `resources/views/admin/profil/*` |
| 6 | **Anggota 6** | **Panduan, Landing Page & UI-UX** | • `app/Http/Controllers/Public/HomeController.php`<br>• `app/Http/Controllers/Public/VerifikasiSuratController.php`<br>• `resources/views/welcome.blade.php`<br>• `resources/views/public/panduan/*` |

---

## 🚀 4. Panduan Setup Proyek Lokal (Untuk Rekan Tim yang Baru Clone)

Ikuti langkah-langkah di bawah ini untuk menjalankan SADESA di komputer lokal masing-masing:

### Prasyarat
- PHP 8.2 atau 8.4 (Laravel Herd / Laragon / XAMPP)
- Composer
- Node.js & NPM
- Git

### Langkah Instalasi

```bash
# 1. Clone repository ke komputer lokal
git clone https://github.com/<username-lead>/sadesa-sistem-administrasi-dan-informasi-desa-.git
cd sadesa-sistem-administrasi-dan-informasi-desa-

# 2. Pindah ke branch development
git checkout dev

# 3. Install dependency PHP (Composer)
composer install

# 4. Install dependency Frontend (Node.js)
npm install

# 5. Duplikat file environment .env
# Windows (PowerShell):
copy .env.example .env
# Mac / Linux:
# cp .env.example .env

# 6. Generate Application Key Laravel
php artisan key:generate

# 7. Jalankan migrasi basis data dan seeder data awal
php artisan migrate:fresh --seed

# 8. Jalankan storage link (untuk membaca file upload/gambar)
php artisan storage:link

# 9. Jalankan development server
# Jalankan frontend bundler (terminal 1):
npm run dev

# Jalankan server Laravel (terminal 2):
php artisan serve
```

Aplikasi sekarang dapat diakses di browser melalui URL: `http://localhost:8000`.

---

## 🔐 5. Akun Pengujian Bawaan (Hasil Seeder)

Database seeder telah menyiapkan akun siap uji untuk mempermudah pengerjaan:

| Peran (Role) | Email | Password | Keterangan Akses |
|---|---|---|---|
| **Administrator** | `admin@sadesa.id` | `password` | Akses penuh panel admin perangkat desa |
| **Kepala Desa** | `kades@sadesa.id` | `password` | Menyetujui surat dan menandatangani surat |
| **Warga** | `budi@warga.id` | `password` | Terhubung ke NIK Budi Santoso untuk tes pengajuan surat mandiri |

---

## 🌿 6. Panduan Kolaborasi Git & GitHub (Anti Merge Conflict)

Agar pengerjaan 6 orang berjalan lancar tanpa konflik merge file:

### Aturan Branch
- **`main`**: Kode rilis final yang sudah diuji (hanya dimerge saat siap presentasi).
- **`dev`**: Branch pengembangan bersama. Semua anggota mengambil dan menggabungkan kodingan di sini.
- **`feature/nama-fitur`**: Branch kerja pribadi masing-masing anggota.

### Alur Kerja Harian Anggota Tim

```bash
# 1. Pastikan branch dev lokal sudah paling baru
git checkout dev
git pull origin dev

# 2. Buat branch fitur baru untuk tugasmu
git checkout -b feature/nama-fitur-kamu
# Contoh: git checkout -b feature/layanan-surat

# 3. Koding fitur sesuai tugas di modul masing-masing...

# 4. Cek perubahan file yang kamu buat
git status

# 5. Commit pekerjaan
git add .
git commit -m "feat(surat): membuat form pengajuan surat dan validasi input"

# 6. SINKRONISASI SEBELUM PUSH (PENTING! Mencegah Merge Conflict)
git checkout dev
git pull origin dev
git checkout feature/nama-fitur-kamu
git merge dev

# 7. Push branch fitur ke GitHub
git push -u origin feature/nama-fitur-kamu
```

### Membuat Pull Request (PR) di GitHub
1. Buka repository proyek di GitHub.
2. Klik tombol **"Compare & pull request"** yang baru saja muncul.
3. Pastikan **base branch: `dev`** dan **compare branch: `feature/nama-fitur-kamu`**.
4. Tulis keterangan singkat mengenai fitur yang telah selesai.
5. Klik **"Create pull request"**.
6. Ketua tim / anggota lain mereview dan klik **"Merge pull request"**.

---

## 📁 7. Struktur Folder Penting

```
sadesa/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/         <- Controller panel staf & kades
│   │   ├── Warga/         <- Controller layanan mandiri warga
│   │   └── Public/        <- Controller halaman umum
│   └── Models/            <- 14 Model Eloquent basis data
├── database/
│   ├── migrations/        <- Skema database SADESA
│   └── seeders/           <- Starter data desa, jenis surat & akun uji
├── resources/
│   └── views/
│       ├── components/    <- Komponen (kop-surat.blade.php, navbar, footer)
│       ├── public/        <- View portal umum (about, berita, pengaduan, panduan)
│       ├── warga/         <- View portal warga
│       ├── admin/         <- View panel admin
│       └── surat/cetak/   <- Template cetak A4 surat ber-kop resmi
└── routes/
    ├── web.php            <- Loader rute utama
    ├── public.php         <- Rute publik & pengunjung
    ├── warga.php          <- Rute portal warga terproteksi auth
    └── admin.php          <- Rute panel admin terproteksi auth
```

---

## ⚖️ 8. Tata Tertib Pengodean Bersama
1. **Dilarang langsung commit ke branch `main` atau `dev`**.
2. **Dilarang mengubah struktur database yang sudah ada di migration tanpa koordinasi**. Jika perlu kolom tambahan, buat migration baru (`php artisan make:migration add_kolom_to_tabel`).
3. **Jangan meng-upload file `.env`**. File konfigurasi pribadi tetap lokal.
4. **Jalankan formatter Pint sebelum commit**: Jalankan `vendor/bin/pint --dirty` agar format kode PHP rapi dan konsisten.

---
*Dikembangkan dengan penuh dedikasi oleh Tim Pengembang SADESA - PTIK.*
