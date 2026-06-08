{{-- resources/views/components/Admin/Pengaturan/pengaturan.blade.php --}}
@props(['pengaturan'])

{{-- 
    PERBAIKAN: Seluruh konten dibungkus dengan tag <form>.
    - Menggunakan route('admin.pengaturan.update') yang telah diselaraskan dengan routes/web.php
    - Menggunakan enctype="multipart/form-data" agar fitur 'Upload Logo' dapat berfungsi mengirim file gambar.
--}}
<form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('POST')

    <div class="bg-white shadow-lg rounded-2xl p-4 md:p-6 lg:p-8 w-full my-6">

        {{-- Judul Halaman --}}
        <div class="mb-4">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-700">Informasi Kelurahan & Format Surat</h2>
        </div>

        <hr class="mb-6 border-gray-300">

        {{-- Alert Notifikasi Sukses --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm text-sm font-medium">
                <i class="fa-solid fa-circle-check mr-2 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Error Validation Handlers --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ===================================================== --}}
        {{-- SECTION 1: INFORMASI KELURAHAN & KOP SURAT (HEADER)   --}}
        {{-- ===================================================== --}}
        <div class="mb-8">
            <h3 class="text-base md:text-lg font-medium text-gray-700 mb-4 flex items-center gap-2 text-blue-600">
                <i class="fa-solid fa-building"></i> Struktur Instansi & Kop Surat
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Instansi Tingkat I</label>
                    <input type="text" name="instansi_1" value="{{ old('instansi_1', $pengaturan->instansi_1 ?? 'PEMERINTAH KOTA YOGYAKARTA') }}"
                           placeholder="Contoh: PEMERINTAH KOTA YOGYAKARTA"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Instansi Tingkat II</label>
                    <input type="text" name="instansi_2" value="{{ old('instansi_2', $pengaturan->instansi_2 ?? 'KECAMATAN UMBULHARJO') }}"
                           placeholder="Contoh: KECAMATAN UMBULHARJO"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Kelurahan (Instansi Utama)</label>
                    <input type="text" name="instansi_3" value="{{ old('instansi_3', $pengaturan->instansi_3 ?? 'KELURAHAN ARGOMULYO') }}"
                           placeholder="Masukkan Nama Kelurahan"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Alamat Kantor Kelurahan</label>
                    <input type="text" name="alamat_instansi" value="{{ old('alamat_instansi', $pengaturan->alamat_instansi ?? 'Jl. Argomulyo Raya No. 123') }}"
                           placeholder="Alamat Lengkap Kelurahan"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Pola Penomoran Otomatis</label>
                    <input type="text" name="kode_pola_surat" value="{{ old('kode_pola_surat', $pengaturan->kode_pola_surat ?? '470/{NUMBER}/Kel-Argo/2026') }}"
                           placeholder="Contoh: 470/{NUMBER}/2026"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-mono text-red-600" required>
                </div>
            </div>

            {{-- Logo Instansi --}}
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-2">Logo Instansi</label>
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 bg-blue-100 p-3 rounded-xl w-14 h-14 flex items-center justify-center border">
                        @if(isset($pengaturan->logo_daerah) && $pengaturan->logo_daerah)
                            <img src="{{ asset('storage/' . $pengaturan->logo_daerah) }}" class="w-full h-full object-contain">
                        @else
                            <i class="fa-solid fa-building fa-xl text-blue-600"></i>
                        @endif
                    </div>

                    <label for="upload-logo"
                           class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl shadow flex items-center gap-2 text-sm transition">
                        <i class="fa-solid fa-upload text-sm"></i>
                        <span>Upload Logo</span>
                        <input type="file" id="upload-logo" name="logo_daerah" class="sr-only" accept="image/*">
                    </label>
                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- SECTION 2: OTORITAS PEJABAT & FOOTER SURAT            --}}
        {{-- ===================================================== --}}
        <div class="mb-8 border-t pt-6 border-gray-100">
            <h3 class="text-base md:text-lg font-medium text-gray-700 mb-4 flex items-center gap-2 text-blue-600">
                <i class="fa-solid fa-signature"></i> Otoritas Penandatangan & Penutup Surat
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Jabatan Penandatangan</label>
                    <input type="text" name="jabatan_pejabat" value="{{ old('jabatan_pejabat', $pengaturan->jabatan_pejabat ?? 'LURAH ARGOMULYO') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Pejabat (Lurah/Pimpinan)</label>
                    <input type="text" name="nama_pejabat" value="{{ old('nama_pejabat', $pengaturan->nama_pejabat ?? 'SUGIRAN, S.IP') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-semibold" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">NIP Pejabat</label>
                    <input type="text" name="nip_pejabat" value="{{ old('nip_pejabat', $pengaturan->nip_pejabat ?? '197405122002121003') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah Tanda Tangan (TDD)</label>
                    <select name="jumlah_tdd" class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                        <option value="1" {{ old('jumlah_tdd', $pengaturan->jumlah_tdd ?? '1') == '1' ? 'selected' : '' }}>1 TDD (Hanya Pimpinan / Lurah)</option>
                        <option value="2" {{ old('jumlah_tdd', $pengaturan->jumlah_tdd ?? '1') == '2' ? 'selected' : '' }}>2 TDD (Warga Pemohon & Lurah)</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Kalimat Penutup Standar Surat</label>
                    <input type="text" name="kalimat_penutup" value="{{ old('kalimat_penutup', $pengaturan->kalimat_penutup ?? 'Demikian surat keterangan ini kami sampaikan dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- SECTION 3: INFORMASI KONTAK & OPERASIONAL             --}}
        {{-- ===================================================== --}}
        <div class="mb-8 border-t pt-6 border-gray-100">
            <h3 class="text-base md:text-lg font-medium text-gray-700 mb-4 flex items-center gap-2 text-blue-600">
                <i class="fa-solid fa-address-book"></i> Kontak & Jam Operasional Kantor
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1">
                        <i class="fa-solid fa-phone text-gray-500 text-xs"></i> Kontak Telepon
                    </label>
                    <input type="text" name="kontak_instansi" value="{{ old('kontak_instansi', $pengaturan->kontak_instansi ?? '(+62) 1234-5678-2322') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1">
                        <i class="fa-solid fa-envelope text-gray-500 text-xs"></i> Email Kelurahan
                    </label>
                    <input type="email" name="email_kelurahan" value="{{ old('email_kelurahan', $pengaturan->email_kelurahan ?? 'kelurahan@example.com') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1 flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-gray-500 text-xs"></i> Detail Lokasi / Koordinat
                    </label>
                    <input type="text" name="detail_lokasi" value="{{ old('detail_lokasi', $pengaturan->detail_lokasi ?? 'JL. kelurahan, RT 05, RW 20') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Senin - Jumat</label>
                    <input type="text" name="jam_kerja_regular" value="{{ old('jam_kerja_regular', $pengaturan->jam_kerja_regular ?? '08:00 - 16:00 WIB') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Sabtu</label>
                    <input type="text" name="jam_kerja_sabtu" value="{{ old('jam_kerja_sabtu', $pengaturan->jam_kerja_sabtu ?? '08:00 - 12:00 WIB') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Minggu</label>
                    <input type="text" name="jam_kerja_minggu" value="{{ old('jam_kerja_minggu', $pengaturan->jam_kerja_minggu ?? 'Tutup') }}"
                           class="block w-full px-3 py-2.5 border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>
        </div>

        {{-- Tombol Simpan Akhir --}}
        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-7 rounded-xl shadow-md text-sm transition">
                Simpan Perubahan
            </button>
        </div>

    </div>
</form>