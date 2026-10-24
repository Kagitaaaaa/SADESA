# SADESA (Sistem Administrasi & Informasi Desa)

Aplikasi web administrasi desa untuk pelayanan surat pengantar warga, publikasi informasi, pengelolaan kependudukan, dan pengaduan masyarakat.

Repository Utama: [https://github.com/Kagitaaaaa/SADESA](https://github.com/Kagitaaaaa/SADESA)

---

## 👥 Pembagian Tugas Modul (6 Orang)

| No | Anggota | Modul Utama | Ruang Lingkup File / Folder |
|:--:|---|---|---|
| 1 | **Anggota 1** | **Kependudukan & User** | Master Kartu Keluarga & Data Warga (`admin/kependudukan/*`), Akun Pengguna & Role |
| 2 | **Anggota 2** | **Layanan E-Surat & Kop Surat** | Permohonan Surat Warga (`warga/surat/*`), Verifikasi Admin, Cetak Surat Ber-Kop Otomatis (`surat/cetak/*`) & QR Code |
| 3 | **Anggota 3** | **Pengaduan Warga** | Form Pengaduan Publik/Warga (`public/pengaduan/*`), Lacak Tiket Aduan, Tanggapan Admin (`admin/pengaduan/*`) |
| 4 | **Anggota 4** | **Informasi, Dokumentasi & Agenda** | Publikasi Berita Desa, Kalender Agenda Kegiatan, Galeri Dokumentasi Foto Kegiatan (`public/informasi/*`, `admin/informasi/*`) |
| 5 | **Anggota 5** | **Profil Desa & Aparatur** | Halaman About Desa (Sejarah, Visi-Misi, Peta Wilayah) & Struktur Organisasi Aparatur Desa (`public/about/*`, `admin/profil/*`) |
| 6 | **Anggota 6** | **Panduan Sistem & Landing Page** | Halaman Tutorial/Panduan Pengajuan & Aduan (`public/panduan/*`), Halaman Depan Publik (`welcome.blade.php`), UI Finishing |

---

## 🚀 Setup Proyek (Laravel Herd)

Karena menggunakan **Laravel Herd**, setup di komputer lokal sangat praktis:

```bash
# 1. Masuk ke folder Herd dan clone dari repositori FORK Anda sendiri
cd D:\Herd
git clone https://github.com/<username-kamu>/SADESA.git
cd SADESA

# 2. Install dependensi backend PHP (Composer)
composer install

# 3. Siapkan file konfigurasi environment dan generate App Key
copy .env.example .env
php artisan key:generate

# 4. Migrasi database dan isi data awal otomatis (Desa Kejawar, Akun Uji, Jenis Surat)
php artisan migrate:fresh --seed
php artisan storage:link
```

> **Catatan:** Aset CSS & JavaScript tampilan web sudah di-compile di folder `public/build`, jadi Anda **tidak wajib menginstall Node.js / NPM**. Aplikasi langsung aktif dan dapat diakses di browser melalui:  
👉 **`http://sadesa.test`** (atau nama folder di Laravel Herd Anda)

---

## 🔑 Akun Pengujian Bawaan

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Admin Desa** | `admin@sadesa.id` | `password` | Mengelola data master, verifikasi surat, respon aduan |
| **Kepala Desa** | `kades@sadesa.id` | `password` | Validasi & tanda tangan persetujuan surat |
| **Warga** | `budi@warga.id` | `password` | Layanan mandiri permohonan surat & riwayat aduan |

---

## 🌿 Alur Kerja Fork & Pull Request (Untuk Rekan Tim)

Kolaborasi menggunakan alur **Fork & Pull Request** agar repositori utama tetap aman dan bebas dari konflik kode (*merge conflict*):

### 1. Fork Repositori (Sekali di Awal)
Buka [https://github.com/Kagitaaaaa/SADESA](https://github.com/Kagitaaaaa/SADESA) lalu klik tombol **Fork** di pojok kanan atas untuk menyalin repositori ke akun GitHub Anda masing-masing.

### 2. Koding Fitur di Komputer Lokal
```bash
# 1. Buat branch baru untuk tugas Anda (jangan koding di main)
git checkout -b feature/nama-fitur-kamu
# Contoh: git checkout -b feature/pengaduan-warga

# 2. Koding fitur sesuai pembagian tugas di folder masing-masing...

# 3. Simpan perubahan ke git
git add .
git commit -m "feat: menyelesaikan form pengaduan warga"

# 4. Push branch ke repositori Fork milik Anda sendiri
git push -u origin feature/nama-fitur-kamu
```

### 3. Buat Pull Request (PR) ke Repositori Utama
1. Buka halaman GitHub hasil fork Anda: `https://github.com/<username-kamu>/SADESA`
2. Klik tombol hijau **"Contribute"** → pilih **"Open pull request"**.
3. Pastikan tujuan merge mengarah ke repositori utama (`Kagitaaaaa/SADESA`).
4. Tulis deskripsi singkat fitur yang selesai dibuat, lalu klik **"Create pull request"**.
5. Pemilik repositori utama akan mereview dan menggabungkan (*merge*) kodingan Anda.
