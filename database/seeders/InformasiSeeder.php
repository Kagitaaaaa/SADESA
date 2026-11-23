<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Galeri;
use App\Models\Informasi;
use App\Models\KategoriInformasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class InformasiSeeder extends Seeder
{
    public function run(): void
    {
        $penulis = User::where('email', 'admin@sadesa.id')->first() ?? User::where('role', 'admin')->first();

        if (! $penulis) {
            $this->command?->warn('Akun admin belum ada. Jalankan php artisan db:seed terlebih dahulu.');

            return;
        }

        $kategori = [
            'berita-desa' => KategoriInformasi::firstOrCreate(['slug' => 'berita-desa'], ['nama_kategori' => 'Berita Desa']),
            'pengumuman' => KategoriInformasi::firstOrCreate(['slug' => 'pengumuman'], ['nama_kategori' => 'Pengumuman Resmi']),
            'pembangunan' => KategoriInformasi::firstOrCreate(['slug' => 'pembangunan'], ['nama_kategori' => 'Transparansi Pembangunan']),
            'kegiatan-warga' => KategoriInformasi::firstOrCreate(['slug' => 'kegiatan-warga'], ['nama_kategori' => 'Kegiatan Warga & Pemuda']),
        ];

        $waktu = now('Asia/Jakarta');

        $berita = [
            ['slug' => 'pelayanan-surat-pengantar-kini-dapat-diajukan-online', 'k' => 'pengumuman', 'hari' => 2,
                'judul' => 'Pelayanan Surat Pengantar Kini Dapat Diajukan Secara Online',
                'ringkasan' => 'Warga dapat mengajukan surat pengantar melalui SADESA tanpa antre di kantor desa.',
                'konten' => "Pemerintah Desa Pabelan resmi membuka layanan pengajuan surat pengantar secara online melalui SADESA.\n\nWarga cukup masuk menggunakan akun yang terdaftar, memilih jenis surat, dan melengkapi isian yang diperlukan. Status permohonan dapat dipantau langsung dari akun masing-masing.\n\nSurat yang sudah disetujui memuat kode QR untuk memeriksa keaslian dokumen."],
            ['slug' => 'perbaikan-jalan-lingkungan-rw-02-tahap-pertama-selesai', 'k' => 'pembangunan', 'hari' => 6,
                'judul' => 'Perbaikan Jalan Lingkungan RW 02 Tahap Pertama Selesai',
                'ringkasan' => 'Pengerasan jalan sepanjang 320 meter telah rampung dan dapat dilalui warga.',
                'konten' => "Pekerjaan pengerasan jalan lingkungan di RW 02 tahap pertama telah selesai dikerjakan.\n\nPembangunan bersumber dari anggaran desa tahun berjalan dan dikerjakan bersama kelompok warga setempat. Tahap berikutnya direncanakan dilanjutkan setelah proses perencanaan selesai."],
            ['slug' => 'kerja-bakti-bersama-membersihkan-saluran-air', 'k' => 'kegiatan-warga', 'hari' => 10,
                'judul' => 'Kerja Bakti Bersama Membersihkan Saluran Air Lingkungan',
                'ringkasan' => 'Puluhan warga dan karang taruna membersihkan saluran air menjelang musim hujan.',
                'konten' => "Warga bersama karang taruna melaksanakan kerja bakti membersihkan saluran air di beberapa titik.\n\nKegiatan ini rutin dilakukan menjelang musim hujan untuk mencegah genangan."],
            ['slug' => 'jadwal-posyandu-balita-dan-lansia-bulan-ini', 'k' => 'pengumuman', 'hari' => 14,
                'judul' => 'Jadwal Posyandu Balita dan Lansia Bulan Ini',
                'ringkasan' => 'Posyandu dilaksanakan di masing-masing dusun. Harap membawa buku KIA atau kartu kontrol.',
                'konten' => "Posyandu balita dan lansia bulan ini dilaksanakan di masing-masing dusun sesuai jadwal.\n\nWarga diminta membawa buku KIA atau kartu kontrol lansia. Informasi lebih lanjut dapat ditanyakan kepada kader posyandu setempat."],
        ];

        foreach ($berita as $b) {
            Informasi::firstOrCreate(['slug' => $b['slug']], [
                'kategori_informasi_id' => $kategori[$b['k']]->id,
                'user_id' => $penulis->id,
                'judul' => $b['judul'],
                'ringkasan' => $b['ringkasan'],
                'konten' => $b['konten'],
                'is_published' => true,
                'tanggal_publish' => $waktu->copy()->subDays($b['hari']),
                'views_count' => 0,
            ]);
        }

        $hariIni = $waktu->copy()->startOfDay();

        $agenda = [
            ['slug' => 'rapat-koordinasi-warga-rw-01', 'judul' => 'Rapat Koordinasi Warga RW 01', 'geser' => 3, 'durasi' => 0, 'jam' => ['19:30', '21:00'], 'lokasi' => 'Balai Desa Pabelan', 'pj' => 'Ketua RW 01', 'deskripsi' => 'Pembahasan jadwal ronda dan iuran keamanan lingkungan.'],
            ['slug' => 'posyandu-balita-dusun-pabelan', 'judul' => 'Posyandu Balita Dusun Pabelan', 'geser' => 6, 'durasi' => 0, 'jam' => ['08:00', '11:00'], 'lokasi' => 'Pos Posyandu Dusun Pabelan', 'pj' => 'Kader Posyandu', 'deskripsi' => 'Penimbangan, imunisasi, dan pemberian makanan tambahan.'],
            ['slug' => 'musyawarah-perencanaan-pembangunan-desa', 'judul' => 'Musyawarah Perencanaan Pembangunan Desa', 'geser' => 12, 'durasi' => 1, 'jam' => ['09:00', '14:00'], 'lokasi' => 'Aula Kantor Desa', 'pj' => 'Sekretaris Desa', 'deskripsi' => 'Penyusunan prioritas pembangunan desa bersama perwakilan warga.'],
            ['slug' => 'peringatan-hari-besar-nasional-tingkat-desa', 'judul' => 'Peringatan Hari Besar Nasional Tingkat Desa', 'geser' => -9, 'durasi' => 0, 'jam' => ['07:00', '10:00'], 'lokasi' => 'Lapangan Desa', 'pj' => 'Panitia Desa', 'deskripsi' => 'Upacara dan kegiatan bersama seluruh unsur masyarakat.'],
        ];

        foreach ($agenda as $a) {
            $mulai = $hariIni->copy()->addDays($a['geser']);
            $selesai = $a['durasi'] ? $mulai->copy()->addDays($a['durasi']) : null;

            Agenda::firstOrCreate(['slug' => $a['slug']], [
                'judul_agenda' => $a['judul'],
                'deskripsi' => $a['deskripsi'],
                'tanggal_mulai' => $mulai->toDateString(),
                'tanggal_selesai' => $selesai?->toDateString(),
                'jam_mulai' => $a['jam'][0],
                'jam_selesai' => $a['jam'][1],
                'lokasi' => $a['lokasi'],
                'koordinator' => $a['pj'],
                'status' => Agenda::hitungStatus($mulai, $selesai),
            ]);
        }

        $galeri = [
            ['slug' => 'kerja-bakti-bersih-desa', 'judul' => 'Kerja Bakti Bersih Desa', 'kategori' => 'Kegiatan', 'hari' => 10, 'lokasi' => 'Lingkungan RW 02', 'deskripsi' => 'Dokumentasi kerja bakti membersihkan saluran air bersama warga.'],
            ['slug' => 'pembangunan-jalan-lingkungan', 'judul' => 'Pembangunan Jalan Lingkungan', 'kategori' => 'Pembangunan', 'hari' => 20, 'lokasi' => 'RW 02', 'deskripsi' => 'Proses pengerasan jalan lingkungan tahap pertama.'],
        ];

        foreach ($galeri as $g) {
            Galeri::firstOrCreate(['slug' => $g['slug']], [
                'judul' => $g['judul'],
                'kategori' => $g['kategori'],
                'tanggal_kegiatan' => $hariIni->copy()->subDays($g['hari'])->toDateString(),
                'lokasi' => $g['lokasi'],
                'deskripsi' => $g['deskripsi'],
            ]);
        }
    }
}
