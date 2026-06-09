{{-- Filter + Search + Download --}}
<div class="p-6 bg-white rounded-xl shadow-md">
    
    {{-- Form action menggunakan url()->current() agar otomatis menyesuaikan dengan halaman Admin atau Pimpinan --}}
    <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-end gap-6 mb-6">
        
        {{-- Filter Laporan (Hanya Bulan) --}}
        <div>
            <label for="filter-bulan" class="text-gray-700 text-sm font-medium mb-1 block">Periode Laporan</label>
            <select name="bulan" id="filter-bulan" onchange="this.form.submit()" class="appearance-none w-56 h-10 px-3 border border-gray-300 bg-white text-gray-700 text-sm rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 font-medium">
                <option value="">Semua Bulan</option>
                @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $key => $bulan)
                    <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>{{ $bulan }}</option>
                @endforeach
            </select>
        </div>

        {{-- Search Bar --}}
        <div class="flex-1 min-w-[200px]">
            <label for="search" class="text-gray-700 text-sm font-medium mb-1 block">Pencarian Data</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama pemohon, jenis surat, nomor surat, alamat..." class="w-full h-10 pl-3 pr-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
        </div>

        {{-- Download Buttons --}}
        <div class="flex gap-3 ml-auto">
            <button type="button" id="download-pdf" class="h-10 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 rounded-lg text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </button>
            <button type="button" id="download-excel" class="h-10 bg-green-500 hover:bg-green-600 text-white font-semibold px-4 rounded-lg text-sm flex items-center justify-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Download Excel
            </button>
        </div>
    </form>
</div>

