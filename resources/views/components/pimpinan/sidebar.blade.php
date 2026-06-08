<aside
    class="bg-white border-r border-gray-200 flex flex-col min-h-screen
           w-[70px] md:w-64 transition-all duration-300 overflow-y-auto">

    {{-- Logo --}}
    <div class="flex items-center p-4">
        <div class="bg-blue-600 rounded-lg p-2 w-12 h-12 flex items-center justify-center mx-auto md:mx-0">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
        </div>

        <div class="hidden md:flex flex-col ml-3">
            <span class="font-bold text-gray-900 text-[15px] leading-tight">Pimpinan Portal</span>
            <span class="text-xs text-gray-400 leading-tight">Desa Digital</span>
        </div>
    </div>

    <hr class="border-gray-200 my-2 hidden md:block">

    {{-- Menu Pimpinan --}}
    @php
        $menus = [
        ['href' => route('pimpinan.dashboard'), 'icon' => 'fa-solid fa-house', 'text' => 'Dashboard'],
        ['href' => route('pimpinan.permohonan'), 'icon' => 'fa-solid fa-file-lines', 'text' => 'Data Permohonan'],
        ['href' => route('pimpinan.riwayat'), 'icon' => 'fa-solid fa-clock-rotate-left', 'text' => 'Riwayat Persetujuan'],
        ['href' => route('pimpinan.rekap'), 'icon' => 'fa-solid fa-chart-pie', 'text' => 'Rekap & Laporan'],
    ];

        $currentUrl = url()->current();
    @endphp

    <nav class="flex-1 px-2 py-4 flex flex-col gap-6">
        @foreach ($menus as $menu)
            @php
                $isActive = $currentUrl === $menu['href'];
            @endphp

            <a href="{{ $menu['href'] }}"
               class="flex items-center gap-4 px-3 py-3 rounded-lg transition-all duration-200
                      {{ $isActive ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">

                <i class="fa {{ $menu['icon'] }} text-xl w-6 
                   text-center md:text-left mx-auto md:mx-0"></i>

                <span class="hidden md:inline text-[15px]">
                    {{ $menu['text'] }}
                </span>
            </a>
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="px-2 py-4 mt-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit"
                onclick="return confirm('Apakah Anda yakin ingin keluar?')"
                class="flex items-center gap-4 w-full px-3 py-3 rounded-lg 
                       text-red-600 hover:bg-gray-100 transition-all duration-200">

                <i class="fa fa-sign-out-alt text-xl w-6 
                      text-center md:text-left mx-auto md:mx-0"></i>

                <span class="hidden md:inline text-[15px] font-medium">Logout</span>
            </button>
        </form>
    </div>

</aside>