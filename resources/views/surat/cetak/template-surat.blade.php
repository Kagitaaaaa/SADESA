@php
    $profil = $profil ?? once(fn () => \App\Models\ProfilDesa::first());
    $pejabat = $penandatangan
        ?? $surat->aparatur
        ?? $surat->ttdAparatur
        ?? \App\Models\Aparatur::where('status_aktif', true)->orderBy('urutan')->first();

    $namaDesa = $profil->nama_desa ?? 'Pabelan';
    $kecamatan = $profil->kecamatan ?? 'Kartasura';
    $kabupaten = $profil->kabupaten ?? 'Sukoharjo';
    $jabatanPejabat = $pejabat->jabatan ?? 'Kepala Desa';
    $namaPejabat = $pejabat->nama ?? ($profil->nama_kepala_desa ?? 'Sujatno, S.Sos.');
    $nipPejabat = $pejabat->nip_niap ?? ($profil->nip_kepala_desa ?? null);

    $dataTambahan = $surat->data_tambahan ?? $surat->data_isian ?? [];
    $tanggalTerbit = ($surat->tanggal_disetujui ?? $surat->tanggal_selesai ?? now())->translatedFormat('d F Y');
    $urlVerifikasi = $surat->qr_token ? route('surat.verifikasi', $surat->qr_token) : route('home');
    $hashHex = md5($urlVerifikasi);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak {{ $surat->jenisSurat->nama_surat ?? 'Surat Keterangan' }} - {{ $surat->nomor_surat ?? 'Draft' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 20mm 20mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.45;
            color: #000;
            background: #e2e8f0;
            margin: 0;
            padding: 24px 12px;
        }
        .a4-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            padding: 18mm 22mm 20mm 22mm;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.15);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .dokumen-body {
            flex-grow: 1;
        }
        .toolbar-no-print {
            max-width: 210mm;
            margin: 0 auto 16px auto;
            background: #172554;
            color: #fff;
            padding: 12px 18px;
            border-radius: 10px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 13px;
        }
        .toolbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 7px;
            font-weight: 700;
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid #93C5FD;
        }
        .btn-print {
            background: #1D4ED8;
            color: #fff;
        }
        .btn-verify {
            background: #DBEAFE;
            color: #172554;
        }
        .judul-surat {
            text-align: center;
            margin: 14px 0 18px 0;
        }
        .judul-surat h1 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .judul-surat p {
            margin: 4px 0 0 0;
            font-size: 11.5pt;
            font-weight: bold;
        }
        .paragraf {
            text-align: justify;
            margin: 0 0 10px 0;
            text-indent: 32px;
        }
        .tabel-biodata {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px 16px;
        }
        .tabel-biodata td {
            padding: 2.5px 6px;
            vertical-align: top;
            font-size: 11.5pt;
        }
        .tabel-ttd {
            width: 100%;
            margin-top: 24px;
            border-collapse: collapse;
        }
        .tabel-ttd td {
            vertical-align: top;
        }
        .qr-box {
            display: inline-block;
            padding: 4px;
            border: 1px solid #000;
            background: #fff;
            margin: 4px 0 2px 0;
        }
        .dokumen-footer {
            margin-top: 32px;
            padding-top: 8px;
            border-top: 1px solid #000;
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
            color: #000;
            line-height: 1.35;
        }
        .footnote-item {
            margin-bottom: 2px;
            text-align: justify;
        }
        .footnote-item sup {
            font-weight: bold;
            margin-right: 2px;
        }
        .footnote-url {
            font-size: 7.5pt;
            color: #111;
            margin-top: 3px;
        }
        .url-text {
            font-family: 'Courier New', Courier, monospace;
            word-break: break-all;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .a4-sheet {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 0 0 16mm 0;
                box-shadow: none;
                display: block;
            }
            .toolbar-no-print {
                display: none !important;
            }
            .dokumen-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                padding-top: 6px;
                border-top: 1px solid #000;
                background: #fff;
            }
        }
    </style>
