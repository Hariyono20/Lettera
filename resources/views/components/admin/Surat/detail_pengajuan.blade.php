@extends('layouts.admin.detail_surat')

{{-- Memastikan FontAwesome & Alpine.js tersedia untuk interaksi tombol tolak --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@section('content')
    <div class="w-full mx-auto px-6 py-6 mt-1 space-y-5">

        {{-- BACK BUTTON --}}
        <div>
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-blue-600 transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali</span>
            </a>
        </div>

        {{-- FLASH SESSION MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center shadow-sm text-xs">
                <i class="fas fa-check-circle mr-2 text-sm text-green-600"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center shadow-sm text-xs">
                <i class="fas fa-times-circle mr-2 text-sm text-red-500"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- TRACKING STATUS --}}
        {{-- ===================================================== --}}
        <div class="bg-white shadow-sm rounded-2xl p-5 border border-gray-200">

            @php
                $currentStep = match($surat->status) {
                    'pending', 'diajukan'                                                 => 1,
                    'verifikasi', 'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'      => 2,
                    'proses', 'diproses', 'disposisi'                                     => 3,
                    'selesai', 'siap_diambil'                                             => 4,
                    'ditolak', 'ditolak_pimpinan'                                         => 1,
                    default                                                               => 1
                };

                $statusLabel = match($surat->status) { 
                    'pending', 'diajukan'                                                 => 'Menunggu Verifikasi Admin Kelurahan', 
                    'verifikasi'                                                          => 'Diverifikasi oleh Admin', 
                    'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'               => 'Menunggu Persetujuan / Disposisi Lurah', 
                    'proses', 'diproses', 'disposisi'                                     => 'Disetujui Lurah (Silakan Cetak & TTD Manual)', 
                    'siap_diambil'                                                        => 'Surat Siap Diambil Pemohon', 
                    'selesai'                                                             => 'Selesai & Berkas Diarsipkan', 
                    'ditolak', 'ditolak_pimpinan'                                         => 'Ditolak',
                    default                                                               => 'Status Tidak Diketahui'
                };

                $badgeColor = match($surat->status) {
                    'pending', 'diajukan'                                                 => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                    'verifikasi', 'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'      => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'proses', 'diproses', 'disposisi'                                     => 'bg-orange-50 text-orange-700 border-orange-200',
                    'selesai', 'siap_diambil'                                             => 'bg-green-50 text-green-700 border-green-200',
                    'ditolak', 'ditolak_pimpinan'                                         => 'bg-red-50 text-red-700 border-red-200',
                    default                                                               => 'bg-gray-50 text-gray-700 border-gray-200'
                };

                $dataSuratArray = is_string($surat->data_surat) ? json_decode($surat->data_surat, true) : $surat->data_surat;
                $masterSetting = $pengaturan ?? \App\Models\PengaturanSurat::find(1);
                
                $polaNomor = $masterSetting->kode_pola_surat ?? '000/{NUMBER}/ARG/' . date('Y');
                $angkaTigaDigit = str_pad($surat->id, 3, '0', STR_PAD_LEFT);
                $nomorSuratPreview = str_replace('{NUMBER}', $angkaTigaDigit, $polaNomor);
            @endphp

            <div class="relative px-4 sm:px-8 py-2">
                <div class="absolute top-4 left-0 w-full h-0.5 bg-gray-100 rounded-full"></div>
                <div class="absolute top-4 left-0 h-0.5 rounded-full {{ in_array($surat->status, ['ditolak', 'ditolak_pimpinan']) ? 'bg-red-500' : 'bg-blue-600' }}"
                    style="width: {{ match($currentStep) { 1 => '0%', 2 => '33.33%', 3 => '66.66%', 4 => '100%', default => '0%' } }}">
                </div>

                <div class="relative flex justify-between">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition-all duration-300 {{ $currentStep >= 1 ? (in_array($surat->status, ['ditolak', 'ditolak_pimpinan']) ? 'bg-red-500 text-white font-bold' : 'bg-blue-600 text-white font-bold') : 'bg-gray-200 text-gray-400' }}">
                            1
                        </div>
                        <span class="mt-2 text-[11px] font-bold text-center {{ $currentStep == 1 ? 'text-blue-600' : 'text-gray-500' }}">
                            Pengajuan <br><span class="text-[10px] font-normal text-gray-400">(Pending)</span>
                        </span>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition-all duration-300 {{ $currentStep >= 2 ? 'bg-blue-600 text-white font-bold' : 'bg-gray-200 text-gray-400' }}">
                            2
                        </div>
                        <span class="mt-2 text-[11px] font-bold text-center {{ $currentStep == 2 ? 'text-indigo-600' : 'text-gray-500' }}">
                            Persetujuan <br><span class="text-[10px] font-normal text-gray-400">(Lurah)</span>
                        </span>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition-all duration-300 {{ $currentStep >= 3 ? 'bg-blue-600 text-white font-bold' : 'bg-gray-200 text-gray-400' }}">
                            3
                        </div>
                        <span class="mt-2 text-[11px] font-bold text-center {{ $currentStep == 3 ? 'text-orange-600' : 'text-gray-500' }}">
                            Cetak & TTD <br><span class="text-[10px] font-normal text-gray-400">(Manual)</span>
                        </span>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs transition-all duration-300 {{ $currentStep >= 4 ? 'bg-green-600 text-white font-bold' : 'bg-gray-200 text-gray-400' }}">
                            4
                        </div>
                        <span class="mt-2 text-[11px] font-bold text-center {{ $currentStep == 4 ? 'text-green-600' : 'text-gray-500' }}">
                            Selesai
                        </span>
                    </div>
                </div>
            </div>

            {{-- Keterangan Alur Status & Disposisi --}}
            <div class="mt-6 pt-3 border-t border-gray-50">
                @if ($surat->status === 'ditolak' || $surat->status === 'ditolak_pimpinan')
                    <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 rounded-xl text-xs">
                        <div class="flex items-start gap-2.5">
                            <i class="fas fa-times-circle text-sm mt-0.5 text-red-600"></i>
                            <div>
                                <h3 class="font-bold text-red-800 mb-0.5">Pengajuan Ditolak</h3>
                                @if($surat->catatan_admin) <p class="font-normal"><strong>Alasan Admin:</strong> {{ $surat->catatan_admin }}</p> @endif
                                @if($surat->catatan_pimpinan) <p class="font-normal mt-0.5"><strong>Catatan Lurah:</strong> {{ $surat->catatan_pimpinan }}</p> @endif
                            </div>
                        </div>
                    </div>
                @elseif($surat->status === 'selesai' || $surat->status === 'siap_diambil')
                    <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-xl text-xs">
                        <div class="flex items-start gap-2.5">
                            <i class="fas fa-check-circle text-sm mt-0.5 text-green-600"></i>
                            <div class="w-full">
                                <h3 class="font-bold text-green-800 mb-0.5">Surat Selesai Diproses</h3>
                                <p class="font-normal">Surat resmi selesai ditandatangani manual.</p>
                                @if($surat->catatan_admin) <p class="mt-1 font-medium text-green-900"><i class="fas fa-bell text-green-600 mr-0.5"></i> Pengambilan: "{{ $surat->catatan_admin }}"</p> @endif
                                
                                {{-- Menampilkan riwayat disposisi pimpinan setelah status selesai --}}
                                @if($surat->disposisi_pimpinan && trim($surat->disposisi_pimpinan) !== '')
                                    <div class="mt-3 bg-white/90 p-2.5 rounded-lg border border-green-200 text-gray-700 text-[11px]">
                                        <p class="font-bold text-emerald-900 mb-0.5"><i class="fas fa-thumbtack text-emerald-600 mr-0.5"></i> Arsip Catatan Disposisi Pimpinan:</p>
                                        <p class="italic font-normal">"{{ $surat->disposisi_pimpinan }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-100 text-blue-800 px-4 py-3 rounded-xl text-xs">
                        <div class="flex items-start gap-2.5">
                            <i class="fas fa-info-circle text-sm mt-0.5 text-blue-600"></i>
                            <div class="w-full">
                                <h3 class="font-bold text-blue-900 mb-0.5">Status Saat Ini</h3>
                                <p class="font-medium text-blue-900/80">{{ $statusLabel }}</p>
                                
                                {{-- MENAMPILKAN DISPOSISI PIMPINAN DENGAN TRIM PENGECEKAN KOKOH --}}
                                @if($surat->disposisi_pimpinan && trim($surat->disposisi_pimpinan) !== '')
                                    <div class="mt-2.5 bg-white p-3 rounded-lg border border-blue-200 text-gray-700 text-[11px] shadow-sm">
                                        <p class="font-bold text-indigo-900 mb-1 flex items-center gap-1">
                                            <i class="fas fa-paste text-indigo-600 text-xs"></i> 
                                            Instruksi / Catatan Disposisi Lurah:
                                        </p>
                                        <div class="bg-indigo-50/50 p-2 rounded border border-indigo-100 font-medium italic text-gray-800">
                                            "{{ $surat->disposisi_pimpinan }}"
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-2 text-[10px] text-gray-400 italic">
                                        * Belum ada catatan disposisi spesifik atau pimpinan langsung menyetujui berkas.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- PANEL AKSI VERIFIKASI ADMIN --}}
        {{-- ===================================================== --}}
        @if(in_array($surat->status, ['pending', 'diajukan']))
            <div class="bg-white shadow-sm rounded-2xl p-5 border border-gray-200" x-data="{ showTolakForm: false }">
                <h3 class="text-xs font-bold text-gray-800 mb-1">Evaluasi Pengajuan Surat</h3>
                <p class="text-[11px] text-gray-400 font-normal mb-4">Periksa keabsahan pratinjau dokumen di bawah. Tentukan apakah pengajuan ini layak diteruskan ke Pimpinan atau harus ditolak.</p>
                
                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.surat.verifikasi', $surat->id) }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="setuju">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition">
                            <i class="fas fa-check text-[10px]"></i> Verifikasi & Teruskan ke Pimpinan
                        </button>
                    </form>

                    <button type="button" @click="showTolakForm = !showTolakForm" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold px-5 py-2.5 rounded-xl text-xs flex items-center gap-1.5 transition">
                        <i class="fas fa-times text-[10px]"></i> Tolak Pengajuan
                    </button>
                </div>

                <div class="w-full" x-show="showTolakForm" x-transition class="mt-4 pt-4 border-t border-gray-100">
                    <form action="{{ route('admin.surat.verifikasi', $surat->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="action" value="tolak">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="catatan_admin" rows="3" required minlength="5"
                                class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-blue-500 text-gray-800" 
                                placeholder="Tulis alasan penolakan secara jelas (minimal 5 karakter)..."></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-sm transition">
                                Kirim Penolakan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- PANEL UNDUH BERKAS --}}
        {{-- ===================================================== --}}
        @if(in_array($surat->status, ['proses', 'diproses', 'disposisi', 'selesai', 'siap_diambil']))
            <div class="bg-white shadow-sm rounded-2xl p-4 border border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-xs font-bold text-gray-800">Langkah 1: Unduh Berkas Pengajuan</h3>
                    <p class="text-[11px] text-gray-400 mt-0.5 font-normal">Unduh PDF mentah hasil inputan warga untuk cetak fisik, pengarsipan, atau ditandatangani manual oleh Lurah.</p>
                </div>
                <a href="{{ route('admin.surat.download', $surat->id) }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-xl shadow-sm transition flex items-center gap-1.5 text-xs justify-center">
                    <i class="fas fa-download text-[10px]"></i> Unduh Berkas PDF
                </a>
            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- PANEL SELESAI MANUAL --}}
        {{-- ===================================================== --}}
        @if(in_array($surat->status, ['proses', 'diproses', 'disposisi']))
            <div class="bg-white shadow-sm rounded-2xl p-5 border border-gray-200">
                <h3 class="text-xs font-bold text-gray-800 mb-1">Langkah 2: Selesaikan Transaksi Surat</h3>
                <p class="text-[11px] text-gray-400 font-normal">Silakan jalankan proses tanda tangan basah fisik terlebih dahulu dengan merujuk disposisi di atas, kemudian kunci status surat di bawah ini.</p>
                
                <form action="{{ route('admin.surat.selesai', $surat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-gray-700">Upload File Scan Surat Resmi (Opsional)</label>
                            <input type="file" name="file_upload" class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-xl bg-gray-50/50">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-gray-700">Pesan Pemberitahuan untuk Warga (Opsional)</label>
                            <textarea name="pemberitahuan" rows="2" class="w-full px-3 py-1.5 rounded-xl border border-gray-200 text-xs outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Surat selesai, silakan ambil berkas fisik di kantor kelurahan."></textarea>
                        </div>
                    </div>
                    <div class="border-t border-gray-50 pt-3 flex justify-end">
                        <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold px-5 py-2 rounded-xl text-xs flex items-center gap-1.5 shadow-sm transition">
                            <i class="fas fa-check-double text-[10px]"></i> Tandai Selesai
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- PREVIEW DATA SURAT (TABULAR) --}}
        {{-- ===================================================== --}}
        <div class="bg-white shadow-sm rounded-2xl p-5 border border-gray-200">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <div>
                    <h2 class="text-sm font-bold text-gray-800">Pratinjau Data Pengajuan</h2>
                    <p class="text-gray-400 text-[11px] font-normal mt-0.5">{{ $surat->jenisSurat->nama_surat ?? 'Detail Surat' }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 border rounded-full text-[11px] font-bold shadow-sm {{ $badgeColor }} capitalize">
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

            @if (in_array($surat->status, ['selesai', 'siap_diambil']) && $surat->file_surat)
                <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-6" x-data="{ openFormGanti: false }">
                    <div class="text-center py-4">
                        <i class="fas fa-file-pdf text-3xl text-red-500 mb-2"></i>
                        <p class="text-xs text-gray-500">Surat telah selesai dan diarsipkan dengan berkas fisik digital.</p>
                        
                        <div class="mt-4 flex flex-wrap justify-center gap-4 text-xs">
                            <a href="{{ asset('storage/' . $surat->file_surat) }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-blue-600 hover:underline">
                                <i class="fas fa-eye text-[10px]"></i> Lihat Dokumen Saat Ini
                            </a>
                            <button type="button" @click="openFormGanti = !openFormGanti" class="inline-flex items-center gap-1 font-bold text-orange-600 hover:underline">
                                <i class="fas fa-edit text-[10px]"></i> Ganti / Edit File
                            </button>
                        </div>
                    </div>

                    <div class="w-full" x-show="openFormGanti" x-transition class="mt-4 pt-4 border-t border-gray-200/60 max-w-md mx-auto text-left">
                        <form action="{{ route('admin.surat.update_file', $surat->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[11px] font-bold text-gray-700 mb-1">Unggah Berkas Baru (PDF/JPG/PNG max 2MB)</label>
                                <input type="file" name="file_upload" required class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="flex justify-end gap-2 text-[10px]">
                                <button type="button" @click="openFormGanti = false" class="px-3 py-1.5 font-bold text-gray-500 bg-gray-200 rounded-lg hover:bg-gray-300 transition">Batal</button>
                                <button type="submit" class="px-3 py-1.5 font-bold text-white bg-orange-600 rounded-lg hover:bg-orange-700 shadow-sm transition">Simpan File Baru</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="overflow-hidden border border-gray-200 rounded-xl">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-50 text-gray-600 uppercase font-bold border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 w-1/3">Field</th>
                                <th class="px-4 py-3">Informasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-4 py-3 font-semibold text-gray-700">Nomor Surat</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $surat->nomor_surat ?? $nomorSuratPreview }}</td>
                            </tr>
                            
                            @if(!empty($dataSuratArray) && is_array($dataSuratArray))
                                @foreach ($dataSuratArray as $key => $value)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 capitalize text-gray-600">{{ str_replace('_', ' ', $key) }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $value ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            <tr class="bg-blue-50/30">
                                <td class="px-4 py-3 font-bold text-blue-800">Pejabat Penandatangan</td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-blue-900">{{ $masterSetting->nama_pejabat }}</div>
                                    <div class="text-[10px] text-blue-700 uppercase">{{ $masterSetting->jabatan_pejabat }}</div>
                                    @if($masterSetting->nip_pejabat)
                                        <div class="text-[10px] text-gray-500 mt-0.5">NIP. {{ $masterSetting->nip_pejabat }}</div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection