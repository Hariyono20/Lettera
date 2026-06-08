<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Surat Resmi - Kalurahan Argomulyo</title>
    <style>
        /* ===================================================== */
        /* ATURAN 1 & 3: TIPOGRAFI ARIAL & MARGIN ATURAN BANTUL  */
        /* ===================================================== */
        @page {
            size: a4 portrait;
            /* Margin: Atas 2cm, Kanan 2cm, Bawah 2.5cm, Kiri 3cm (untuk ruang arsip) */
            margin: 2cm 2cm 2.5cm 3cm;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #000;
            margin: 0;
            padding: 0;
            font-size: 12pt; /* Batang tubuh surat wajib ukuran 12 */
        }
        
        .wrapper {
            width: 100%;
        }
        
        /* ===================================================== */
        /* ATURAN 2: KOP SURAT FLUID (ANTI-PATAH & CENTER PERFECT)*/
        /* ===================================================== */
        .kop-container {
            width: 100%;
            margin-bottom: 2px;
        }
        
        .table-kop {
            width: 100%;
            border-collapse: collapse;
        }
        
        .logo-cell {
            vertical-align: middle;
            width: 18%; /* Menggunakan persentase agar space tengah tetap luas */
            text-align: left;
            padding: 0;
        }
        
        .logo-img {
            height: 3cm; /* Kunci tinggi logo tetap 3cm sesuai aturan */
            width: auto;
            display: block;
        }
        
        .text-cell {
            vertical-align: middle;
            text-align: center;
            padding: 0;
            /* Kompensasi padding kanan sebesar lebar logo agar teks otomatis center murni di lembar kertas */
            padding-right: 12%; 
        }
        
        /* Hierarki Ukuran Font Kop Surat (Size 12 - 14 Bold Resmi) */
        .text-kop h1 {
            font-size: 13pt;
            text-transform: uppercase;
            margin: 0;
            padding: 0;
            font-weight: bold;
            letter-spacing: 0.5px;
            line-height: 1.2;
            white-space: nowrap; /* Memaksa teks agar memanjang lurus ke samping */
        }
        .text-kop h2 {
            font-size: 14pt;
            text-transform: uppercase;
            margin: 3px 0 0 0;
            padding: 0;
            font-weight: bold;
            letter-spacing: 0.5px;
            line-height: 1.2;
            white-space: nowrap;
        }
        .text-kop h3 {
            font-size: 15pt;
            text-transform: uppercase;
            margin: 3px 0 0 0;
            padding: 0;
            font-weight: bold;
            letter-spacing: 0.5px;
            line-height: 1.3;
            white-space: nowrap;
        }
        
        .text-kop p {
            font-size: 10pt;
            margin: 5px 0 0 0;
            padding: 0;
            font-weight: normal;
            line-height: 1.3;
        }
        
        /* Garis Pembatas Kop Ganda Tradisional (Tebal-Tipis Realistis) */
        .garis-kop-tebal {
            border-top: 3.5px solid #000;
            margin-top: 10px;
            margin-bottom: 1.5px;
        }
        .garis-kop-tipis {
            border-top: 1px solid #000;
            margin-bottom: 25px;
        }
        
        /* TITEL SURAT */
        .judul-surat {
            text-align: center;
            margin-bottom: 30px;
        }
        .judul-surat h2 {
            font-size: 12pt;
            text-transform: uppercase;
            text-decoration: underline;
            font-weight: bold;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .judul-surat p {
            margin: 5px 0 0 0;
            font-size: 12pt;
            font-weight: normal;
        }
        
        /* ISI BATANG TUBUH SURAT */
        .isi-surat-text {
            text-align: justify;
            font-size: 12pt;
        }
        .indent-paragraf {
            text-indent: 1.25cm; /* Jarak ketukan paragraf standar dinas */
            text-align: justify;
            margin-top: 0;
            margin-bottom: 15px;
        }
        
        /* ===================================================== */
        /* TABEL DATA WARGA (RENGGANG, RAPI & TIDAK BOLD)        */
        /* ===================================================== */
        .table-data {
            width: 100%;
            margin-left: 1.25cm;
            margin-top: 15px;
            margin-bottom: 35px; /* Jarak dibuat agak jauh ke kalimat penutup */
            border-collapse: collapse;
            table-layout: fixed; /* Mengunci lebar kolom agar tanda (:) sejajar vertikal */
        }
        .table-data td {
            padding: 8px 0; /* Jarak vertikal renggang dan rapi */
            vertical-align: top;
            font-size: 12pt;
        }
        .label-col {
            width: 28%;
            text-transform: capitalize;
            font-weight: normal;
        }
        .titik-col {
            width: 4%;
            text-align: left;
            font-weight: normal;
        }
        .val-col {
            width: 68%;
            font-weight: normal; /* Tetap normal (Tidak Bold) */
        }

        /* AREA VERIFIKASI TANDA TANGAN (REALISTIS KEDINASAN) */
        .ttd-container {
            margin-top: 45px;
            width: 100%;
            page-break-inside: avoid;
        }
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .ttd-cell {
            width: 50%;
            vertical-align: top;
        }
        
        .ttd-kanan-box {
            width: 230px;
            margin-left: auto;
            text-align: left;
        }
        
        .ttd-kiri-box {
            width: 230px;
            margin-right: auto;
            text-align: left;
        }
        
        .space-ttd {
            height: 75px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        
        <!-- KOP SURAT STRUKTUR AMAN: ANTI TEKS PECAH/PATAH -->
        <div class="kop-container">
            <table class="table-kop">
                <tr>
                    <td class="logo-cell">
                        @if(!empty($pengaturan->logo_daerah))
                            <img src="{{ public_path('storage/' . $pengaturan->logo_daerah) }}" class="logo-img">
                        @else
                            <div style="height: 3cm; width: 2.5cm;"></div>
                        @endif
                    </td>
                    <td class="text-cell">
                        <div class="text-kop">
                            <h1>{{ $pengaturan->instansi_1 ?? 'PEMERINTAH KABUPATEN BANTUL' }}</h1>
                            <h2>{{ $pengaturan->instansi_2 ?? 'KAPANEWON SEDAYU' }}</h2>
                            <h3>{{ $pengaturan->instansi_3 ?? 'PEMERINTAH KALURAHAN ARGOMULYO' }}</h3>
                            <p>{{ $pengaturan->alamat_instansi ?? 'Jl. Wates Km. 12 Argomulyo, Sedayu, Bantul 55752' }}</p>
                            <p>{{ $pengaturan->kontak_instansi ?? 'Email: kalurahan.argomulyo@bantulkab.go.id' }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- GARIS DOUBLE KOP DINAS REALISTIS -->
        <div class="garis-kop-tebal"></div>
        <div class="garis-kop-tipis"></div>

        <div class="judul-surat">
            <h2>{{ $surat->jenisSurat->nama_surat ?? 'SURAT KETERANGAN' }}</h2>
            <p>Nomor: {{ $surat->nomor_surat ?? $nomor_surat }}</p>
        </div>

        <div class="isi-surat-text">
            <p class="indent-paragraf">Yang bertanda tangan di bawah ini, Pemerintah Kalurahan Argomulyo, Kapanewon Sedayu, Kabupaten Bantul menerangkan bahwa:</p>
            
            <!-- STRUKTUR DATA WARGA -->
            <table class="table-data">
                @if(!empty($dataSuratArray) && is_array($dataSuratArray))
                    @foreach ($dataSuratArray as $key => $value)
                        <tr>
                            <td class="label-col">{{ str_replace('_', ' ', $key) }}</td>
                            <td class="titik-col">:</td>
                            <td class="val-col">{{ $value ?: '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" style="font-style: italic; color: #777; text-align: center; padding: 15px 0;">Data isi berkas tidak terbaca.</td>
                    </tr>
                @endif
            </table>

            <p class="indent-paragraf">
                {{ $pengaturan->kalimat_penutup ?? 'Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.' }}
            </p>
        </div>

        <!-- AREA VERIFIKASI TANDA TANGAN -->
        <div class="ttd-container">
            <table class="ttd-table">
                <tr>
                    <td class="ttd-cell">
                        @if(($pengaturan->jumlah_tdd ?? '1') == '2')
                            <div class="ttd-kiri-box">
                                <p style="margin: 0; color: transparent; select: none;">.</p> 
                                <p style="margin: 0; padding: 0;">Pemohon / Warga,</p>
                                <div class="space-ttd"></div>
                                <p style="text-decoration: underline; font-weight: bold; text-transform: uppercase; margin: 0; padding: 0;">
                                    {{ $dataSuratArray['nama'] ?? $dataSuratArray['nama_lengkap'] ?? '(Nama Pemohon)' }}
                                </p>
                            </div>
                        @endif
                    </td>
                    
                    <td class="ttd-cell">
                        <div class="ttd-kanan-box">
                            <p style="margin: 0; padding: 0;">Argomulyo, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                            <p style="font-weight: bold; text-transform: uppercase; margin: 0; padding: 0;">{{ $pengaturan->jabatan_pejabat ?? 'LURAH ARGOMULYO' }}</p>
                            
                            <div class="space-ttd"></div>
                            
                            <p style="text-decoration: underline; font-weight: bold; text-transform: uppercase; margin: 0; padding: 0;">{{ $pengaturan->nama_pejabat ?? 'SUGIRAN, S.IP' }}</p>
                            @if(!empty($pengaturan->nip_pejabat))
                                <p style="font-size: 11pt; margin: 2px 0 0 0; padding: 0;">NIP. {{ $pengaturan->nip_pejabat }}</p>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>