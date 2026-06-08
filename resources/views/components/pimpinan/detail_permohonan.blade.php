<div class="w-full mx-auto px-6 py-10">

    {{-- Back Button (Disesuaikan dengan route yang benar) --}}
    <a href="{{ route('pimpinan.permohonan') }}"
        class="inline-flex items-center text-gray-600 hover:text-blue-800 mb-8 text-base transition">
        <span class="text-xl mr-2">←</span>
        Kembali ke Antrean
    </a>

    {{-- Flash Session Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-2 text-xl"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
            <i class="fas fa-times-circle mr-2 text-xl"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ===================================================== --}}
    {{-- PANEL AKSI DISPOSISI & PERSETUJUAN PIMPINAN --}}
    {{-- ===================================================== --}}
    <div class="bg-white shadow-xl rounded-2xl p-8 mb-10 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-pen-nib text-indigo-600"></i> Form Otorisasi & Disposisi Lurah
        </h3>
        
        <form action="{{ route('pimpinan.surat.approve', $surat->id) }}" method="POST" id="formDisposisi">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                {{-- Input Disposisi (Digunakan jika memilih ACC) --}}
                <div>
                    <label for="disposisi_pimpinan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan Disposisi Pimpinan <span class="text-xs text-green-600 font-normal">(Opsional - diisi jika menyetujui berkas)</span>
                    </label>
                    <textarea name="disposisi_pimpinan" id="disposisi_pimpinan" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-sm shadow-inner"
                        placeholder="Contoh: Setuju, cetak nomor resmi dan serahkan ke pemohon."></textarea>
                </div>

                {{-- Input Alasan Penolakan (Wajib jika memilih Tolak) --}}
                <div>
                    <label for="catatan_pimpinan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alasan Penolakan Dokumen <span class="text-xs text-red-500 font-normal">(Wajib diisi jika mengklik tombol Tolak Pengajuan)</span>
                    </label>
                    <textarea name="catatan_pimpinan" id="catatan_pimpinan" rows="3" 
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition text-sm shadow-inner @error('catatan_pimpinan') border-red-500 @enderror"
                        placeholder="Tulis alasan jika berkas permohonan warga ini ditolak..."></textarea>
                    @error('catatan_pimpinan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-gray-100 pt-5">
                <button type="submit" name="action" value="acc" 
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl shadow-md transition flex items-center gap-2 text-sm">
                    <i class="fas fa-check-double"></i> Tanda Tangani & Setujui Surat
                </button>

                <button type="submit" name="action" value="tolak" 
                    class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-xl shadow-md transition flex items-center gap-2 text-sm">
                    <i class="fas fa-times-circle"></i> Tolak Dokumen Warga
                </button>
            </div>
        </form>
    </div>

    {{-- ===================================================== --}}
    {{-- PREVIEW DATA DAN TEMPLATE SURAT UNTUK PIMPINAN --}}
    {{-- ===================================================== --}}
    <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</h2>
                <p class="text-gray-500 text-sm mt-1">Verifikasi Isian Formulir Warga</p>
            </div>
            <div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-700 border border-indigo-200">
                    Menunggu Persetujuan Anda
                </span>
            </div>
        </div>

        <div class="border border-gray-300 rounded-xl p-10 bg-gray-50 min-h-[800px]">
            {{-- KOP SURAT --}}
            <div class="text-center border-b-4 border-black pb-4 mb-8">
                <h1 class="text-xl font-bold uppercase">Pemerintah Kota Yogyakarta</h1>
                <h2 class="text-lg font-semibold uppercase">Kelurahan Argomulyo</h2>
                <p class="text-sm mt-2">Jl. Argomulyo Raya No. 123 Yogyakarta</p>
            </div>

            {{-- JUDUL SURAT --}}
            <div class="text-center mb-10">
                <h2 class="text-lg font-bold uppercase underline">{{ $surat->jenisSurat->nama_surat ?? 'N/A' }}</h2>
                <p class="mt-2 text-sm">Nomor : <span class="italic text-gray-400">Otomatis digenerate setelah disetujui</span></p>
            </div>

            {{-- ISI DATA PENGGUNA --}}
            <div class="text-sm leading-loose">
                <p class="mb-6 indent-8 text-justify">Yang bertanda tangan di bawah ini menerangkan bahwa:</p>

                <div class="pl-8 mb-8">
                    {{-- Loop data surat murni dari data_surat JSON hasil parse --}}
                    @if(!empty($surat->data_surat) && is_array($surat->data_surat))
                        @foreach ($surat->data_surat as $key => $value)
                            <div class="flex mb-2">
                                <div class="w-52 capitalize text-gray-600">{{ str_replace('_', ' ', $key) }}</div>
                                <div class="w-5">:</div>
                                <div class="font-medium text-gray-900">{{ $value ?: '-' }}</div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-sm text-gray-400 italic">Data isi surat tidak ditemukan.</p>
                    @endif
                </div>

                <p class="text-justify indent-8">Demikian surat ini dibuat dengan sebenar-benarnya agar dapat dipergunakan sebagaimana mestinya.</p>
            </div>

            {{-- AREA TANDA TANGAN (PREVIEW LURAH) --}}
            <div class="flex justify-end mt-20">
                <div class="text-center w-72 text-sm">
                    <p>Yogyakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p class="mt-2">Lurah Kelurahan Argomulyo</p>
                    <div class="h-24 flex items-center justify-center border-2 border-dashed border-gray-300 rounded-xl my-2 bg-gray-100/50">
                        <span class="text-xs text-gray-400 italic">E-Signature Tanda Tangan</span>
                    </div>
                    <p class="font-semibold underline text-gray-700">Nama Pimpinan</p>
                </div>
            </div>
        </div>
    </div>
</div>