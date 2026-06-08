<div class="w-full lg:w-1/3 xl:w-1/4 bg-white border border-gray-200 shadow-sm rounded-2xl p-5 h-fit flex-shrink-0">
    <div class="flex flex-col items-center text-center">

        <div class="w-20 h-20 rounded-full overflow-hidden mb-3 border-4 border-gray-100 shadow-inner">
            <img src="{{ auth()->user()->foto ? asset('storage/foto/' . auth()->user()->foto) : asset('default/avatar.png') }}"
                alt="Foto Profil" class="w-full h-full object-cover">
        </div>

        <h3 class="text-base font-bold text-gray-800 leading-snug">{{ auth()->user()->nama }}</h3>
        
        {{-- NIK Tersembunyi (Hanya 3 Angka Terakhir) --}}
        <p class="text-xs text-gray-400 mt-0.5 tracking-wide">
            @if(auth()->user()->nik)
                NIK #{{ str_repeat('*', strlen(auth()->user()->nik) - 3) . substr(auth()->user()->nik, -3) }}
            @else
                NIK #-
            @endif
        </p>
    </div>

    <div class="space-y-3.5 border-t border-gray-100 pt-5 mt-5">

        <div class="flex items-center text-gray-600 min-w-0">
            <i class="fa-solid fa-envelope text-gray-400 w-4 text-center mr-3 flex-shrink-0 text-xs"></i>
            <p class="text-xs truncate">{{ auth()->user()->email }}</p>
        </div>

        <div class="flex items-center text-gray-600">
            <i class="fa-solid fa-phone text-gray-400 w-4 text-center mr-3 flex-shrink-0 text-xs"></i>
            <p class="text-xs">{{ auth()->user()->no_wa ?? '-' }}</p>
        </div>

        <div class="flex items-start text-gray-600">
            <i class="fa-solid fa-location-dot text-gray-400 w-4 text-center mr-3 flex-shrink-0 text-xs mt-0.5"></i>
            <p class="text-xs leading-relaxed line-clamp-2">{{ auth()->user()->alamat ?? 'Belum mengisi alamat rumah' }}</p>
        </div>

    </div>

    <a href="{{ route('profil.edit') }}"
        class="w-full mt-5 py-2 px-4 bg-gray-50 border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-100 hover:text-gray-900 transition flex items-center justify-center gap-1.5 shadow-sm">
        <i class="fa-solid fa-user-gear text-[11px]"></i>
        Pengaturan Akun
    </a>
</div>