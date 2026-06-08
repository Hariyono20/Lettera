{{-- resources/views/components/admin/dashboard/_stats_cards.blade.php --}}
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    {{-- Total Pengajuan --}}
    <div class="bg-white shadow-md rounded-xl p-5 flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">Total Pengajuan</p>
            <h2 class="text-3xl font-bold">{{ $statistik['total_pengajuan'] }}</h2>
            <div class="flex items-center gap-1 text-[#16A34A] text-xs mt-1">
                <i class="fa-solid fa-arrow-up"></i>
                <span>Bulan ini</span>
            </div>
        </div>
        <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
            <i class="fa-solid fa-file-alt text-2xl"></i>
        </div>
    </div>

    {{-- Menunggu Verifikasi --}}
    <div class="bg-white shadow-md rounded-xl p-5 flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">Menunggu Verifikasi</p>
            <h2 class="text-3xl font-bold">{{ $statistik['menunggu_verifikasi'] }}</h2>
            <div class="flex items-center gap-1 text-yellow-600 text-xs mt-1">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span>Perlu tindakan</span>
            </div>
        </div>
        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-xl">
            <i class="fa-solid fa-clock text-2xl"></i>
        </div>
    </div>

    {{-- Sedang Diproses --}}
    <div class="bg-white shadow-md rounded-xl p-5 flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">Sedang Diproses</p>
            <h2 class="text-3xl font-bold">{{ $statistik['sedang_diproses'] }}</h2>
            <div class="flex items-center gap-1 text-indigo-600 text-xs mt-1">
                <i class="fa-solid fa-sync"></i>
                <span>Dalam progress</span>
            </div>
        </div>
        <div class="bg-indigo-100 text-indigo-600 p-3 rounded-xl">
            <i class="fa-solid fa-spinner text-2xl"></i>
        </div>
    </div>

    {{-- Surat Selesai --}}
    <div class="bg-white shadow-md rounded-xl p-5 flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">Surat Selesai</p>
            <h2 class="text-3xl font-bold">{{ $statistik['surat_selesai'] }}</h2>
            <div class="flex items-center gap-1 text-[#16A34A] text-xs mt-1">
                <i class="fa-solid fa-check"></i>
                <span>Bulan ini</span>
            </div>
        </div>
        <div class="bg-green-100 text-green-600 p-3 rounded-xl">
            <i class="fa-solid fa-check text-2xl"></i>
        </div>
    </div>

</div>