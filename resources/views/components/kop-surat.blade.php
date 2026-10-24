@props(['profil' => null])

@php
    $profil = $profil ?? \App\Models\ProfilDesa::first();
@endphp

<div class="kop-surat-container" style="border-bottom: 3px double #000; padding-bottom: 12px; margin-bottom: 24px; font-family: 'Times New Roman', Times, serif;">
    <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr>
            <!-- Lambang Daerah / Logo Kabupaten -->
            <td style="width: 15%; text-align: center; vertical-align: middle; border: none;">
                @if($profil && $profil->lambang_daerah && file_exists(public_path($profil->lambang_daerah)))
                    <img src="{{ asset($profil->lambang_daerah) }}" alt="Logo Kabupaten" style="max-height: 90px; width: auto;">
                @else
                    <div style="width: 80px; height: 80px; border: 2px dashed #999; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: #666; text-align: center; border-radius: 50%;">
                        LAMBANG<br>DAERAH
                    </div>
                @endif
            </td>

            <!-- Teks Identitas Desa -->
            <td style="width: 85%; text-align: center; vertical-align: middle; border: none; padding-right: 15px;">
                <h4 style="margin: 0; font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
                    PEMERINTAH KABUPATEN {{ strtoupper($profil->kabupaten ?? 'BANYUMAS') }}
                </h4>
                <h4 style="margin: 2px 0; font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
                    KECAMATAN {{ strtoupper($profil->kecamatan ?? 'BANYUMAS') }}
                </h4>
                <h2 style="margin: 4px 0; font-size: 18pt; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px;">
                    DESA {{ strtoupper($profil->nama_desa ?? 'KEJAWAR') }}
                </h2>
                <p style="margin: 2px 0; font-size: 10pt; line-height: 1.3;">
                    {{ $profil->alamat_kantor ?? 'Jl. Kejawar No. 01' }} | Kode Pos: {{ $profil->kode_pos ?? '53192' }}
                </p>
                <p style="margin: 0; font-size: 9.5pt; color: #222;">
                    Website: {{ $profil->website_url ?? 'kejawar.desa.id' }} &bull; Email: {{ $profil->email_desa ?? 'pemdes@kejawar.desa.id' }}
                </p>
            </td>
        </tr>
    </table>
</div>
