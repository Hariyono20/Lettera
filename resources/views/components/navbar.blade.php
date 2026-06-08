<header class="bg-white shadow px-4 md:px-6 py-4 
                flex flex-col md:flex-row md:justify-between md:items-center gap-4">

    {{-- Judul --}}
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            {{ $title ?? 'Dashboard Penduduk' }}
        </h1>
        <p class="text-gray-500 text-sm">
            Selamat datang kembali, kelola semua aktivitas desa
        </p>
    </div>

    {{-- Right area --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end 
                gap-4 md:gap-6 w-full md:w-auto">

        {{-- Tombol Ajukan --}}
        <a href="{{ route('pengajuan.surat') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-xl 
                   flex items-center gap-2 hover:bg-blue-700 transition 
                   w-full sm:w-auto justify-center text-xs font-semibold shadow-sm">
            <i class="fas fa-plus"></i>
            <span>Ajukan Surat Baru</span>
        </a>

        {{-- Profil --}}
        <a href="{{ route('profil.saya') }}"
           class="flex items-center gap-3 group
                  w-full sm:w-auto justify-center sm:justify-start">

            {{-- Foto Profil Dinamis --}}
            <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 shadow-sm flex-shrink-0">
                <img src="{{ auth()->user()->foto ? asset('storage/foto/' . auth()->user()->foto) : asset('default/avatar.png') }}" 
                     alt="Foto Profil" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-200">
            </div>

            {{-- Info user muncul di layar > sm --}}
            <div class="hidden sm:flex flex-col leading-tight min-w-0 max-w-[150px]">
                <span class="font-semibold text-xs text-gray-900 truncate group-hover:text-blue-600 transition">
                    {{ auth()->user()->nama ?? 'Penduduk' }}
                </span>
                
                {{-- NIK Tersembunyi (Hanya 3 Angka Terakhir) --}}
                <span class="text-[11px] text-gray-400 font-medium truncate mt-0.5">
                    @if(auth()->user()->nik)
                        NIK #{{ str_repeat('*', strlen(auth()->user()->nik) - 3) . substr(auth()->user()->nik, -3) }}
                    @else
                        NIK #-
                    @endif
                </span>
            </div>

        </a>

    </div>
</header>