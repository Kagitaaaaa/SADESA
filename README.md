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

## 🚀 Panduan Memulai (Step-by-Step dari Fork sampai Jalan)

Ikuti urutan langkah berikut secara runtut:

### Langkah 1: Fork Repositori di GitHub (Sekali di Awal)
1. Buka repositori utama: [https://github.com/Kagitaaaaa/SADESA](https://github.com/Kagitaaaaa/SADESA)
2. Klik tombol **Fork** di pojok kanan atas untuk menyalin repositori ke akun GitHub Anda masing-masing.

---

### Langkah 2: Clone ke Komputer Lokal & Setup (Laravel Herd)
Buka terminal Anda, masuk ke direktori Herd, lalu jalankan perintah berikut:

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

> **Catatan Penting:** Anda **TIDAK PERLU menginstall Node.js ataupun NPM** karena seluruh file aset CSS dan JS tampilan web sudah di-compile di folder `public/build`.  
> Website langsung aktif dan dapat dibuka di browser:  
> 👉 **`http://sadesa.test`** (atau nama folder di Laravel Herd Anda)

---

### Langkah 3: Akun Pengujian Bawaan untuk Login

Setelah database di-seed, gunakan akun berikut untuk masuk ke sistem:

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Admin Desa** | `admin@sadesa.id` | `password` | Mengelola data master, verifikasi surat, respon aduan |
| **Kepala Desa** | `kades@sadesa.id` | `password` | Validasi & tanda tangan persetujuan surat |
| **Warga** | `budi@warga.id` | `password` | Layanan mandiri permohonan surat & riwayat aduan |

---

### Langkah 4: Alur Koding Fitur & Mengirimkan Pull Request (PR)

Setelah website berjalan di laptop Anda, ikuti alur berikut saat mengerjakan modul Anda:

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

**Kirim ke Repositori Utama:**
1. Buka halaman GitHub fork Anda: `https://github.com/<username-kamu>/SADESA`
2. Klik tombol hijau **"Contribute"** → pilih **"Open pull request"**.
3. Pastikan tujuan merge mengarah ke repositori utama (`Kagitaaaaa/SADESA`).
4. Beri judul dan deskripsi singkat tentang fitur yang dibuat, lalu klik **"Create pull request"**.
5. Pemilik repositori utama akan mereview dan menggabungkan (*merge*) kodingan Anda ke proyek utama.
