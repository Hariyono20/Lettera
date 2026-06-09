@inject('suratModel', 'App\Models\Surat')

@php
    // Proteksi mandiri jika controller rute lain tidak melempar data permohonan terbaru
    if (!isset($permohonanTerbaru)) {
        $permohonanTerbaru = $suratModel::with(['user', 'jenisSurat'])
            ->whereIn('status', ['menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'])
            ->latest()
            ->limit(5)
            ->get();
    }
@endphp

{{-- Container Utama Komponen Terbaru --}}
<div class="bg-white shadow-sm rounded-xl p-4 w-full max-w-[1600px] mx-auto border border-slate-200/80 mt-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-3.5 gap-2">
        <div>
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                <i class="fas fa-clock text-blue-500 text-xs"></i> Permohonan Surat Terbaru
            </h2>
            <p class="text-slate-400 text-[11px] mt-0.5">Menampilkan maksimal 5 berkas masuk terbaru yang memerlukan persetujuan Anda saat ini.</p>
        </div>
        <a href="{{ route('pimpinan.permohonan') }}" class="text-blue-600 hover:text-blue-700 text-xs font-bold flex items-center gap-1 transition">
            Lihat Semua Antrean <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <div class="min-w-[1100px]">
            
            {{-- Header Tabel Ringkas (Total 6 Kolom) --}}
            <div class="grid grid-cols-[1.8fr_1.5fr_2fr_1.5fr_1fr_0.8fr] gap-4 text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2 px-3">
                <div>Nama Pemohon</div>
                <div>NIK</div>
                <div>Jenis Layanan Surat</div>
                <div>Tanggal Masuk</div>
                <div>Status Berkas</div>
                <div class="text-center">Aksi</div>
            </div>

            {{-- List Data Body --}}
            <div class="space-y-1.5">
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

                    {{-- Item Row Tanpa Avatar & NIK Mandiri --}}
                    <div class="grid grid-cols-[1.8fr_1.5fr_2fr_1.5fr_1fr_0.8fr] gap-4 items-center bg-white border border-slate-100 hover:border-blue-300 p-2.5 px-3 rounded-lg hover:shadow-sm transition-all">
                        
                        {{-- Kolom 1: Nama Pemohon --}}
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-700 text-xs truncate" title="{{ $namaDiSurat }}">{{ $namaDiSurat }}</p>
                        </div>

                        {{-- Kolom 2: NIK (Berdiri Sendiri) --}}
                        <div class="min-w-0">
                            <p class="text-slate-600 text-xs font-mono tracking-tight font-medium">{{ $nikDiSurat }}</p>
                        </div>

                        {{-- Kolom 3: Jenis Surat --}}
                        <div class="min-w-0">
                            <p class="text-slate-600 font-medium text-xs truncate" title="{{ $item->jenisSurat->nama_surat ?? 'N/A' }}">
                                {{ $item->jenisSurat->nama_surat ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Kolom 4: Tanggal Masuk beserta Jam --}}
                        <div class="text-slate-500 text-xs font-medium">
                            {{ $item->created_at ? $item->created_at->translatedFormat('d M Y - H:i') : '-' }}
                        </div>

                        {{-- Kolom 5: Status Badge --}}
                        <div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border border-indigo-200/60 bg-indigo-50 text-indigo-700 tracking-wide inline-block whitespace-nowrap">
                                Persetujuan Pimpinan
                            </span>
                        </div>

                        {{-- Kolom 6: Tombol Eksekusi --}}
                        <div class="flex justify-center">
                            <a href="{{ route('pimpinan.surat.detail', $item->id) }}" 
                               class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-1.5 rounded-md text-xs font-bold transition shadow-sm active:scale-[0.98]">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center border border-dashed border-slate-200 rounded-lg bg-slate-50/30">
                        <i class="fas fa-check-circle text-emerald-500 text-xl mb-1.5 block"></i>
                        <p class="text-slate-500 font-semibold text-xs">Semua Antrean Bersih!</p>
                        <p class="text-slate-400 text-[11px] mt-0.5">Tidak ada permohonan surat baru yang memerlukan persetujuan Anda saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>