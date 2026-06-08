<div class="py-4 w-full">
    {{-- GRID SYSTEM: Tetap responsif otomatis di semua ukuran layar --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

        {{-- Total Pengajuan --}}
        <div class="bg-white shadow-md rounded-2xl p-5 sm:p-6 min-h-[135px] flex items-center justify-between border border-gray-100 transform hover:scale-[1.01] transition-transform duration-200">
            <div>
                <p class="text-gray-500 text-[14px] sm:text-[15px] font-semibold">Total Pengajuan</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800 leading-tight mt-1">
                    {{ $statistik['total_pengajuan'] }}
                </h2>
                <p class="text-gray-400 text-[12px] font-medium mt-1">Bulan ini</p>
            </div>
            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 ml-3">
                <i class="fa-solid fa-list-check text-xl sm:text-2xl"></i>
            </div>
        </div>

        {{-- Sedang Diproses --}}
        <div class="bg-white shadow-md rounded-2xl p-5 sm:p-6 min-h-[135px] flex items-center justify-between border border-gray-100 transform hover:scale-[1.01] transition-transform duration-200">
            <div>
                <p class="text-gray-500 text-[14px] sm:text-[15px] font-semibold">Sedang Diproses</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800 leading-tight mt-1">
                    {{ $statistik['sedang_diproses'] }}
                </h2>
                <p class="text-gray-400 text-[12px] font-medium mt-1">Dalam antrian</p>
            </div>
            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center flex-shrink-0 ml-3">
                <i class="fa-solid fa-clock text-xl sm:text-2xl"></i>
            </div>
        </div>

        {{-- Menunggu Verifikasi --}}
        <div class="bg-white shadow-md rounded-2xl p-5 sm:p-6 min-h-[135px] flex items-center justify-between border border-gray-100 transform hover:scale-[1.01] transition-transform duration-200">
            <div>
                <p class="text-gray-500 text-[14px] sm:text-[15px] font-semibold">Menunggu Verifikasi</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800 leading-tight mt-1">
                    {{ $statistik['menunggu_verifikasi'] }}
                </h2>
                <p class="text-gray-400 text-[12px] font-medium mt-1">Perlu Tindakan</p>
            </div>
            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center flex-shrink-0 ml-3">
                <i class="fa-solid fa-triangle-exclamation text-xl sm:text-2xl"></i>
            </div>
        </div>

        {{-- Surat Selesai --}}
        <div class="bg-white shadow-md rounded-2xl p-5 sm:p-6 min-h-[135px] flex items-center justify-between border border-gray-100 transform hover:scale-[1.01] transition-transform duration-200">
            <div>
                <p class="text-gray-500 text-[14px] sm:text-[15px] font-semibold">Surat Selesai</p>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-800 leading-tight mt-1">
                    {{ $statistik['surat_selesai'] }}
                </h2>
                <p class="text-gray-400 text-[12px] font-medium mt-1">Siap Diambil</p>
            </div>
            <div class="w-12 sm:w-14 h-12 sm:h-14 bg-green-50 text-green-600 rounded-xl flex items-center justify-center flex-shrink-0 ml-3">
                <i class="fa-solid fa-check-circle text-xl sm:text-2xl"></i>
            </div>
        </div>

    </div>
</div>