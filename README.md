# SADESA (Sistem Administrasi & Informasi Desa)

Aplikasi web administrasi desa untuk pelayanan surat pengantar warga, publikasi informasi, pengelolaan kependudukan, dan pengaduan masyarakat.

Repository: [https://github.com/Kagitaaaaa/SADESA-](https://github.com/Kagitaaaaa/SADESA)

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
# 1. Masuk ke direktori Herd Anda lalu clone repository
cd D:\Herd
git clone https://github.com/Kagitaaaaa/SADESA-.git
cd SADESA

# 2. Install package PHP & Frontend
composer install
npm install

# 3. Buat file .env dan generate App Key
copy .env.example .env
php artisan key:generate

# 4. Migrasi database dan isi data awal otomatis (Desa, Akun Uji, Jenis Surat)
php artisan migrate:fresh --seed
php artisan storage:link

# 5. Jalankan asset Vite
npm run dev
```

Aplikasi langsung aktif dan dapat diakses di browser melalui:  
👉 **`http://sadesa.test`** (atau URL site di Laravel Herd Anda)

---

## 🔑 Akun Pengujian Bawaan

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Admin Desa** | `admin@sadesa.id` | `password` | Mengelola data master, verifikasi surat, respon aduan |
| **Kepala Desa** | `kades@sadesa.id` | `password` | Validasi & tanda tangan persetujuan surat |
| **Warga** | `priapabelan@warga.id` | `password` | Layanan mandiri permohonan surat & riwayat aduan |

---

## 🌿 Alur Kolaborasi Git & GitHub

Agar pengerjaan 6 orang tidak saling menimpa kode (bebas merge conflict):

```bash
# 1. Selalu mulai dari branch dev terbaru
git checkout dev
git pull origin dev

# 2. Buat branch fitur sesuai tugas masing-masing
git checkout -b feature/nama-fitur-kamu
# Contoh: git checkout -b feature/layanan-surat

# 3. Koding di modul masing-masing, lalu simpan commit
git add .
git commit -m "feat: deskripsi perubahan fitur"

# 4. Sinkronkan dengan dev sebelum push (wajib)
git checkout dev
git pull origin dev
git checkout feature/nama-fitur-kamu
git merge dev

# 5. Push branch fitur ke GitHub
git push -u origin feature/nama-fitur-kamu

# 6. Buka GitHub -> Buat Pull Request (PR) ke branch 'dev'
```
