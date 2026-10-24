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

## 🚀 Panduan Memulai (Simpel via GitHub Desktop & Laravel Herd)

Ikuti urutan langkah mudah ini:

### 1. Fork Repositori di Web GitHub
1. Buka repositori utama: [https://github.com/Kagitaaaaa/SADESA](https://github.com/Kagitaaaaa/SADESA)
2. Klik tombol **Fork** (di pojok kanan atas) untuk menyalin repositori ke akun GitHub Anda.

---

### 2. Clone Lewat Aplikasi GitHub Desktop
1. Buka aplikasi **GitHub Desktop**.
2. Klik menu **File** → **Clone repository...**
3. Di tab **GitHub.com**, pilih repositori fork Anda (`<username-kamu>/SADESA`).
4. Pada **Local path**, arahkan ke folder Herd Anda: `D:\Herd\SADESA`.
5. Klik tombol **Clone**.

---

### 3. Setup Awal Laravel (Cuma 1 Kali di Terminal)
Buka terminal / PowerShell di folder proyek (`D:\Herd\SADESA`), lalu jalankan:

```bash
# 1. Install library backend PHP
composer install

# 2. Siapkan file .env dan generate App Key
copy .env.example .env
php artisan key:generate

# 3. Buat database dan isi data awal otomatis (Desa, Akun Uji, Jenis Surat)
php artisan migrate:fresh --seed
php artisan storage:link
```
---

### 4. Akun Bawaan untuk Uji Coba Login
Gunakan akun ini untuk mengetes fitur di browser:

* **Admin Desa:** `admin@sadesa.id` | Password: `password` (Akses kelola warga, surat & berita)
* **Kepala Desa:** `kades@sadesa.id` | Password: `password` (Akses persetujuan surat)
* **Warga:** `budi@warga.id` | Password: `password` (Akses layanan mandiri surat warga)

---

### 5. Cara Commit pake GitHub Desktop

Setiap kali mau mengerjakan tugas modul Anda:

1. **Buat Branch Baru di GitHub Desktop:**
   - Di bagian atas, klik menu **Current Branch** → klik tombol **New branch**.
   - Beri nama sesuai fitur tugasmu (misal: `fitur-pengaduan`).
2. **Koding di Text Editor:**
   - Buka VS Code / editor pilihan Anda, buat kodingan sesuai modul Anda.
3. **Commit & Push di GitHub Desktop:**
   - Buka kembali GitHub Desktop (perubahan file akan otomatis terdeteksi di sisi kiri).
   - Di pojok kiri bawah, tulis pesan di kotak **Summary** (misal: `selesai buat form pengaduan`).
   - Klik tombol biru **Commit to fitur-...**
   - Di bagian atas, klik tombol **Push origin**.
4. **Kirim Tugas (Create Pull Request):**
   - Setelah selesai push, GitHub Desktop akan langsung memunculkan tombol **"Preview Pull Request"** atau **"Create Pull Request"**.
   - Klik tombol tersebut, maka browser akan terbuka otomatis.
   - Klik tombol hijau **Create pull request** di browser. Selesai!
