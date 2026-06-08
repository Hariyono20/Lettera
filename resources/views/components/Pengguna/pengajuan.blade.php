<div class="bg-white shadow-xl rounded-2xl p-6 sm:p-8 w-full border border-gray-100/50">

    {{-- Judul & Tombol Lihat Semua --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-black text-gray-800 tracking-tight">Pengajuan Terbaru</h2>
        <a href="{{ route('riwayat.pengajuan') }}" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition flex items-center gap-1.5 bg-blue-50 px-3 py-1.5 rounded-xl hover:bg-blue-100/70">
            Lihat Semua <i class="fa-solid fa-arrow-right text-[11px]"></i>
        </a>
    </div>

    <hr class="mb-5 border-gray-200">

    @if($riwayatTerakhir->count() < 1)
        {{-- Kondisi Jika Belum Ada Data --}}
        <div class="p-8 text-center text-gray-500 text-sm">
            <i class="fa-solid fa-envelope-open-text text-3xl text-gray-300 mb-3 block"></i>
            Belum ada aktivitas pengajuan surat.
        </div>
    @else
        {{-- Container table → responsive horizontal --}}
        <div class="w-full overflow-x-auto">
            <div class="min-w-[850px] lg:min-w-[900px] pb-2">

                {{-- Header Tabel --}}
                <div class="grid grid-cols-4 gap-4 text-gray-500 text-base font-bold mb-4 px-4">
                    <p>Jenis Surat</p>
                    <p>Tanggal Pengajuan</p>
                    <p>Status Surat</p>
                    <p>Tindakan</p>
                </div>

                {{-- List Data Terkini --}}
                <div class="space-y-3.5 text-[15px] text-gray-700">
                    @foreach($riwayatTerakhir as $surat)
                        <div class="grid grid-cols-4 gap-4 items-center bg-gray-50/70 hover:bg-gray-100/80 p-4 rounded-2xl transition duration-150 border border-gray-100">
                            
                            {{-- Kolom 1: Nama / Jenis Surat --}}
                            <p class="font-bold text-gray-800 tracking-wide">
                                {{ $surat->jenisSurat->nama_surat }}
                            </p>
                            
                            {{-- Kolom 2: Tanggal Format Indonesia --}}
                            <p class="text-gray-600 font-medium">
                                {{ \Carbon\Carbon::parse($surat->created_at)->translatedFormat('d F Y') }}
                            </p>
                            
                            {{-- Kolom 3: Status Badge Dinamis (Sudah Sinkron & Anti-Unknown) --}}
                            <div>
                                @php
                                    $statusColor = match($surat->status) {
                                        'pending', 'diajukan' => 'bg-yellow-100 text-yellow-800 border border-yellow-200/60',
                                        'verifikasi' => 'bg-blue-100 text-blue-800 border border-blue-200/60',
                                        'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan' => 'bg-indigo-100 text-indigo-800 border border-indigo-200/60',
                                        'proses', 'diproses' => 'bg-orange-100 text-orange-800 border border-orange-200/60',
                                        'selesai' => 'bg-green-100 text-green-800 border border-green-200/60',
                                        'ditolak' => 'bg-red-100 text-red-800 border border-red-200/60',
                                        default => 'bg-gray-100 text-gray-800 border border-gray-200'
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
                                <div class="rounded-xl px-3.5 py-1.5 font-bold text-center text-xs tracking-wide w-fit shadow-sm {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </div>
                            </div>
                            
                            {{-- Kolom 4: Aksi --}}
                            <div class="flex gap-5 items-center text-sm">
                                <a href="{{ route('riwayat-pengajuan.detail', $surat->id) }}" class="text-blue-600 font-bold hover:text-blue-800 hover:underline flex items-center gap-1.5 group">
                                    <i class="fa-solid fa-eye text-xs text-blue-500 group-hover:text-blue-700"></i> Lihat Detail
                                </a>
                                
                                {{-- Tombol Download jika status Selesai --}}
                                @if($surat->status === 'selesai' && $surat->file_surat)
                                    <a href="{{ route('downloadSurat', $surat->id) }}" class="text-emerald-600 font-bold hover:text-emerald-800 hover:underline flex items-center gap-1.5 group">
                                        <i class="fa-solid fa-file-arrow-down text-xs text-emerald-500 group-hover:text-emerald-700"></i> Unduh Berkas
                                    </a>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    @endif
</div>