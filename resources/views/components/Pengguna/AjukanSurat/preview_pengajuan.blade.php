@extends('layouts.pengajuan_surat')

@section('content')
<!-- SweetAlert2 CDN untuk Notifikasi Pop-up Premium & Rapi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="w-full max-w-full mx-auto px-4 sm:px-6 py-6 mt-2 space-y-5">

    {{-- CARD UTAMA --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- HEADER BANNER --}}
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 p-5 sm:p-6 text-white">

            {{-- Dot Pattern Integration --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg width="100%" height="100%">
                    <defs>
                        <pattern id="dotPattern" x="0" y="0" width="36" height="36" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="2" fill="white" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dotPattern)" />
                </svg>
            </div>

            <div class="relative z-10 flex flex-col gap-5">
                {{-- Baris Atas: Info Jenis Surat & Status --}}
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">
                    <div class="flex-1 flex flex-col items-start gap-2">
                        {{-- Elemen Atas --}}
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-blue-100 text-xs font-medium">
                            <i class="fa-solid fa-file-signature text-[11px]"></i>
                            <span>Preview Pengajuan Surat</span>
                        </div>

                        {{-- Elemen Bawah: JENIS SURAT (DALAM BANNER) --}}
                        <div class="inline-flex items-center gap-3 bg-white/10 border border-white/10 rounded-xl p-3 max-w-md w-full sm:w-auto">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center text-white text-sm flex-shrink-0">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-wider text-blue-200">
                                    Jenis Surat Yang Diajukan
                                </p>
                                <h3 class="text-sm font-bold text-white leading-tight">
                                    {{ $jenisSurat->nama_surat }}
                                </h3>
                            </div>
                        </div>
                    </div>

                    {{-- STATUS STEP INDICATOR --}}
                    <div class="bg-white/10 border border-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-white min-w-[240px] xl:min-w-[260px] self-start xl:self-center">
                        <p class="text-[10px] text-blue-100 uppercase tracking-wider font-bold mb-1.5">Status Tahapan</p>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-green-500/20 border border-green-400/30 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-circle-check text-green-300"></i>
                            </div>

                            <div>
                                <p class="font-bold text-sm text-white leading-tight">Siap Dikirim</p>
                                <p class="text-[11px] text-blue-200 font-normal">Langkah terakhir pengajuan</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Baris Bawah: TAMPILAN KHUSUS HP / MOBILE (Hanya muncul di HP) --}}
                <div class="block sm:hidden bg-amber-500/20 border border-amber-400/30 rounded-xl p-3.5 mt-2">
                    <h1 class="text-base font-bold text-white leading-tight flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-amber-300 text-sm"></i>
                        Periksa Draft Surat Anda
                    </h1>
                    <p class="mt-1.5 text-amber-100 text-[11px] font-normal leading-relaxed opacity-95">
                        Pastikan seluruh data dan isi surat sudah benar sebelum pengajuan dikirim ke Admin Kelurahan Argomulyo.
                    </p>
                </div>
            </div>
        </div>

        {{-- CONTENT SECTION --}}
        <div class="bg-white p-5 sm:p-6 space-y-6">

            {{-- RINGKASAN DATA FORMULIR --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                
                {{-- Header Sub-Card --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50/80">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700 text-xs">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-xs text-gray-800">
                                Ringkasan Data Pengajuan
                            </h3>
                            <p class="text-[11px] text-gray-400 font-normal">
                                Rincian berkas data yang telah Anda isi
                            </p>
                        </div>
                    </div>

                    <span class="inline-flex px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-700 text-[11px] font-bold">
                        {{ count($dataSurat) }} Field
                    </span>
                </div>

                {{-- Isi Grid Data --}}
                <div class="p-4 sm:p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        @foreach($dataSurat as $key => $value)
                            <div class="border border-gray-100 rounded-xl p-3 bg-gray-50/60 hover:bg-white hover:border-blue-100 transition duration-150">
                                <p class="text-[10px] uppercase tracking-wider font-bold text-gray-400 mb-1">
                                    {{ str_replace('_', ' ', $key) }}
                                </p>
                                <p class="text-xs font-medium text-gray-800 break-words leading-normal">
                                    {{ $value ?: '-' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- SUBMIT FORM & ACTIONS --}}
            <form id="formKirimPengajuan" action="{{ route('ajukan-surat.submit') }}" method="POST" class="space-y-5">
                @csrf

                <input type="hidden" name="jenis_surat_id" value="{{ $jenisSurat->id }}">
                <input type="hidden" name="catatan" value="{{ $catatan }}">

                @foreach($dataSurat as $key => $value)
                    <input type="hidden" name="data_surat[{{ $key }}]" value="{{ $value }}">
                @endforeach

                {{-- WARNING NOTICE --}}
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-700 flex-shrink-0 text-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-yellow-800">
                            Perhatian Sebelum Mengirim
                        </h4>
                        <p class="text-xs leading-relaxed text-yellow-700 mt-0.5 font-normal">
                            Setelah pengajuan dikirim, data akan langsung dikunci dan <strong>tidak dapat diubah kembali</strong> sampai proses peninjauan verifikasi selesai dilakukan oleh Admin Kelurahan Argomulyo.
                        </p>
                    </div>
                </div>

                {{-- ACTION BUTTONS GROUP --}}
                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 pt-4 border-t border-gray-100">
                    
                    {{-- Kembali --}}
                    <button
                        type="button"
                        onclick="window.history.back()"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-5 py-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-100 text-gray-500 font-bold text-xs transition">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali & Perbaiki
                    </button>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2.5 rounded-xl bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold text-xs shadow-sm transition duration-150">
                        <i class="fa-solid fa-paper-plane"></i>
                        Konfirmasi & Kirim Pengajuan
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>

{{-- INTERACTION & NOTIFICATION SCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formKirimPengajuan');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); 

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Kirim Pengajuan?',
                    text: "Pastikan seluruh data draf surat Anda sudah benar.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981', 
                    cancelButtonColor: '#6B7280',  
                    confirmButtonText: 'Ya, Kirim Sekarang',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-xl font-sans',
                        title: 'font-bold text-gray-800 text-sm',
                        htmlContainer: 'text-gray-600 text-xs',
                        confirmButton: 'text-xs font-bold px-4 py-2 rounded-lg',
                        cancelButton: 'text-xs font-bold px-4 py-2 rounded-lg'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Mengirim...',
                            text: 'Mohon tunggu berkas sedang diproses sistem.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        form.submit();
                    }
                });
            } else {
                if (confirm('Apakah Anda yakin ingin mengirim pengajuan surat ini? Data tidak dapat diubah kembali.')) {
                    form.submit();
                }
            }
        });
    });
</script>

<style>
    .preview-content {
        font-family: "Times New Roman", Times, serif;
        font-size: 1rem;
        line-height: 1.9;
        color: #000 !important;
        text-align: justify;
        word-wrap: break-word;
    }

    .preview-content p,
    .preview-content span,
    .preview-content div,
    .preview-content td,
    .preview-content th {
        color: #000 !important;
    }

    .preview-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
    }

    .preview-content table td,
    .preview-content table th {
        border: 1px solid #d1d5db;
        padding: 8px;
    }

    .preview-content img {
        max-width: 100%;
        height: auto;
    }

    @media (max-width: 640px) {
        .preview-content {
            font-size: 0.95rem;
            line-height: 1.8;
        }
    }
</style>
@endsection