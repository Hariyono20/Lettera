<div class="bg-white p-4 w-full max-w-full mx-auto mb-4 rounded-2xl shadow-sm border border-gray-100 mt-5 px-6">
    <div class="flex flex-wrap gap-6 items-end">
        <div class="w-full md:w-auto">
            <label class="text-gray-700 text-xs font-semibold mb-1 block">Filter Status</label>
            <select id="filter-status"
                class="w-full md:w-[250px] h-9 px-3 border border-gray-200 bg-gray-50 text-gray-600 text-xs rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                <option value="">Semua Status</option>
                <option value="diajukan"> Diajukan (Baru)</option>
                <option value="menunggu_persetujuan_pimpinan">Persetujuan Pimpinan</option>
                <option value="diproses">Sedang Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak"> Ditolak</option>
            </select>
        </div>

        <div class="flex-1">
            <label class="text-gray-700 text-xs font-semibold mb-1 block">Cari Pemohon</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="search-input"
                    placeholder="Cari berdasarkan nama atau NIK yang tertera di surat..."
                    class="w-full h-9 pl-9 pr-4 border border-gray-200 bg-gray-50 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none transition-all">
            </div>
        </div>
    </div>
</div>

<div class="bg-white shadow-lg rounded-2xl p-4 w-full max-w-full mx-auto">

    {{-- Judul --}}
    <h2 class="text-lg font-semibold text-gray-700 mb-2">Permohonan Terbaru</h2>

    <hr class="mb-3 border-gray-300">

    {{-- Container table --}}
    <div class="w-full overflow-x-auto">
        <div class="min-w-[1100px]">

            {{-- Header --}}
            <div
                class="grid grid-cols-[2fr_1.5fr_2fr_1.2fr_1fr_1fr] gap-4 text-gray-600 text-[14px] font-semibold mb-2 px-4">
                <p>Nama Penduduk</p>
                <p>NIK</p>
                <p>Jenis Surat</p>
                <p>Tanggal Masuk</p>
                <p>Status</p>
                <p class="text-center">Aksi</p>
            </div>

            {{-- List Data --}}
            <div id="table-body" class="space-y-2.5 text-[13px] text-gray-700">

                @forelse ($rows as $item)
                    @php
                        // 1. Convert JSON ke Array PHP
                        $rawArray = is_string($item->data_surat)
                            ? json_decode($item->data_surat, true)
                            : $item->data_surat;

                        // 2. Ubah semua KEY di dalam array menjadi HURUF KECIL (Mencegah error NIK vs nik vs Nik)
                        $dataSuratArray = is_array($rawArray) ? array_change_key_case($rawArray, CASE_LOWER) : [];

                        // 3. Ambil data Nama dengan fallback (Semua key dicek dalam huruf kecil)
                        $namaDiSurat =
                            $dataSuratArray['nama'] ??
                            ($dataSuratArray['nama_lengkap'] ??
                                ($dataSuratArray['nama_penduduk'] ?? ($item->user->name ?? 'Tidak Ada Nama')));

                        // 4. Ambil data NIK dengan fallback (Semua key dicek dalam huruf kecil)
                        $nikDiSurat =
                            $dataSuratArray['nik'] ??
                            ($dataSuratArray['nik_penduduk'] ??
                                ($dataSuratArray['no_nik'] ??
                                    ($dataSuratArray['nomor_nik'] ?? ($item->user->nik ?? '-'))));

                        // 5. Penentuan warna badge status
                        $badgeClass = match ($item->status) {
                            'diajukan', 'pending' => 'bg-blue-100 text-blue-800',
                            'verifikasi_admin', 'verifikasi' => 'bg-indigo-100 text-indigo-800',
                            'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan' => 'bg-purple-100 text-purple-800',
                            'proses', 'diproses' => 'bg-yellow-100 text-yellow-800',
                            'selesai' => 'bg-green-100 text-green-800',
                            'ditolak' => 'bg-red-100 text-red-800',
                            default => 'bg-gray-100 text-gray-800',
                        };

                        // 6. Penentuan label text status
                        $statusLabel = match ($item->status) {
                            'diajukan', 'pending' => 'Baru',
                            'verifikasi_admin', 'verifikasi' => 'Verifikasi',
                            'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan' => 'Persetujuan',
                            'proses', 'diproses' => 'Proses',
                            'selesai' => 'Selesai',
                            'ditolak' => 'Ditolak',
                            default => str_replace('_', ' ', $item->status),
                        };
                    @endphp

                    {{-- Item Row --}}
                    <div class="row-item grid grid-cols-[2fr_1.5fr_2fr_1.2fr_1fr_1fr] gap-4 items-center bg-gray-50 p-3.5 rounded-xl hover:bg-gray-100 transition-all shadow-sm"
                        data-status="{{ $item->status }}">
                        <p class="font-semibold text-gray-800 truncate pr-2" title="{{ $namaDiSurat }}">
                            {{ $namaDiSurat }}</p>
                        <p class="text-gray-500 font-mono font-medium">{{ $nikDiSurat }}</p>
                        <p class="font-medium text-gray-700">{{ $item->jenisSurat->nama_surat ?? 'N/A' }}</p>
                        <p class="text-gray-500 text-[11px]">
                            {{ $item->created_at ? $item->created_at->translatedFormat('d M Y') : '-' }}</p>

                        <div>
                            <div
                                class="rounded-lg px-2 py-0.5 font-semibold text-center w-full max-w-[110px] text-[11px] {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('admin.riwayat-pengajuan.detail', $item->id) }}"
                                class="text-blue-600 font-semibold hover:underline inline-block text-xs">Lihat &
                                Proses</a>
                        </div>
                    </div>

                @empty
                    <div class="py-10 text-center bg-gray-50 rounded-xl">
                        <p class="text-gray-400 text-xs italic">Belum ada data permohonan masuk.</p>
                    </div>
                @endforelse

            </div>

        </div>
    </div>
    {{-- Pagination Section --}}
    @if ($rows instanceof \Illuminate\Pagination\LengthAwarePaginator && $rows->hasPages())
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between flex-wrap gap-4">
            <p class="text-xs text-gray-500">
                Menampilkan <span class="font-semibold text-gray-700">{{ $rows->firstItem() }}</span>
                sampai <span class="font-semibold text-gray-700">{{ $rows->lastItem() }}</span>
                dari <span class="font-semibold text-gray-700">{{ $rows->total() }}</span> data
            </p>

            <div class="flex items-center space-x-1">
                @if ($rows->onFirstPage())
                    <span
                        class="w-8 h-8 flex items-center justify-center text-xs text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">&lt;</span>
                @else
                    <a href="{{ $rows->previousPageUrl() }}"
                        class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors">&lt;</a>
                @endif

                @foreach ($rows->getUrlRange(max(1, $rows->currentPage() - 2), min($rows->lastPage(), $rows->currentPage() + 2)) as $page => $url)
                    @if ($page == $rows->currentPage())
                        <span
                            class="w-8 h-8 flex items-center justify-center text-xs text-white bg-blue-600 border border-blue-600 rounded-lg font-semibold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                            class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($rows->hasMorePages())
                    <a href="{{ $rows->nextPageUrl() }}"
                        class="w-8 h-8 flex items-center justify-center text-xs text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition-colors">&gt;</a>
                @else
                    <span
                        class="w-8 h-8 flex items-center justify-center text-xs text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">&gt;</span>
                @endif
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('filter-status');
        const rows = document.querySelectorAll('.row-item');

        function filter() {
            const val = searchInput.value.toLowerCase();
            const stat = statusFilter.value;

            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                const status = row.getAttribute('data-status');

                const matchSearch = text.includes(val);

                // Menyamakan kondisi pencocokan filter javascript untuk status multi-alias
                let matchStatus = false;
                if (stat === "") {
                    matchStatus = true;
                } else if (stat === 'diajukan') {
                    matchStatus = (status === 'diajukan' || status === 'pending');
                } else if (stat === 'menunggu_persetujuan_pimpinan') {
                    matchStatus = (status === 'menunggu_persetujuan' || status ===
                        'menunggu_persetujuan_pimpinan');
                } else if (stat === 'diproses') {
                    matchStatus = (status === 'proses' || status === 'diproses');
                } else {
                    matchStatus = (status === stat);
                }

                row.style.display = (matchSearch && matchStatus) ? 'grid' : 'none';
            });
        }

        searchInput.addEventListener('keyup', filter);
        statusFilter.addEventListener('change', filter);
    });
</script>
