<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat - {{ $surat->nomor_surat ?? 'Surat Keterangan' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 25mm 20mm 25mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .judul-surat {
            margin-top: 10px;
            margin-bottom: 25px;
        }
        .judul-surat h3 {
            margin: 0;
            font-size: 14pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .judul-surat p {
            margin: 3px 0 0 0;
            font-size: 11pt;
        }
        .table-data {
            width: 100%;
            margin: 12px 0 16px 20px;
            border-collapse: collapse;
        }
        .table-data td {
            padding: 3px 6px;
            vertical-align: top;
            font-size: 11.5pt;
        }
        .ttd-box {
            margin-top: 35px;
            width: 100%;
        }
        .ttd-box td {
            vertical-align: top;
        }
        .qr-placeholder {
            width: 75px;
            height: 75px;
            border: 1px solid #333;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 8pt;
            text-align: center;
            margin-bottom: 5px;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak Browser -->
    <div class="no-print" style="background: #f1f5f9; padding: 12px; text-align: right; margin-bottom: 20px; border-bottom: 1px solid #cbd5e1;">
        <button onclick="window.print()" style="background: #0284c7; color: white; padding: 8px 18px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <!-- Kop Surat Otomatis -->
    <x-kop-surat :profil="$profil" />

    <!-- Judul & Nomor Surat -->
    <div class="judul-surat text-center">
        <h3>{{ $surat->jenisSurat->nama_surat ?? 'SURAT KETERANGAN' }}</h3>
        <p>Nomor: {{ $surat->nomor_surat ?? '470 / ...... / KJW / 2026' }}</p>
    </div>

    <!-- Paragraf Pembuka -->
    <div class="text-justify">
        <p>
            Yang bertanda tangan di bawah ini Kepala Desa {{ $profil->nama_desa ?? 'Kejawar' }}, 
            Kecamatan {{ $profil->kecamatan ?? 'Banyumas' }}, Kabupaten {{ $profil->kabupaten ?? 'Banyumas' }}, 
            menerangkan dengan sebenarnya bahwa:
        </p>
    </div>

    <!-- Data Biodata Penduduk (Otomatis dari Database) -->
    <table class="table-data">
        <tr>
            <td style="width: 25%;">Nama Lengkap</td>
            <td style="width: 3%;">:</td>
            <td style="width: 72%;" class="fw-bold">{{ strtoupper($surat->warga->nama_lengkap ?? '-') }}</td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $surat->warga->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td>No. Kartu Keluarga</td>
            <td>:</td>
            <td>{{ $surat->warga->kartuKeluarga->no_kk ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tempat, Tgl. Lahir</td>
            <td>:</td>
            <td>{{ $surat->warga->tempat_lahir ?? '-' }}, {{ $surat->warga->tanggal_lahir ? $surat->warga->tanggal_lahir->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $surat->warga->jenis_kelamin ?? '-' }}</td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>:</td>
            <td>{{ $surat->warga->agama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>:</td>
            <td>{{ $surat->warga->pekerjaan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Tinggal</td>
            <td>:</td>
            <td>
                {{ $surat->warga->kartuKeluarga->alamat ?? '-' }}, 
                RT {{ $surat->warga->kartuKeluarga->rt ?? '0' }} / RW {{ $surat->warga->kartuKeluarga->rw ?? '0' }}, 
                Dusun {{ $surat->warga->kartuKeluarga->dusun ?? '-' }}, 
                Desa {{ $profil->nama_desa ?? 'Kejawar' }}
            </td>
        </tr>
    </table>

    <!-- Paragraf Maksud & Keterangan Tambahan -->
    <div class="text-justify">
        <p>
            Bahwa nama tersebut di atas adalah benar-benar warga penduduk Desa {{ $profil->nama_desa ?? 'Kejawar' }}, 
            dan surat pengantar/keterangan ini diberikan untuk keperluan:
        </p>
        <p style="margin-left: 20px; font-weight: bold; font-style: italic;">
            "{{ $surat->keperluan ?? '-' }}"
        </p>

        @if(!empty($surat->data_isian))
            <p>Keterangan Tambahan:</p>
            <table class="table-data" style="margin-top: 5px;">
                @foreach($surat->data_isian as $key => $val)
                    <tr>
                        <td style="width: 25%; text-transform: capitalize;">{{ str_replace('_', ' ', $key) }}</td>
                        <td style="width: 3%;">:</td>
                        <td style="width: 72%;">{{ $val }}</td>
                    </tr>
                @endforeach
            </table>
        @endif

        <p>
            Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <!-- Tanda Tangan & QR Code Verifikasi Keabsahan -->
    <table class="ttd-box">
        <tr>
            <td style="width: 50%; text-align: center;">
                @if($surat->qr_token)
                    <div style="margin-top: 25px;">
                        <div class="qr-placeholder">
                            QR CODE<br>VERIFIKASI
                        </div>
                        <p style="margin: 0; font-size: 8.5pt; color: #555;">
                            Pindai untuk validasi keaslian surat<br>
                            Token: {{ substr($surat->qr_token, 0, 10) }}...
                        </p>
                    </div>
                @endif
            </td>
            <td style="width: 50%; text-align: center;">
                <p style="margin: 0 0 5px 0;">
                    {{ $profil->nama_desa ?? 'Kejawar' }}, {{ now()->translatedFormat('d F Y') }}
                </p>
                <p style="margin: 0 0 55px 0; font-weight: bold;">
                    Kepala Desa {{ $profil->nama_desa ?? 'Kejawar' }}
                </p>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    {{ $profil->nama_kepala_desa ?? 'SUJATNO, S.Sos.' }}
                </p>
                @if($profil->nip_kepala_desa)
                    <p style="margin: 2px 0 0 0; font-size: 10pt;">
                        NIP. {{ $profil->nip_kepala_desa }}
                    </p>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>
