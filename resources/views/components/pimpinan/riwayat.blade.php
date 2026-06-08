@php
    $title = 'Riwayat Persetujuan';
@endphp

{{-- Container Filter --}}
<div class="bg-white p-5 w-full mb-6 rounded-2xl shadow-sm border border-gray-100 mt-5">
    <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-end">
        
        {{-- Filter Status Riwayat --}}
        <div class="w-full md:w-[280px] shrink-0">
            <label class="text-gray-700 text-xs font-bold tracking-wide uppercase mb-2 block">Status Dokumen</label>
            <div class="relative">
                <select id="filter-status" class="w-full h-11 pl-4 pr-10 border border-gray-200 bg-gray-50/70 text-gray-700 text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                    <option value="">Semua Status Riwayat</option>
                    <option value="proses">Diteruskan ke Admin (Proses)</option>
                    <option value="selesai">Selesai / Rampung</option>
                    <option value="ditolak">Ditolak</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        {{-- Input Pencarian --}}
        <div class="flex-1">
            <label class="text-gray-700 text-xs font-bold tracking-wide uppercase mb-2 block">Cari Arsip Pemohon</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="search-input" placeholder="Cari riwayat berdasarkan nama atau NIK..." 
                       class="w-full h-11 pl-11 pr-4 border border-gray-200 bg-gray-50/70 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
            </div>
        </div>
    </div>
</div>

{{-- Container Utama Tabel --}}
<div class="bg-white shadow-sm rounded-2xl p-6 w-full border border-gray-100">
    <div class="mb-4">
        <h2 class="text-lg font-bold text-slate-800">Log Riwayat Persetujuan Anda</h2>
        <p class="text-slate-400 text-xs">Menampilkan berkas surat yang telah Anda tanda tangani, disposisi, atau tolak.</p>
    </div>

    <div class="overflow-x-auto">
        <div class="min-w-[1000px]">
            
            {{-- Header Tabel (Diubah menjadi 4 kolom) --}}
            <div class="grid grid-cols-[2.5fr_2fr_1.5fr_1.2fr] gap-4 text-gray-400 text-[11px] font-bold uppercase tracking-wider mb-3 px-4">
                <div>Data Pemohon</div>
                <div>Jenis Surat</div>
                <div>Tanggal Aksi</div>
                <div>Status Saat Ini</div>
            </div>

            <div id="table-body" class="space-y-2.5">
                @forelse ($surats as $item)
                    @php
                        $rawArray = is_string($item->data_surat) ? json_decode($item->data_surat, true) : $item->data_surat;
                        $dataSuratArray = is_array($rawArray) ? array_change_key_case($rawArray, CASE_LOWER) : [];
                        
                        $namaDiSurat = $dataSuratArray['nama'] ?? $dataSuratArray['nama_lengkap'] ?? ($item->user->nama ?? 'Tidak Ada Nama');
                        $nikDiSurat = $dataSuratArray['nik'] ?? $dataSuratArray['no_nik'] ?? ($item->user->nik ?? '-');

                        $statusLabel = match($item->status) { 
                            'proses', 'diproses' => 'Disetujui (Diproses)', 
                            'selesai'            => 'Selesai', 
                            'ditolak'            => 'Ditolak Pimpinan',
                            default              => str_replace('_', ' ', $item->status)
                        };

                        $badgeClass = match($item->status) {
                            'proses', 'diproses' => 'bg-orange-50 text-orange-700 border-orange-200/60',
                            'selesai'            => 'bg-green-50 text-green-700 border-green-200/60',
                            'ditolak'            => 'bg-red-50 text-red-700 border-red-200/60',
                            default              => 'bg-gray-50 text-gray-700 border-gray-200/60'
                        };
                    @endphp

                    {{-- Baris Data (Diubah menjadi 4 kolom, tombol aksi dihapus) --}}
                    <div class="row-item grid grid-cols-[2.5fr_2fr_1.5fr_1.2fr] gap-4 items-center bg-white border border-gray-100 p-3.5 rounded-xl hover:shadow-md hover:border-blue-100 transition-all shadow-sm" 
                         data-status="{{ $item->status }}">
                        
                        {{-- Profil Pemohon --}}
                        <div class="flex items-center space-x-3.5 min-w-0">
                            <div class="w-9 h-9 shrink-0 bg-gradient-to-br from-blue-700 to-indigo-900 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($namaDiSurat, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="nama-user font-semibold text-slate-800 text-sm truncate" title="{{ $namaDiSurat }}">{{ $namaDiSurat }}</p>
                                <p class="nik-user text-slate-400 text-xs mt-0.5 font-mono font-medium">NIK: {{ $nikDiSurat }}</p>
                            </div>
                        </div>

                        {{-- Jenis Surat --}}
                        <div class="min-w-0">
                            <p class="text-slate-700 font-medium text-sm truncate" title="{{ $item->jenisSurat->nama_surat ?? 'N/A' }}">
                                {{ $item->jenisSurat->nama_surat ?? $item->jenisSurat->nama_jenis ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Tanggal Aksi/Persetujuan --}}
                        <div class="text-slate-500 text-xs font-medium">
                            {{ $item->tanggal_disetujui ? \Carbon\Carbon::parse($item->tanggal_disetujui)->translatedFormat('d M Y - H:i') : ($item->updated_at ? $item->updated_at->translatedFormat('d M Y') : '-') }}
                        </div>

                        {{-- Status Badge --}}
                        <div>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase border tracking-wider inline-block whitespace-nowrap {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <p class="text-gray-400 text-sm italic">Belum ada riwayat persetujuan dokumen.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Pagination Section --}}
    @if ($surats->hasPages())
        <div class="mt-6 pt-4 border-t border-gray-100">
            {{ $surats->links() }}
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
                
                let matchStatus = false;
                if (stat === "") {
                    matchStatus = true;
                } else if (stat === 'proses') {
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