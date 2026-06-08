@extends('layouts.pengajuan_surat')

@section('content')

{{-- Kontainer luar padat (max-w-7xl atau full sesuai kebutuhan layout) --}}
<div class="w-full max-w-full mx-auto px-4 sm:px-5 py-4 mt-1">
    
    {{-- Tombol Kembali (Lebih Kecil) --}}
    <div class="mb-3">
        <a href="{{ route('riwayat.pengajuan') }}"
            class="inline-flex items-center gap-1.5 text-gray-500 hover:text-gray-800 font-medium text-[11px] transition-colors duration-200">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Riwayat
        </a>
    </div>

    @php
        // Inisialisasi status aktif langsung dari data surat detail
        $statusActive = $surat->status;
        
        $statusLabel = match($surat->status) {
            'pending', 'diajukan'                             => 'Menunggu Verifikasi',
            'verifikasi'                                      => 'Diverifikasi',
            'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan' => 'Menunggu Persetujuan Pimpinan',
            'proses', 'diproses'                              => 'Sedang Diproses',
            'selesai'                                         => 'Selesai',
            'ditolak'                                         => 'Ditolak',
            default                                           => 'Status Tidak Diketahui'
        };
    @endphp

    {{-- ======================================================================================== --}}
    {{-- INTEGRASI: TRACKING STATUS + DETAIL DOKUMEN (COMPACT VERSION) --}}
    {{-- ======================================================================================== --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 mb-4">
        
        {{-- Bagian Atas Card: Judul & Status Ringkas Dokumen --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-3.5 mb-4">
            <div>
                <h2 class="text-sm font-bold text-gray-900 tracking-tight flex items-center gap-1.5">
                    <i class="fa-solid fa-route text-blue-600 text-xs"></i> Alur Proses Pengajuan
                </h2>
                <div class="mt-1 text-[11px] text-gray-400 flex flex-wrap items-center gap-x-2.5 gap-y-0.5">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-hashtag text-gray-300"></i> 
                        No: <strong class="text-gray-700 font-medium">{{ $surat->nomor_surat ?? 'Belum Diterbitkan' }}</strong>
                    </span>
                    <span class="hidden sm:inline text-gray-200">•</span>
                    <span class="flex items-center gap-1">
                        <i class="fa-regular fa-calendar text-gray-300"></i> 
                        {{ \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d F Y') }} — {{ \Carbon\Carbon::parse($surat->created_at)->format('H:i') }} WIB
                    </span>
                </div>
            </div>

            {{-- Status Penyerahan / Hasil Akhir Terintegrasi --}}
            <div class="flex items-center gap-2 bg-gray-50 border border-gray-100 rounded-lg p-2 sm:max-w-sm">
                <div class="w-7 h-7 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 text-xs">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div class="text-[10px] leading-normal text-gray-500 flex-1">
                    <span class="block font-bold text-gray-700 text-[11px] mb-0.5">Penyerahan Dokumen:</span>
                    @if($surat->status === 'selesai' && $surat->catatan_admin)
                        <span class="italic text-blue-700">"{{ $surat->catatan_admin }}"</span>
                    @elseif($surat->status === 'selesai' && !$surat->file_surat)
                        <span>Selesai. Silakan ambil berkas fisik di pelayanan Kelurahan.</span>
                    @elseif($surat->status === 'selesai')
                        <span class="text-emerald-700 font-medium">Dokumen digital siap diunduh.</span>
                    @else
                        <span>Dokumen berada dalam tahap <strong class="underline text-blue-700">{{ $statusLabel }}</strong>.</span>
                    @endif
                </div>
                @if($surat->status === 'selesai' && $surat->file_surat)
                    <a href="{{ route('downloadSurat', $surat->id) }}"
                        class="inline-flex items-center gap-1 px-2 py-1 rounded bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[10px] transition shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-file-pdf"></i> Unduh
                    </a>
                @endif
            </div>
        </div>

        {{-- Grid Timeline Dinamis: Lebih kecil & rapat --}}
        <div class="relative flex flex-col lg:flex-row justify-between gap-4 lg:gap-2 pl-3 lg:pl-0">
            
            {{-- Garis Penghubung (Lebih Tipis) --}}
            <div class="absolute left-[19px] lg:left-0 top-2 bottom-2 lg:top-[11px] lg:bottom-auto w-[1.5px] lg:w-full h-full lg:h-[1.5px] bg-gray-100 -z-0"></div>

            {{-- Tahap 1: Menunggu Verifikasi --}}
            @php
                $step1Passed = in_array($statusActive, ['verifikasi', 'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan', 'proses', 'diproses', 'selesai', 'ditolak']);
            @endphp
            <div class="flex lg:flex-col items-start lg:items-center gap-3 lg:gap-1.5 flex-1 relative z-10 lg:text-center">
                <div class="w-4 h-4 rounded-full bg-white border-2 flex items-center justify-center flex-shrink-0 {{ $step1Passed ? 'border-green-500 text-green-500' : 'border-yellow-500 text-yellow-500' }}">
                    <i class="{{ $step1Passed ? 'fa-solid fa-circle-check text-[9px]' : 'fa-solid fa-spinner fa-spin text-[8px]' }}"></i>
                </div>
                <div>
                    <p class="text-[11px] {{ $step1Passed ? 'font-medium text-gray-500' : 'font-bold text-gray-900' }} leading-tight">Menunggu Verifikasi</p>
                    <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d M Y') }}</p>
                </div>
            </div>

            {{-- Tahap 2: Diverifikasi --}}
            @php
                $step2Active = in_array($statusActive, ['verifikasi', 'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan', 'proses', 'diproses', 'selesai', 'ditolak']);
                $step2Passed = in_array($statusActive, ['menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan', 'proses', 'diproses', 'selesai', 'ditolak']);
            @endphp
            <div class="flex lg:flex-col items-start lg:items-center gap-3 lg:gap-1.5 flex-1 relative z-10 lg:text-center">
                <div class="w-4 h-4 rounded-full bg-white border-2 flex items-center justify-center flex-shrink-0 {{ $step2Passed ? 'border-green-500 text-green-500' : ($step2Active ? 'border-blue-500 text-blue-500' : 'border-gray-200 text-gray-300') }}">
                    @if($step2Passed) <i class="fa-solid fa-circle-check text-[9px]"></i>
                    @elseif($step2Active) <i class="fa-solid fa-clock fa-spin-pulse text-[8px]"></i>
                    @else <i class="fa-regular fa-circle text-[8px]"></i> @endif
                </div>
                <div>
                    <p class="text-[11px] {{ $step2Passed ? 'font-medium text-gray-500' : ($step2Active ? 'font-bold text-gray-900' : 'font-medium text-gray-400') }} leading-tight">Diverifikasi</p>
                    <p class="text-[10px] text-gray-400">{{ $step2Active ? 'Cek Berkas' : '-' }}</p>
                </div>
            </div>

            {{-- Tahap 3: Persetujuan Pimpinan --}}
            @php
                $step3Active = in_array($statusActive, ['menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan', 'proses', 'diproses', 'selesai', 'ditolak']);
                $step3Passed = in_array($statusActive, ['proses', 'diproses', 'selesai', 'ditolak']);
            @endphp
            <div class="flex lg:flex-col items-start lg:items-center gap-3 lg:gap-1.5 flex-1 relative z-10 lg:text-center">
                <div class="w-4 h-4 rounded-full bg-white border-2 flex items-center justify-center flex-shrink-0 {{ $step3Passed ? 'border-green-500 text-green-500' : ($step3Active ? 'border-indigo-500 text-indigo-500' : 'border-gray-200 text-gray-300') }}">
                    @if($step3Passed) <i class="fa-solid fa-circle-check text-[9px]"></i>
                    @elseif($step3Active) <i class="fa-solid fa-clock fa-spin-pulse text-[8px]"></i>
                    @else <i class="fa-regular fa-circle text-[8px]"></i> @endif
                </div>
                <div>
                    <p class="text-[11px] {{ $step3Passed ? 'font-medium text-gray-500' : ($step3Active ? 'font-bold text-gray-900' : 'font-medium text-gray-400') }} leading-tight">Persetujuan Pimpinan</p>
                    <p class="text-[10px] text-gray-400">{{ $step3Active ? ($step3Passed ? 'Disetujui' : 'Menunggu TTD') : '-' }}</p>
                </div>
            </div>

            {{-- Tahap 4: Diproses --}}
            @php
                $step4Active = in_array($statusActive, ['proses', 'diproses', 'selesai', 'ditolak']);
                $step4Passed = in_array($statusActive, ['selesai', 'ditolak']);
            @endphp
            <div class="flex lg:flex-col items-start lg:items-center gap-3 lg:gap-1.5 flex-1 relative z-10 lg:text-center">
                <div class="w-4 h-4 rounded-full bg-white border-2 flex items-center justify-center flex-shrink-0 {{ $step4Passed ? 'border-green-500 text-green-500' : ($step4Active ? 'border-orange-500 text-orange-500' : 'border-gray-200 text-gray-300') }}">
                    @if($step4Passed) <i class="fa-solid fa-circle-check text-[9px]"></i>
                    @elseif($step4Active) <i class="fa-solid fa-clock fa-spin-pulse text-[8px]"></i>
                    @else <i class="fa-regular fa-circle text-[8px]"></i> @endif
                </div>
                <div>
                    <p class="text-[11px] {{ $step4Passed ? 'font-medium text-gray-500' : ($step4Active ? 'font-bold text-gray-900' : 'font-medium text-gray-400') }} leading-tight">Diproses</p>
                    <p class="text-[10px] text-gray-400">{{ $step4Active ? ($step4Passed ? 'Selesai Cetak' : 'Dikerjakan') : '-' }}</p>
                </div>
            </div>

            {{-- Tahap 5: Selesai / Ditolak --}}
            <div class="flex lg:flex-col items-start lg:items-center gap-3 lg:gap-1.5 flex-1 relative z-10 lg:text-center">
                @if($statusActive === 'selesai')
                    <div class="w-4 h-4 rounded-full bg-white border-2 border-green-500 text-green-500 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-[9px]"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-gray-900 leading-tight">Selesai</p>
                        <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($surat->updated_at)->translatedFormat('d M Y') }}</p>
                    </div>
                @elseif($statusActive === 'ditolak')
                    <div class="w-4 h-4 rounded-full bg-white border-2 border-red-500 text-red-500 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-xmark text-[9px]"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-red-600 leading-tight">Ditolak</p>
                        <p class="text-[10px] text-red-400">{{ \Carbon\Carbon::parse($surat->updated_at)->translatedFormat('d M Y') }}</p>
                    </div>
                @else
                    <div class="w-4 h-4 rounded-full bg-white border-2 border-gray-200 text-gray-300 flex items-center justify-center flex-shrink-0">
                        <i class="fa-regular fa-circle text-[8px]"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-gray-400 leading-tight">Selesai</p>
                        <p class="text-[10px] text-gray-300">-</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Alert Flash (Lebih Slim) --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-2.5 rounded-lg shadow-sm mb-3 flex items-center gap-2 text-xs font-medium">
            <i class="fa-solid fa-triangle-exclamation text-red-600 text-sm"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Alert Penolakan (Lebih Slim) --}}
    @if ($surat->status === 'ditolak')
        <div class="bg-red-50 border border-red-200 text-red-800 p-3 rounded-xl shadow-sm mb-4 text-xs">
            <div class="flex gap-2.5 items-start">
                <div class="w-7 h-7 bg-red-100 text-red-600 rounded-md flex items-center justify-center flex-shrink-0 text-xs">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-red-900 text-xs">Maaf, Berkas Pengajuan Anda Ditolak</h4>
                    
                    @if($surat->catatan_pimpinan)
                        <div class="text-[11px] text-red-700 mt-1.5 bg-white p-2.5 rounded-lg border border-red-100 shadow-inner">
                            <strong class="text-red-900"><i class="fa-solid fa-user-tie mr-1"></i>Alasan Penolakan Pimpinan:</strong> 
                            <p class="mt-0.5 italic">"{{ $surat->catatan_pimpinan }}"</p>
                        </div>
                    @elseif($surat->catatan_admin)
                        <div class="text-[11px] text-red-700 mt-1.5 bg-white p-2.5 rounded-lg border border-red-100 shadow-inner">
                            <strong class="text-red-900"><i class="fa-solid fa-user-shield mr-1"></i>Catatan Koreksi Admin:</strong> 
                            <p class="mt-0.5 italic">"{{ $surat->catatan_admin }}"</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- TABEL UTAMA INTEGRAL (Lebih Rapat & Bersih) --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden w-full">
        <table class="w-full border-collapse">
            <tbody>
                {{-- BARIS 1: Data Pengajuan Formulir --}}
                <tr class="border-b border-gray-200">
                    <td colspan="2" class="p-4">
                        <div class="flex items-center gap-1.5 mb-2.5">
                            <i class="fa-solid fa-list-check text-blue-600 text-xs"></i>
                            <h3 class="font-bold text-gray-800 text-xs">Data Pengajuan Formulir</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @if(!empty($surat->data_surat) && (is_array($surat->data_surat) || is_object($surat->data_surat)))
                                @foreach($surat->data_surat as $key => $value)
                                    <div class="bg-gray-50 border border-gray-100 rounded-md p-2.5 text-xs">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-0.5">
                                            {{ str_replace('_', ' ', $key) }}
                                        </p>
                                        <p class="font-semibold text-gray-700 break-words">
                                            {{ $value ?: '-' }}
                                        </p>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-xs text-gray-400 italic py-1 col-span-full">Tidak ada data formulir.</p>
                            @endif
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

<style>
    .preview-content {
        font-family: "Times New Roman", Times, serif;
        font-size: 0.9rem;
        line-height: 1.5;
        color: #000 !important;
        text-align: justify;
    }
    .preview-content p, .preview-content span, .preview-content div, .preview-content td, .preview-content th {
        color: #000 !important;
    }
    .preview-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 0.5rem 0;
    }
    .preview-content table td, .preview-content table th {
        border: 1px solid #e5e7eb;
        padding: 5px;
    }
</style>

@endsection