{{-- resources/views/components/pengguna/riwayatpengajuan/riwayat.blade.php --}}

@extends('layouts.pengajuan_surat')

@section('content')

{{-- Kontainer luar dengan jarak atas yang ideal --}}
<div class="w-full max-w-full mx-auto px-4 sm:px-6 py-5 mt-2">

    {{-- Alert Berhasil (Proporsional & Rapi) --}}
    @if(session('success'))
        <div class="mb-5 bg-green-50 border border-green-200 rounded-2xl px-4 py-3 flex items-start gap-3 shadow-sm">
            <div class="w-9 h-9 rounded-xl bg-green-100 flex items-center justify-center text-green-700 flex-shrink-0 text-sm">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h4 class="font-bold text-green-800 text-sm">Berhasil</h4>
                <p class="text-green-700 mt-0.5 text-xs">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- ======================================================================================== --}}
    {{-- CARD PANEL FILTER & SEARCH (MEDIUM SIZE & SPACED) --}}
    {{-- ======================================================================================== --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-5">
        <form action="{{ request()->url() }}" method="GET" class="w-full">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 @if(request('search') || request('jenis_surat') || request('status')) lg:grid-cols-5 @else lg:grid-cols-4 @endif gap-4 items-center">
                
                {{-- 1. Input Search --}}
                <div class="relative w-full sm:col-span-2">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari kode pengajuan..." 
                        class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700 placeholder-gray-400 transition shadow-sm">
                </div>
                
                {{-- 2. Dropdown Jenis Surat --}}
                <div class="w-full">
                    <select name="jenis_surat" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700 font-medium transition shadow-sm cursor-pointer">
                        <option value="">Semua Jenis Surat</option>
                        @isset($jenisSuratList)
                            @foreach($jenisSuratList as $jenis)
                                <option value="{{ $jenis->id }}" {{ request('jenis_surat') == $jenis->id ? 'selected' : '' }}>
                                    {{ $jenis->nama_surat }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- 3. Dropdown Status --}}
                <div class="w-full">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700 font-medium transition shadow-sm cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                        <option value="persetujuan" {{ request('status') == 'persetujuan' ? 'selected' : '' }}>Persetujuan Pimpinan</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- 4. Tombol Reset Filter --}}
                @if(request('search') || request('jenis_surat') || request('status'))
                    <div class="w-full">
                        <a href="{{ request()->url() }}" 
                            class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-800 text-xs font-semibold transition">
                            <i class="fa-solid fa-arrows-rotate text-[11px]"></i>
                            Reset
                        </a>
                    </div>
                @endif

            </div>

        </form>
    </div>

    {{-- ======================================================================================== --}}
    {{-- CONTAINER DATA UTAMA --}}
    {{-- ======================================================================================== --}}
    @if($suratList->count() < 1)
        
        {{-- Tampilan Kosong --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-10 text-center">
            @if(request('search') || request('jenis_surat') || request('status'))
                <div class="w-16 h-16 mx-auto rounded-full bg-amber-100 flex items-center justify-center text-amber-600 text-2xl mb-4">
                    <i class="fa-solid fa-magnifying-glass-blur"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1.5">Data Tidak Ditemukan</h3>
                <p class="text-gray-500 text-sm max-w-md mx-auto leading-relaxed">
                    Tidak ada riwayat permohonan surat yang cocok dengan kriteria filter Anda. Silakan reset kembali pencarian Anda.
                </p>
            @else
                <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-2xl mb-4">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1.5">Belum Ada Pengajuan</h3>
                <p class="text-gray-500 text-sm max-w-md mx-auto leading-relaxed mb-5">
                    Anda belum pernah mengajukan surat pelayanan di kelurahan ini.
                </p>
                <a href="{{ route('pengajuan.surat') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-700 to-indigo-700 text-white text-xs font-bold shadow-md hover:shadow-lg transition">
                    <i class="fa-solid fa-plus text-[10px]"></i> Ajukan Surat Baru
                </a>
            @endif
        </div>

    @else

        {{-- Table Card Container (Slightly Bigger & Spaced Layout) --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden w-full">

            {{-- Desktop Table View --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80 border-b border-gray-200">
                        <tr class="text-left">
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400">Kode</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400">Jenis Surat</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400">Tanggal</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400">Status</th>
                            <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-gray-400 w-12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                        @foreach($suratList as $surat)
                            <tr class="hover:bg-gray-50/40 transition">
                                {{-- Kode --}}
                                <td class="px-6 py-3.5">
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm tracking-tight">{{ $surat->kode_pengajuan }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">ID #{{ $surat->id }}</p>
                                    </div>
                                </td>

                                {{-- Jenis Surat --}}
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm flex-shrink-0">
                                            <i class="fa-solid fa-file-lines"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 text-sm leading-snug">{{ $surat->jenisSurat->nama_surat }}</h4>
                                            <p class="text-xs text-gray-400">Layanan Kelurahan</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-6 py-3.5">
                                    <p class="font-medium text-gray-700 text-sm">{{ \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d M Y') }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($surat->created_at)->format('H:i') }} WIB</p>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-3.5">
                                    @php
                                        $statusColor = match($surat->status) {
                                            'pending', 'diajukan' => 'yellow',
                                            'verifikasi' => 'blue',
                                            'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan' => 'indigo',
                                            'proses', 'diproses' => 'orange',
                                            'selesai' => 'green',
                                            'ditolak' => 'red',
                                            default => 'gray'
                                        };

                                        $statusLabel = match($surat->status) {
                                            'pending', 'diajukan' => 'Menunggu Verifikasi',
                                            'verifikasi' => 'Diverifikasi',
                                            'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan' => 'Persetujuan Pimpinan',
                                            'proses', 'diproses' => 'Diproses',
                                            'selesai' => 'Selesai',
                                            'ditolak' => 'Ditolak',
                                            default => 'Unknown'
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusColor }}-500"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                {{-- Aksi (Sudah Pas Jaraknya) --}}
                                <td class="px-6 py-3.5 align-middle">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('riwayat-pengajuan.detail', $surat->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition whitespace-nowrap">
                                            <i class="fa-solid fa-eye text-[11px]"></i> Detail
                                        </a>
                                        @if($surat->status === 'selesai' && $surat->file_surat)
                                            <a href="{{ route('downloadSurat', $surat->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition shadow-sm whitespace-nowrap">
                                                <i class="fa-solid fa-file-pdf text-[11px]"></i> Unduh
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View (Lebih Proporsional) --}}
            <div class="lg:hidden divide-y divide-gray-100">
                @foreach($suratList as $surat)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">{{ $surat->kode_pengajuan }}</p>
                                <h3 class="font-bold text-gray-800 text-sm leading-tight">{{ $surat->jenisSurat->nama_surat }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d M Y') }}</p>
                            </div>
                            @php
                                $statusColor = match($surat->status) {
                                    'pending', 'diajukan' => 'yellow',
                                    'verifikasi' => 'blue',
                                    'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan' => 'indigo',
                                    'proses', 'diproses' => 'orange',
                                    'selesai' => 'green',
                                    'ditolak' => 'red',
                                    default => 'gray'
                                };
                                $statusLabel = match($surat->status) {
                                    'pending', 'diajukan' => 'Menunggu Verifikasi',
                                    'verifikasi' => 'Diverifikasi',
                                    'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan' => 'Persetujuan Pimpinan',
                                    'proses', 'diproses' => 'Diproses',
                                    'selesai' => 'Selesai',
                                    'ditolak' => 'Ditolak',
                                    default => 'Status Tidak Diketahui'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700 h-fit whitespace-nowrap">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        <div class="grid @if($surat->status === 'selesai' && $surat->file_surat) grid-cols-2 @else grid-cols-1 @endif gap-2.5 mt-4">
                            <a href="{{ route('riwayat-pengajuan.detail', $surat->id) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs transition">
                                <i class="fa-solid fa-eye text-[11px]"></i> Lihat Detail
                            </a>
                            @if($surat->status === 'selesai' && $surat->file_surat)
                                <a href="{{ route('downloadSurat', $surat->id) }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs transition shadow-sm">
                                    <i class="fa-solid fa-file-pdf text-[11px]"></i> Unduh PDF
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination Kontrol (Proporsional) --}}
            <div class="px-6 py-3.5 border-t border-gray-100 bg-gray-50 text-xs">
                {{ $suratList->links() }}
            </div>

        </div>

    @endif

</div>

@endsection