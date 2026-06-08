@inject('suratModel', 'App\Models\Surat')

@php
    // Proteksi mandiri: Jika controller rute lain tidak melempar data, 
    // komponen akan otomatis menarik 5 permohonan terbaru yang butuh ttd pimpinan.
    if (!isset($permohonanTerbaru)) {
        $permohonanTerbaru = $suratModel::with(['user', 'jenisSurat'])
            ->whereIn('status', ['menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'])
            ->latest()
            ->limit(5)
            ->get();
    }
@endphp

{{-- Container Utama Komponen Terbaru --}}
<div class="bg-white shadow-sm rounded-2xl p-6 w-full max-w-[1600px] mx-auto border border-gray-100 mt-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Permohonan Surat Terbaru</h2>
            <p class="text-slate-400 text-xs">Menampilkan maksimal 5 berkas masuk terbaru yang memerlukan persetujuan Anda saat ini.</p>
        </div>
        <a href="{{ route('pimpinan.permohonan') }}" class="text-blue-600 hover:text-blue-700 text-xs font-semibold flex items-center gap-1 transition">
            Lihat Semua Antrean <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <div class="min-w-[1100px]">
            
            {{-- Header Tabel Ringkas --}}
            <div class="grid grid-cols-[2.5fr_2fr_1.5fr_1.2fr_1fr] gap-4 text-gray-400 text-[11px] font-bold uppercase tracking-wider mb-3 px-4">
                <div>Data Pemohon</div>
                <div>Jenis Surat</div>
                <div>Tanggal Masuk</div>
                <div>Status</div>
                <div class="text-center">Aksi</div>
            </div>

            <div class="space-y-2.5">
                @forelse ($permohonanTerbaru as $item)
                    @php
                        $rawArray = is_string($item->data_surat) ? json_decode($item->data_surat, true) : $item->data_surat;
                        $dataSuratArray = is_array($rawArray) ? array_change_key_case($rawArray, CASE_LOWER) : [];
                        
                        $namaDiSurat = $dataSuratArray['nama'] ?? 
                                       $dataSuratArray['nama_lengkap'] ?? 
                                       ($item->user->name ?? 'Tidak Ada Nama');
                        
                        $nikDiSurat = $dataSuratArray['nik'] ?? 
                                      $dataSuratArray['no_nik'] ?? 
                                      ($item->user->nik ?? '-');
                    @endphp

                    <div class="grid grid-cols-[2.5fr_2fr_1.5fr_1.2fr_1fr] gap-4 items-center bg-white border border-gray-100 p-3.5 rounded-xl hover:shadow-md hover:border-blue-100 transition-all shadow-sm">
                        
                        {{-- Data Pemohon --}}
                        <div class="flex items-center space-x-3.5 min-w-0">
                            <div class="w-9 h-9 shrink-0 bg-gradient-to-br from-blue-700 to-indigo-900 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($namaDiSurat, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-800 text-sm truncate" title="{{ $namaDiSurat }}">{{ $namaDiSurat }}</p>
                                <p class="text-slate-400 text-xs mt-0.5 font-mono font-medium">NIK: {{ $nikDiSurat }}</p>
                            </div>
                        </div>

                        {{-- Jenis Surat --}}
                        <div class="min-w-0">
                            <p class="text-slate-700 font-medium text-sm truncate" title="{{ $item->jenisSurat->nama_surat ?? 'N/A' }}">
                                {{ $item->jenisSurat->nama_surat ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Tanggal Masuk --}}
                        <div class="text-slate-500 text-xs font-medium">
                            {{ $item->created_at ? $item->created_at->translatedFormat('d M Y - H:i') : '-' }}
                        </div>

                        {{-- Status Badge --}}
                        <div>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase border tracking-wider inline-block whitespace-nowrap bg-indigo-50 text-indigo-700 border-indigo-200/60">
                                Persetujuan Pimpinan
                            </span>
                        </div>

                        {{-- Tombol Detail Eksekusi --}}
                        <div class="flex justify-center">
                            <a href="{{ route('pimpinan.surat.detail', $item->id) }}" 
                               class="w-full max-w-[100px] text-center bg-blue-600 text-white py-2 rounded-lg text-xs font-semibold hover:bg-blue-700 active:scale-[0.98] transition shadow-sm">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center bg-white border border-gray-100 rounded-xl">
                        <p class="text-gray-400 text-sm italic">Bersih! Tidak ada permohonan surat baru yang perlu disetujui.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>