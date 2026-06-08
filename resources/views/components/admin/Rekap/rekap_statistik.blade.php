{{-- resources/views/components/admin/rekap/rekap_statistik.blade.php --}}
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

    {{-- Total Pengajuan --}}
    <div class="bg-white shadow-md rounded-xl p-5 flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">Total Pengajuan</p>
            <h2 class="text-3xl font-bold">{{ $statistik['total'] }}</h2>
            <div class="flex items-center gap-1 text-[#16A34A] text-xs mt-1">
                <i class="fa-solid fa-arrow-up"></i>
                <span>Aktif</span>
            </div>
        </div>
        <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
            <i class="fa-solid fa-file-alt text-2xl"></i>
        </div>
    </div>

    {{-- Selesai --}}
    <div class="bg-white shadow-md rounded-xl p-5 flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">Selesai</p>
            <h2 class="text-3xl font-bold">{{ $statistik['selesai'] }}</h2>
            <div class="flex items-center gap-1 text-[#16A34A] text-xs mt-1">
                <i class="fa-solid fa-check"></i>
                <span>Arsip resmi</span>
            </div>
        </div>
        <div class="bg-green-100 text-green-600 p-3 rounded-xl">
            <i class="fa-solid fa-check text-2xl"></i>
        </div>
    </div>

    {{-- Sedang Diproses --}}
    <div class="bg-white shadow-md rounded-xl p-5 flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">Sedang Diproses</p>
            <h2 class="text-3xl font-bold">{{ $statistik['proses'] }}</h2>
            <div class="flex items-center gap-1 text-indigo-600 text-xs mt-1">
                <i class="fa-solid fa-sync"></i>
                <span>Butuh tindakan</span>
            </div>
        </div>
        <div class="bg-indigo-100 text-indigo-600 p-3 rounded-xl">
            <i class="fa-solid fa-spinner text-2xl"></i>
        </div>
    </div>

    {{-- Ditolak --}}
    <div class="bg-white shadow-md rounded-xl p-5 flex justify-between items-center">
        <div>
            <p class="text-gray-600 text-sm">Ditolak</p>
            <h2 class="text-3xl font-bold">{{ $statistik['ditolak'] }}</h2>
            <div class="flex items-center gap-1 text-red-600 text-xs mt-1">
                <i class="fa-solid fa-times"></i>
                <span>Tidak valid</span>
            </div>
        </div>
        <div class="bg-red-100 text-red-600 p-3 rounded-xl">
            <i class="fa-solid fa-times text-2xl"></i>
        </div>
    </div>

</div>