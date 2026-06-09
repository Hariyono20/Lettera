@php
    $title = 'Riwayat Persetujuan';
@endphp

{{-- Container Filter: Kompak & Ringkas --}}
<div class="bg-white p-4 w-full mb-4 rounded-xl shadow-sm border border-slate-200/80 mt-4">
    <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-end">
        
        {{-- Filter Status Riwayat --}}
        <div class="w-full md:w-[240px] shrink-0">
            <label class="text-slate-500 text-[10px] font-bold tracking-wider uppercase mb-1.5 block">Status Dokumen</label>
            <div class="relative">
                <select id="filter-status" class="w-full h-9 pl-3 pr-8 border border-slate-200 bg-slate-50/50 text-slate-700 text-xs rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition cursor-pointer font-medium appearance-none">
                    <option value="">Semua Status Riwayat</option>
                    <option value="proses">Diteruskan ke Admin (Proses)</option>
                    <option value="selesai">Selesai / Rampung</option>
                    <option value="ditolak">Ditolak</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2.5 pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        {{-- Input Pencarian --}}
        <div class="flex-1">
            <label class="text-slate-500 text-[10px] font-bold tracking-wider uppercase mb-1.5 block">Cari Arsip Pemohon</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" id="search-input" placeholder="Cari riwayat berdasarkan nama atau NIK..." 
                       class="w-full h-9 pl-9 pr-3 border border-slate-200 bg-slate-50/50 rounded-lg text-xs focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition placeholder:text-slate-400 font-medium">
            </div>
        </div>
    </div>
</div>

{{-- Container Utama Tabel Log Riwayat --}}
<div class="bg-white shadow-sm rounded-xl p-4 w-full border border-slate-200/80">
    <div class="mb-3.5">
        <h2 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
            <i class="fas fa-history text-blue-500 text-xs"></i> Log Riwayat Persetujuan Anda
        </h2>
        <p class="text-slate-400 text-[11px] mt-0.5">Menampilkan berkas surat yang telah Anda tanda tangani, disposisi, atau tolak.</p>
    </div>

    <div class="overflow-x-auto">
        <div class="min-w-[1000px]">
            
            {{-- Header Tabel Proporsional (Total 5 Kolom setelah NIK mandiri) --}}
            <div class="grid grid-cols-[2fr_1.5fr_2fr_1.5fr_1fr] gap-4 text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-2 px-3">
                <div>Nama Pemohon</div>
                <div>NIK</div>
                <div>Jenis Layanan Surat</div>
                <div>Tanggal Aksi</div>
                <div>Status Saat Ini</div>
            </div>

            {{-- List Data Body --}}
            <div id="table-body" class="space-y-1.5">
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
                            'selesai'            => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                            'ditolak'            => 'bg-rose-50 text-rose-700 border-rose-200/60',
                            default              => 'bg-slate-50 text-slate-700 border-slate-200/60'
                        };
                    @endphp

                    {{-- Baris Data Clean (Tanpa Avatar, NIK Mandiri) --}}
                    <div class="row-item grid grid-cols-[2fr_1.5fr_2fr_1.5fr_1fr] gap-4 items-center bg-white border border-slate-100 hover:border-blue-300 p-2.5 px-3 rounded-lg hover:shadow-sm transition-all" 
                         data-status="{{ $item->status }}">
                        
                        {{-- Kolom 1: Nama Pemohon --}}
                        <div class="min-w-0">
                            <p class="nama-user font-semibold text-slate-700 text-xs truncate" title="{{ $namaDiSurat }}">{{ $namaDiSurat }}</p>
                        </div>

                        {{-- Kolom 2: NIK Mandiri --}}
                        <div class="min-w-0">
                            <p class="nik-user text-slate-600 text-xs font-mono tracking-tight font-medium">{{ $nikDiSurat }}</p>
                        </div>

                        {{-- Kolom 3: Jenis Surat --}}
                        <div class="min-w-0">
                            <p class="text-slate-600 font-medium text-xs truncate" title="{{ $item->jenisSurat->nama_surat ?? 'N/A' }}">
                                {{ $item->jenisSurat->nama_surat ?? $item->jenisSurat->nama_jenis ?? 'N/A' }}
                            </p>
                        </div>

                        {{-- Kolom 4: Tanggal Aksi/Persetujuan --}}
                        <div class="text-slate-500 text-xs font-medium">
                            {{ $item->tanggal_disetujui ? \Carbon\Carbon::parse($item->tanggal_disetujui)->translatedFormat('d M Y - H:i') : ($item->updated_at ? $item->updated_at->translatedFormat('d M Y') : '-') }}
                        </div>

                        {{-- Kolom 5: Status Badge --}}
                        <div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold border tracking-wide inline-block whitespace-nowrap {{ $badgeClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center border border-dashed border-slate-200 rounded-lg bg-slate-50/30">
                        <i class="fas fa-archive text-slate-300 text-xl mb-1 block"></i>
                        <p class="text-slate-400 text-xs italic">Belum ada riwayat persetujuan dokumen.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Pagination Section --}}
    @if ($surats->hasPages())
        <div class="mt-4 pt-3 border-t border-slate-100">
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