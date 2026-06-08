<header class="bg-white shadow px-4 md:px-6 py-4 
                flex flex-col md:flex-row md:justify-between md:items-center gap-4">

    {{-- Judul --}}
    <div>
        <h1 class="text-xl font-bold text-gray-900">
            {{ $title ?? 'Dashboard Admin' }}
        </h1>
        <p class="text-gray-500 text-sm">
            Selamat datang kembali, kelola semua aktivitas desa
        </p>
    </div>

    {{-- Right area --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end 
                gap-4 md:gap-6 w-full md:w-auto">

        {{-- Profil Admin dengan Dropdown (Alpine.js) --}}
        <div class="relative flex items-center justify-center sm:justify-end w-full sm:w-auto" 
             x-data="{ openProfile: false }">
             
            <button @click="openProfile = !openProfile" 
                    @click.away="openProfile = false"
                    class="flex items-center gap-3 p-1.5 px-3 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-200 transition-all duration-200 focus:outline-none">
                
                {{-- Indikator Status Aktif (Lingkaran Hijau Berkedip) --}}
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold uppercase shadow-sm">
                        {{ substr(auth()->user()->nama, 0, 2) }}
                    </div>
                    {{-- Efek Ping Status Aktif --}}
                    <span class="absolute bottom-0 right-0 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500 border-2 border-white"></span>
                    </span>
                </div>

                {{-- Info Ringkas User --}}
                <div class="hidden sm:flex flex-col leading-tight text-left">
                    <span class="font-semibold text-gray-900 capitalize text-sm">
                        {{ auth()->user()->nama }}
                    </span>
                    <span class="text-[11px] text-gray-400 font-medium capitalize tracking-wider">
                        {{ auth()->user()->role }}
                    </span>
                </div>
                
                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                   :class="openProfile ? 'rotate-180' : ''"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="openProfile"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                 style="display: none;">
                
                {{-- Header Dropdown --}}
                <div class="px-4 py-2 border-b border-gray-50">
                    <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Akun Login</p>
                    <p class="text-xs text-gray-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                    
                    {{-- Badge Status --}}
                    <div class="mt-2 flex items-center gap-1.5">
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-green-50 text-green-700 rounded-full border border-green-200 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Sesi Aktif
                        </span>
                    </div>
                </div>

                {{-- Menu Pilihan --}}
                <div class="p-1">
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Apakah Anda yakin ingin keluar?')"
                                class="flex items-center gap-2 w-full text-left px-3 py-2 text-xs text-red-600 hover:bg-red-50 rounded-lg transition font-medium">
                            <i class="fa-solid fa-sign-out-alt w-4"></i>
                            Keluar Aplikasi
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</header>