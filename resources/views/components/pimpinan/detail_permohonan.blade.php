<div class="h-full flex flex-col overflow-hidden max-w-[1600px] mx-auto p-3 sm:p-4 gap-3">

    {{-- BAR ATAS: Tombol Kembali & Flash Message --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 flex-shrink-0">
        <a href="{{ route('pimpinan.permohonan') }}"
            class="inline-flex items-center text-xs font-medium text-gray-600 hover:text-indigo-600 transition">
            <i class="fas fa-arrow-left mr-1.5 text-xs"></i>
            Kembali ke Antrean
        </a>

        {{-- Flash Session Message --}}
        @if(session('success') || session('error'))
            <div class="flex items-center text-xs font-medium px-3 py-1 rounded-lg shadow-sm border {{ session('success') ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }}">
                <i class="fas {{ session('success') ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} mr-1.5"></i>
                <span>{{ session('success') ?? session('error') }}</span>
            </div>
        @endif
    </div>

    {{-- KONTEN UTAMA: Split Screen (2 Kolom pada Desktop, Stack pada Mobile) --}}
    <div class="flex-1 flex flex-col lg:flex-row gap-4 overflow-hidden min-h-0">
        
        {{-- KOLOM KIRI: FORM DISPOSISI & AKSI (Flex-shrink agar ukurannya pas) --}}
        <div class="w-full lg:w-[400px] flex flex-col gap-3 flex-shrink-0">
            <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 mb-3 flex items-center gap-1.5">
                    <i class="fas fa-pen-nib text-indigo-600"></i> Otorisasi & Disposisi
                </h3>
                
                <form action="{{ route('pimpinan.surat.approve', $surat->id) }}" method="POST" id="formDisposisi" class="space-y-3">
                    @csrf
                    
                    {{-- Input Disposisi ACC --}}
                    <div>
                        <label for="disposisi_pimpinan" class="block text-xs font-medium text-gray-700 mb-1">
                            Catatan Disposisi <span class="text-[10px] text-green-600 font-normal">(Opsional - Jika ACC)</span>
                        </label>
                        <textarea name="disposisi_pimpinan" id="disposisi_pimpinan" rows="2" 
                            class="w-full px-3 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-xs shadow-sm bg-gray-50/50 placeholder:text-gray-400"
                            placeholder="Contoh: Setuju, proses nomor resmi..."></textarea>
                    </div>

                    {{-- Input Alasan Penolakan --}}
                    <div>
                        <label for="catatan_pimpinan" class="block text-xs font-medium text-gray-700 mb-1">
                            Alasan Penolakan <span class="text-[10px] text-red-500 font-normal">(Wajib jika TOLAK)</span>
                        </label>
                        <textarea name="catatan_pimpinan" id="catatan_pimpinan" rows="2" 
                            class="w-full px-3 py-1.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition text-xs shadow-sm bg-gray-50/50 placeholder:text-gray-400 @error('catatan_pimpinan') border-red-500 @enderror"
                            placeholder="Tulis alasan jika berkas ditolak..."></textarea>
                        @error('catatan_pimpinan')
                            <p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100">
                        <button type="submit" name="action" value="acc" 
                            class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-3 rounded-lg shadow-sm transition flex items-center justify-center gap-1.5 text-xs">
                            <i class="fas fa-check-double text-[10px]"></i> Setujui & TTD
                        </button>

                        <button type="submit" name="action" value="tolak" 
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-3 rounded-lg shadow-sm transition flex items-center justify-center gap-1.5 text-xs">
                            <i class="fas fa-times-circle text-[10px]"></i> Tolak Berkas
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- Info Tambahan (Opsional, agar sisi kiri seimbang) --}}
            <div class="hidden lg:block bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-600">
                <i class="fas fa-info-circle text-slate-500 mr-1"></i> Periksa kembali data formulir di sebelah kanan sebelum melakukan penandatanganan elektronik.
            </div>
        </div>

        {{-- KOLOM KANAN: PREVIEW SURAT (Scrollable secara mandiri) --}}
        <div class="flex-1 flex flex-col bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden min-w-0">
            
            {{-- Header Preview --}}
            <div class="flex justify-between items-center px-4 py-3 bg-gray-50/70 border-b border-gray-200 flex-shrink-0">
                <div class="min-w-0">
                    <h2 class="text-sm font-bold text-gray-800 truncate">{{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</h2>
                    <p class="text-gray-500 text-[11px]">Verifikasi Formulir Pemohon</p>
                </div>
                <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-[10px] font-medium bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                    Menunggu Verifikasi
                </span>
            </div>

            {{-- Area Kertas Surat yang Bisa di-Scroll --}}
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 bg-gray-100 flex justify-center">
                
                {{-- Simulasi Kertas A4 --}}
                <div class="w-full max-w-2xl bg-white shadow-sm border border-gray-300 rounded-sm p-6 sm:p-10 my-auto min-h-[700px] text-gray-900 select-none">
                    
                    {{-- KOP SURAT --}}
                    <div class="text-center border-b-2 border-gray-900 pb-2 mb-6">
                        <h1 class="text-base font-bold uppercase tracking-wide">Pemerintah Kota Yogyakarta</h1>
                        <h2 class="text-sm font-bold uppercase">Kelurahan Argomulyo</h2>
                        <p class="text-[11px] text-gray-600 mt-0.5">Jl. Argomulyo Raya No. 123 Yogyakarta</p>
                    </div>

                    {{-- JUDUL SURAT --}}
                    <div class="text-center mb-6">
                        <h2 class="text-sm font-bold uppercase underline">{{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</h2>
                        <p class="text-[11px] text-gray-500 mt-1">Nomor : <span class="italic text-gray-400">Otomatis saat disetujui</span></p>
                    </div>

                    {{-- ISI DATA --}}
                    <div class="text-xs leading-relaxed space-y-4">
                        <p class="text-justify indent-6">Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

                        <div class="pl-6 space-y-1.5">
                            @if(!empty($surat->data_surat) && is_array($surat->data_surat))
                                @foreach ($surat->data_surat as $key => $value)
                                    <div class="flex items-start">
                                        <div class="w-40 capitalize text-gray-600 flex-shrink-0">{{ str_replace('_', ' ', $key) }}</div>
                                        <div class="w-4 flex-shrink-0">:</div>
                                        <div class="font-semibold text-gray-900 break-words flex-1">{{ $value ?: '-' }}</div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-xs text-gray-400 italic">Data isi surat tidak ditemukan.</p>
                            @endif
                        </div>

                        <p class="text-justify indent-6 pt-2">Demikian surat ini dibuat dengan sebenar-benarnya agar dapat dipergunakan sebagaimana mestinya.</p>
                    </div>

                    {{-- TANDA TANGAN LURAH --}}
                    <div class="flex justify-end mt-12">
                        <div class="text-center w-56 text-xs space-y-1">
                            <p>Yogyakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                            <p class="font-medium text-gray-700">Lurah Kelurahan Argomulyo</p>
                            
                            {{-- Placeholder TTD Minimalis --}}
                            <div class="h-16 flex items-center justify-center border border-dashed border-indigo-300 rounded-lg my-1.5 bg-indigo-50/30">
                                <span class="text-[10px] text-indigo-500/70 italic flex items-center gap-1">
                                    <i class="fas fa-signature"></i> E-Signature Digital
                                </span>
                            </div>
                            
                            <p class="font-bold underline text-gray-800">Nama Pimpinan</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>