{{-- Table Data Rekap Lengkap --}}
<div class="bg-white shadow-lg rounded-2xl p-5 w-full mt-6">
    <h2 class="text-xl font-semibold text-gray-700 mb-3">Laporan Rekapitulasi Pelayanan Surat</h2>
    <hr class="mb-4 border-gray-300">
    <div class="w-full overflow-x-auto">
        <table class="w-full min-w-[1100px] text-sm text-center text-gray-700 border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 font-semibold uppercase text-xs border-b border-gray-300">
                    <th class="py-3 px-4 text-left">Nama Pemohon</th>
                    <th class="py-3 px-4">Jenis Surat</th>
                    <th class="py-3 px-4">Nomor Surat</th>
                    <th class="py-3 px-4 text-left">Alamat</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Tanggal</th>
                </tr>
            </thead>
            <tbody id="table-body" class="divide-y divide-gray-200">
                @forelse($rekapData as $data)
                    <tr class="row-item bg-white hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-left font-medium text-gray-900 nama-warga">
                            {{ $data->nama_pemohon }}
                        </td>
                        <td class="py-3 px-4 font-medium jenis-surat">
                            {{ $data->jenisSurat->nama_surat ?? 'N/A' }}
                        </td>
                        <td class="py-3 px-4 font-mono text-xs text-gray-600 nomor-surat">
                            {{ $data->nomor_surat_fix }}
                        </td>
                        <td class="py-3 px-4 text-left text-gray-600 alamat-warga">
                            {{ $data->alamat_pemohon }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 capitalize status-surat">
                                {{ $data->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 tgl-selesai">
                            {{ \Carbon\Carbon::parse($data->tanggal_selesai)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-gray-400 italic text-center bg-gray-50">
                            Belum ada rekapitulasi data surat yang diselesaikan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
<div class="max-w-7xl mx-auto flex justify-between items-center mt-6 px-1 text-[14px] text-[#111827] font-inter">
    <div id="pagination-info">Menampilkan 0 hingga 0 dari 0 hasil</div>
    <div class="flex gap-1" id="pagination-controls"></div>
</div>

{{-- Script Pendukung Export Data --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const rows = Array.from(document.querySelectorAll('.row-item'));
    const info = document.getElementById('pagination-info');
    const controls = document.getElementById('pagination-controls');

    const downloadPdfBtn = document.getElementById('download-pdf');
    const downloadExcelBtn = document.getElementById('download-excel');

    let currentPage = 1;
    const rowsPerPage = 10;

    function renderTable() {
        const searchVal = searchInput.value.toLowerCase();

        const filtered = rows.filter(row => {
            const namaText = row.querySelector('.nama-warga').textContent.toLowerCase();
            const jenisText = row.querySelector('.jenis-surat').textContent.toLowerCase();
            const nomorText = row.querySelector('.nomor-surat').textContent.toLowerCase();
            const alamatText = row.querySelector('.alamat-warga').textContent.toLowerCase();
            
            return (searchVal === '' || namaText.includes(searchVal) || jenisText.includes(searchVal) || nomorText.includes(searchVal) || alamatText.includes(searchVal));
        });

        const totalRows = filtered.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;
        if(currentPage > totalPages) currentPage = 1;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach(r => r.style.display = 'none');
        filtered.slice(start, end).forEach(r => r.style.display = '');

        const startRow = totalRows === 0 ? 0 : start + 1;
        const endRow = end > totalRows ? totalRows : end;
        info.textContent = `Menampilkan ${startRow} hingga ${endRow} dari ${totalRows} hasil`;

        controls.innerHTML = '';
        if(totalPages > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.className = 'flex items-center justify-center w-8 h-8 border border-gray-300 rounded hover:bg-gray-100 disabled:opacity-40';
            prevBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>`;
            prevBtn.disabled = currentPage === 1;
            prevBtn.addEventListener('click', () => { currentPage--; renderTable(); });
            controls.appendChild(prevBtn);

            for(let i = 1; i <= totalPages; i++){
                const btn = document.createElement('button');
                btn.className = `flex items-center justify-center w-8 h-8 border rounded font-semibold ${i === currentPage ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-300 text-gray-700 hover:bg-gray-100'}`;
                btn.textContent = i;
                btn.addEventListener('click', () => { currentPage = i; renderTable(); });
                controls.appendChild(btn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.className = 'flex items-center justify-center w-8 h-8 border border-gray-300 rounded hover:bg-gray-100 disabled:opacity-40';
            nextBtn.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>`;
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.addEventListener('click', () => { currentPage++; renderTable(); });
            controls.appendChild(nextBtn);
        }
    }

    // Download Excel
    downloadExcelBtn.addEventListener('click', function() {
        const wb = XLSX.utils.book_new();
        const wsData = [["Nama Pemohon", "Jenis Surat", "Nomor Surat", "Alamat", "Status", "Tanggal Selesai"]];
        const searchVal = searchInput.value.toLowerCase();
        
        rows.forEach(row => {
            const namaText = row.querySelector('.nama-warga').textContent.toLowerCase();
            const jenisText = row.querySelector('.jenis-surat').textContent.toLowerCase();
            const nomorText = row.querySelector('.nomor-surat').textContent.toLowerCase();
            const alamatText = row.querySelector('.alamat-warga').textContent.toLowerCase();
            
            const matchesSearch = (searchVal === '' || namaText.includes(searchVal) || jenisText.includes(searchVal) || nomorText.includes(searchVal) || alamatText.includes(searchVal));

            if(matchesSearch){
                wsData.push([
                    row.querySelector('.nama-warga').textContent.trim(),
                    row.querySelector('.jenis-surat').textContent.trim(),
                    row.querySelector('.nomor-surat').textContent.trim(),
                    row.querySelector('.alamat-warga').textContent.trim(),
                    row.querySelector('.status-surat').textContent.trim(),
                    row.querySelector('.tgl-selesai').textContent.trim()
                ]);
            }
        });
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        XLSX.utils.book_append_sheet(wb, ws, "Rekap Pelayanan");
        XLSX.writeFile(wb, "Rekap_Laporan_Pelayanan_Surat.xlsx");
    });

    // Download PDF
    downloadPdfBtn.addEventListener('click', function() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); 
        let y = 15;
        
        doc.setFontSize(14);
        doc.text("LAPORAN REKAPITULASI PELAYANAN SURAT KELURAHAN ARGOMULYO", 10, y);
        y += 12;
        
        doc.setFontSize(10);
        const headers = ["Nama Pemohon", "Jenis Surat", "Nomor Surat", "Alamat", "Status", "Tanggal"];
        const positions = [10, 55, 100, 165, 225, 250]; 
        
        headers.forEach((h, i) => doc.text(h, positions[i], y));
        y += 4;
        doc.line(10, y, 285, y);
        y += 7;

        const searchVal = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const namaText = row.querySelector('.nama-warga').textContent.toLowerCase();
            const jenisText = row.querySelector('.jenis-surat').textContent.toLowerCase();
            const nomorText = row.querySelector('.nomor-surat').textContent.toLowerCase();
            const alamatText = row.querySelector('.alamat-warga').textContent.toLowerCase();
            
            const matchesSearch = (searchVal === '' || namaText.includes(searchVal) || jenisText.includes(searchVal) || nomorText.includes(searchVal) || alamatText.includes(searchVal));

            if(matchesSearch){
                doc.text(row.querySelector('.nama-warga').textContent.trim().substring(0, 20), positions[0], y);
                doc.text(row.querySelector('.jenis-surat').textContent.trim().substring(0, 20), positions[1], y);
                doc.text(row.querySelector('.nomor-surat').textContent.trim().substring(0, 28), positions[2], y);
                doc.text(row.querySelector('.alamat-warga').textContent.trim().substring(0, 26), positions[3], y);
                doc.text(row.querySelector('.status-surat').textContent.trim(), positions[4], y);
                doc.text(row.querySelector('.tgl-selesai').textContent.trim(), positions[5], y);
                y += 8;
                
                if(y > 190) { 
                    doc.addPage();
                    y = 15;
                }
            }
        });
        doc.save("Rekap_Laporan_Pelayanan_Surat.pdf");
    });

    searchInput.addEventListener('input', () => { currentPage = 1; renderTable(); });
    renderTable();
});
</script>