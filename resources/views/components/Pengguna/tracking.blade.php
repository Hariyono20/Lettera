<div class="w-full lg:max-w-[360px]">
    <div class="bg-white shadow-xl rounded-3xl p-5 border border-gray-100/70 font-inter h-full min-h-[450px] flex flex-col justify-between">

        @if($riwayatTerakhir->count() > 0)

            @php
                $suratTerbaru = $riwayatTerakhir->first();
                $statusActive = $suratTerbaru->status;
            @endphp

            <div>

                {{-- Header --}}
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">
                    Status Tracking
                </h2>

                <p class="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-xl mt-3 w-fit max-w-full truncate">
                    {{ $suratTerbaru->jenisSurat->nama_surat }}
                </p>

                <hr class="my-5 border-gray-100">

                {{-- Timeline --}}
                <div class="relative pl-1 flex flex-col gap-5">

                    {{-- Garis --}}
                    <div class="absolute left-[10px] top-3 bottom-3 w-[2px] bg-gray-100 -z-0"></div>

                    {{-- Tahap 5 --}}
                    <div class="flex gap-4 items-start relative z-10">

                        @if($statusActive === 'selesai')

                            <i class="fa-solid fa-circle-check text-green-500 text-lg bg-white shadow-sm rounded-full mt-0.5"></i>

                            <div>
                                <p class="text-[15px] font-bold text-gray-900 leading-tight">
                                    Selesai
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($suratTerbaru->updated_at)->translatedFormat('d M Y') }}
                                </p>
                            </div>

                        @elseif($statusActive === 'ditolak')

                            <i class="fa-solid fa-circle-xmark text-red-500 text-lg bg-white shadow-sm rounded-full mt-0.5"></i>

                            <div>
                                <p class="text-[15px] font-bold text-red-600 leading-tight">
                                    Ditolak
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($suratTerbaru->updated_at)->translatedFormat('d M Y') }}
                                </p>
                            </div>

                        @else

                            <i class="fa-regular fa-circle text-gray-300 text-base bg-white mt-1"></i>

                            <div>
                                <p class="text-[15px] font-medium text-gray-400 leading-tight">
                                    Selesai / Ditolak
                                </p>

                                <p class="text-xs text-gray-300 mt-1">
                                    -
                                </p>
                            </div>

                        @endif

                    </div>

                    {{-- Tahap 4 --}}
                    <div class="flex gap-4 items-start relative z-10">

                        @if(in_array($statusActive, ['proses', 'diproses', 'selesai', 'ditolak']))

                            @php
                                $isPassed = in_array($statusActive, ['selesai', 'ditolak']);
                            @endphp

                            <i class="{{ $isPassed ? 'fa-solid fa-circle-check text-green-500' : 'fa-solid fa-clock text-orange-500 fa-spin-pulse' }} text-lg bg-white shadow-sm rounded-full mt-0.5"></i>

                            <div>
                                <p class="text-[15px] {{ $isPassed ? 'font-semibold text-gray-600' : 'font-bold text-gray-900' }} leading-tight">
                                    Diproses
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $isPassed ? 'Selesai Proses' : 'Sedang Dikerjakan' }}
                                </p>
                            </div>

                        @else

                            <i class="fa-regular fa-circle text-gray-300 text-base bg-white mt-1"></i>

                            <div>
                                <p class="text-[15px] font-medium text-gray-400 leading-tight">
                                    Diproses
                                </p>

                                <p class="text-xs text-gray-300 mt-1">
                                    -
                                </p>
                            </div>

                        @endif

                    </div>

                    {{-- Tahap 3 --}}
                    <div class="flex gap-4 items-start relative z-10">

                        @if(in_array($statusActive, ['menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan', 'proses', 'diproses', 'selesai', 'ditolak']))

                            @php
                                $isPassed = in_array($statusActive, ['proses', 'diproses', 'selesai', 'ditolak']);
                            @endphp

                            <i class="{{ $isPassed ? 'fa-solid fa-circle-check text-green-500' : 'fa-solid fa-clock text-indigo-500 fa-spin-pulse' }} text-lg bg-white shadow-sm rounded-full mt-0.5"></i>

                            <div>
                                <p class="text-[15px] {{ $isPassed ? 'font-semibold text-gray-600' : 'font-bold text-gray-900' }} leading-tight">
                                    Persetujuan Pimpinan
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $isPassed ? 'Disetujui Pimpinan' : 'Menunggu TTD Lurah' }}
                                </p>
                            </div>

                        @else

                            <i class="fa-regular fa-circle text-gray-300 text-base bg-white mt-1"></i>

                            <div>
                                <p class="text-[15px] font-medium text-gray-400 leading-tight">
                                    Persetujuan Pimpinan
                                </p>

                                <p class="text-xs text-gray-300 mt-1">
                                    -
                                </p>
                            </div>

                        @endif

                    </div>

                    {{-- Tahap 2 --}}
                    <div class="flex gap-4 items-start relative z-10">

                        @if(in_array($statusActive, ['verifikasi', 'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan', 'proses', 'diproses', 'selesai', 'ditolak']))

                            @php
                                $isPassed = in_array($statusActive, ['menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan', 'proses', 'diproses', 'selesai', 'ditolak']);
                            @endphp

                            <i class="{{ $isPassed ? 'fa-solid fa-circle-check text-green-500' : 'fa-solid fa-clock text-blue-500 fa-spin-pulse' }} text-lg bg-white shadow-sm rounded-full mt-0.5"></i>

                            <div>
                                <p class="text-[15px] {{ $isPassed ? 'font-semibold text-gray-600' : 'font-bold text-gray-900' }} leading-tight">
                                    Diverifikasi
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $isPassed ? 'Berkas Valid' : 'Pemeriksaan Berkas' }}
                                </p>
                            </div>

                        @else

                            <i class="fa-regular fa-circle text-gray-300 text-base bg-white mt-1"></i>

                            <div>
                                <p class="text-[15px] font-medium text-gray-400 leading-tight">
                                    Diverifikasi
                                </p>

                                <p class="text-xs text-gray-300 mt-1">
                                    -
                                </p>
                            </div>

                        @endif

                    </div>

                    {{-- Tahap 1 --}}
                    <div class="flex gap-4 items-start relative z-10">

                        @php
                            $isPassed = in_array($statusActive, [
                                'verifikasi',
                                'menunggu_persetujuan',
                                'menunggu_persetujuan_pimpinan',
                                'persetujuan',
                                'proses',
                                'diproses',
                                'selesai',
                                'ditolak'
                            ]);
                        @endphp

                        <i class="{{ $isPassed ? 'fa-solid fa-circle-check text-green-500' : 'fa-solid fa-spinner fa-spin text-yellow-500' }} text-lg bg-white shadow-sm rounded-full mt-0.5"></i>

                        <div>
                            <p class="text-[15px] {{ $isPassed ? 'font-semibold text-gray-600' : 'font-bold text-gray-900' }} leading-tight">
                                Menunggu Verifikasi
                            </p>

                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($suratTerbaru->created_at)->translatedFormat('d M Y') }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Footer Button --}}
            <div class="mt-6 pt-4 border-t border-gray-100">

                <a href="{{ route('riwayat-pengajuan.detail', $suratTerbaru->id) }}"
                    class="block text-center text-sm font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 py-3 rounded-2xl transition">

                    Lihat Detail Pengajuan
                    <i class="fa-solid fa-angle-right ml-1"></i>

                </a>

            </div>

        @else

            {{-- Empty State --}}
            <div class="my-auto py-12 flex flex-col items-center justify-center text-center p-4">

                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-5">
                    <i class="fa-solid fa-route text-gray-300 text-3xl"></i>
                </div>

                <h3 class="text-base font-bold text-gray-800">
                    Tidak Ada Tracking
                </h3>

                <p class="text-sm text-gray-400 mt-2 max-w-[220px] leading-relaxed">
                    Belum ada data riwayat pengajuan aktif untuk dilacak saat ini.
                </p>

            </div>

        @endif

    </div>
</div>