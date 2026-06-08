<div class="bg-white shadow-lg rounded-2xl p-5 w-full mt-6">

    {{-- Judul dan Tombol Lihat Semua --}}
    <div class="flex justify-between items-center mb-3">
        <h2 class="text-xl font-semibold text-gray-700">Permohonan Terbaru</h2>
        <a href="{{ route('admin.permohonan') }}" class="text-sm text-blue-600 font-semibold hover:underline">Lihat Semua →</a>
    </div>

    <hr class="mb-4 border-gray-300">

    {{-- Container table --}}
    <div class="w-full overflow-x-auto">
        <div class="min-w-[900px]">

            {{-- Header Tabel --}}
            <div class="grid grid-cols-6 gap-3 text-gray-600 text-[15px] font-semibold mb-3 px-1">
                <p>Nama Penduduk</p>
                <p>NIK</p>
                <p>Jenis Surat</p>
                <p>Tanggal Masuk</p>
                <p>Status</p>
                <p class="text-center">Aksi</p>
            </div>

            {{-- List Data (Maksimal 5) --}}
            <div class="space-y-3 text-[14px] text-gray-700">

                @forelse ($permohonanTerbaru as $item)
                    @php
                        // 1. Convert JSON ke Array PHP (Mengantisipasi jika di DB tipenya masih text string)
                        $rawArray = is_string($item->data_surat) ? json_decode($item->data_surat, true) : $item->data_surat;
                        
                        // 2. Ubah semua KEY di dalam array menjadi HURUF KECIL agar kebal dari variasi kapital
                        $dataSuratArray = is_array($rawArray) ? array_change_key_case($rawArray, CASE_LOWER) : [];
                        
                        // 3. Ambil data Nama dengan fallback huruf kecil
                        $namaDiSurat = $dataSuratArray['nama'] ?? 
                                       $dataSuratArray['nama_lengkap'] ?? 
                                       $dataSuratArray['nama_penduduk'] ?? 
                                       ($item->user->name ?? 'Tidak Ada Nama');
                        
                        // 4. Ambil data NIK dengan fallback huruf kecil (Menyelesaikan masalah NIK tidak muncul)
                        $nikDiSurat = $dataSuratArray['nik'] ?? 
                                      $dataSuratArray['nik_penduduk'] ?? 
                                      $dataSuratArray['no_nik'] ?? 
                                      $dataSuratArray['nomor_nik'] ?? 
                                      ($item->user->nik ?? '-');
                        
                        // 5. Penentuan warna badge status
                        $badgeClass = match($item->status) {
                            'diajukan', 'pending' => 'bg-blue-100 text-blue-800',
                            'verifikasi_admin', 'verifikasi' => 'bg-indigo-100 text-indigo-800',
                            'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan' => 'bg-purple-100 text-purple-800',
                            'proses', 'diproses' => 'bg-yellow-100 text-yellow-800',
                            'selesai' => 'bg-green-100 text-green-800',
                            'ditolak' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800'
                        };

                        // 6. Penentuan label text status
                        $statusLabel = match($item->status) {
                            'diajukan', 'pending' => 'Baru',
                            'verifikasi_admin', 'verifikasi' => 'Verifikasi',
                            'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan' => 'Persetujuan',
                            'proses', 'diproses' => 'Proses',
                            'selesai' => 'Selesai',
                            'ditolak' => 'Ditolak',
                            default => str_replace('_', ' ', $item->status)
                        };
                    @endphp

                    {{-- Item Row --}}
                    <div class="grid grid-cols-6 gap-3 items-center bg-gray-50 p-3 rounded-xl hover:bg-gray-100 transition-all">
                        <p class="font-medium text-gray-800 truncate pr-2" title="{{ $namaDiSurat }}">{{ $namaDiSurat }}</p>
                        <p class="text-gray-500 font-mono font-medium">{{ $nikDiSurat }}</p>
                        <p class="font-medium text-gray-700">{{ $item->jenisSurat->nama_surat ?? 'N/A' }}</p>
                        <p class="text-gray-500 text-xs">{{ $item->created_at ? $item->created_at->translatedFormat('d M Y') : '-' }}</p>
                        
                        <div>
                            <div class="rounded-lg px-3 py-1 font-semibold text-center w-fit text-xs {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </div>
                        </div>
                        
                        <a href="{{ route('admin.riwayat-pengajuan.detail', $item->id) }}" class="text-blue-600 font-semibold hover:underline text-center">Lihat & Proses</a>
                    </div>

                @empty
                    <div class="py-10 text-center bg-gray-50 rounded-xl">
                        <p class="text-gray-400 text-sm italic">Belum ada data permohonan baru masuk.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</div>