</head>
<body>
    <!-- Bilah Alat Cetak (Tidak ikut tercetak di kertas A4) -->
    <div class="toolbar-no-print">
        <div>
            <strong>Dokumen Resmi A4 &bull; Desa {{ $namaDesa }}</strong>
            <span style="display: block; font-size: 11px; color: #DBEAFE;">
                Nomor: {{ $surat->nomor_surat ?? 'Draft Belum Disetujui' }} &bull; Metode TTD: {{ strtoupper($surat->tipe_ttd ?? 'qrcode') }}
            </span>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            @if ($surat->qr_token)
                <a href="{{ $urlVerifikasi }}" target="_blank" class="toolbar-btn btn-verify">
                    Uji Halaman Scan QR Publik
                </a>
            @endif
            <button type="button" onclick="window.print()" class="toolbar-btn btn-print">
                Cetak / Simpan PDF (A4)
            </button>
        </div>
    </div>

    <div class="a4-sheet">
        <div class="dokumen-body">
            <!-- 1. Kop Surat Resmi Dinamis Desa Pabelan -->
            <x-kop-surat :profil="$profil" />

            <!-- 2. Judul & Nomor Surat Resmi -->
            <div class="judul-surat">
                <h1>{{ strtoupper($surat->jenisSurat->nama_surat ?? 'SURAT KETERANGAN DESA') }}</h1>
                <p>Nomor: {{ $surat->nomor_surat ?? ($surat->jenisSurat->kode_surat ?? '470') . ' / ...... / DS-PBL / ' . now()->year }}</p>
            </div>

            <!-- 3. Paragraf Pembuka -->
            <p class="paragraf">
                Yang bertanda tangan di bawah ini {{ $jabatanPejabat }} {{ $namaDesa }}, Kecamatan {{ $kecamatan }}, Kabupaten {{ $kabupaten }}, Provinsi {{ $profil->provinsi ?? 'Jawa Tengah' }}, menerangkan dengan sebenarnya bahwa:
            </p>

            <!-- 4. Tabel Biodata Dasar Pemohon -->
            <table class="tabel-biodata">
                <tr>
                    <td style="width: 30%;">Nama Lengkap</td>
                    <td style="width: 3%;">:</td>
                    <td style="width: 67%; font-weight: bold;">{{ strtoupper($surat->warga->nama_lengkap ?? '-') }}</td>
                </tr>
                <tr>
                    <td>NIK / No. KK</td>
                    <td>:</td>
                    <td>{{ $surat->warga->nik ?? '-' }} / {{ $surat->warga->kartuKeluarga->no_kk ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td>
                        {{ $surat->warga->tempat_lahir ?? '-' }},
                        {{ $surat->warga && $surat->warga->tanggal_lahir ? $surat->warga->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td>Jenis Kelamin / Agama</td>
                    <td>:</td>
                    <td>{{ $surat->warga->jenis_kelamin ?? '-' }} / {{ $surat->warga->agama ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Status Perkawinan</td>
                    <td>:</td>
                    <td>{{ $surat->warga->status_perkawinan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td>{{ $surat->warga->pekerjaan ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat Tempat Tinggal</td>
                    <td>:</td>
                    <td>
                        {{ $surat->warga->kartuKeluarga->alamat ?? '-' }},
                        RT {{ $surat->warga->kartuKeluarga->rt ?? '00' }} / RW {{ $surat->warga->kartuKeluarga->rw ?? '00' }},
                        {{ $surat->warga->kartuKeluarga->dusun ?? 'Dusun Pabelan' }},
                        Desa {{ $namaDesa }}, Kec. {{ $kecamatan }}, Kab. {{ $kabupaten }}
                    </td>
                </tr>
            </table>

            <!-- 5. Narasi Surat & Rincian Data Tambahan Khusus -->
            <p class="paragraf">
                {{ $surat->jenisSurat->template_narasi ?? 'Bahwa nama yang tercantum di atas adalah benar-benar warga penduduk Desa ' . $namaDesa . ', Kecamatan ' . $kecamatan . ', Kabupaten ' . $kabupaten . ' yang berkelakuan baik dan tercatat dalam administrasi kependudukan desa.' }}
            </p>

            @if (!empty($dataTambahan))
                <table class="tabel-biodata" style="margin-top: 4px; margin-bottom: 10px;">
                    @foreach ($dataTambahan as $key => $val)
                        <tr>
                            <td style="width: 30%;">{{ ucwords(str_replace('_', ' ', $key)) }}</td>
                            <td style="width: 3%;">:</td>
                            <td style="width: 67%; font-weight: bold;">{{ is_array($val) ? implode(', ', $val) : $val }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

            <p class="paragraf">
                Surat keterangan ini diterbitkan dan diberikan kepada yang bersangkutan untuk keperluan: <strong>"{{ $surat->keperluan }}"</strong>.
            </p>

            <p class="paragraf">
                Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
            </p>

            <!-- 6. Blok Pengesahan & Tanda Tangan Resmi di Kanan Bawah -->
            <table class="tabel-ttd">
                <tr>
                    <td style="width: 50%;">
                        <!-- Ruang kosong kiri naskah dinas -->
                    </td>

                    <td style="width: 50%; text-align: center;">
                        <div style="margin-bottom: 4px;">
                            {{ $namaDesa }}, {{ $tanggalTerbit }}
                        </div>
                        <div style="font-weight: bold; margin-bottom: 6px;">
                            @if (stripos($jabatanPejabat, 'Kepala Desa') !== false)
                                Kepala Desa {{ $namaDesa }}
                            @else
                                a.n. Kepala Desa {{ $namaDesa }}<br>{{ $jabatanPejabat }}
                            @endif
                        </div>

                        @if (($surat->tipe_ttd ?? 'qrcode') === 'qrcode' && $surat->qr_token)
                            <div class="qr-box">
                                <img
                                    id="qr-code-img"
                                    src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($urlVerifikasi) }}&margin=0"
                                    alt="QR Code Tanda Tangan Elektronik"
                                    width="84"
                                    height="84"
                                    style="display: block; margin: 0 auto; image-rendering: pixelated;"
                                />
                                <div id="qr-code-canvas" style="display: none; width: 84px; height: 84px; margin: 0 auto;"></div>
                            </div>
                            <div style="font-size: 7.5pt; color: #222; margin-top: 2px; line-height: 1.2;">
                                Ditandatangani Secara Elektronik<br>
                                <span style="font-family: monospace; font-size: 7pt; color: #444;">ID Token: {{ substr($surat->qr_token, 0, 10) }}...</span>
                            </div>
                        @else
                            <div style="height: 76px;"></div>
                        @endif

                        <div style="font-weight: bold; text-decoration: underline; margin-top: 4px;">
                            {{ strtoupper($namaPejabat) }}
                        </div>
                        @if ($nipPejabat)
                            <div style="font-size: 10pt;">
                                NIP/NIAP. {{ $nipPejabat }}
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <!-- 7. Footer Catatan Kaki Keabsahan Dokumen (Running Footer Bawah Halaman Tanpa Logo) -->
        <div class="dokumen-footer">
            <div class="footnote-item">
                <sup></sup> Dokumen ini diterbitkan dan disahkan secara resmi oleh <strong>Pemerintah Desa {{ $namaDesa }}</strong> melalui Sistem Administrasi dan Informasi Desa (SADESA).
            </div>
            @if ($surat->qr_token)
                <div class="footnote-item footnote-url">
                    Keabsahan dokumen dapat diverifikasi dengan memindai QR Code pada lembar ini atau mengunjungi tautan:
                    <span class="url-text">{{ $urlVerifikasi }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Script Pembuat Fallback QR Code Otomatis (Jika Offline) -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var qrImg = document.getElementById('qr-code-img');
            var qrCanvas = document.getElementById('qr-code-canvas');
            var verifyUrl = @json($urlVerifikasi);

            if (qrImg) {
                qrImg.onerror = function () {
                    qrImg.style.display = 'none';
                    if (qrCanvas && typeof QRCode !== 'undefined') {
                        qrCanvas.style.display = 'block';
                        new QRCode(qrCanvas, {
                            text: verifyUrl,
                            width: 84,
                            height: 84,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.M
                        });
                    }
                };
            }
        });
    </script>
</body>
</html>
