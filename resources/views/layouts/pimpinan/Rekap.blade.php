<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Digitalisasi Surat' }}</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="flex min-h-screen">

        {{-- 🛠 OTOMATIS PILIH SIDEBAR BERDASARKAN ROLE --}}
        @if(auth()->user()->role === 'pimpinan')
            @include('components.pimpinan.sidebar')
        @else
            @include('components.sidebar_admin') {{-- Sesuaikan nama file sidebar admin kamu --}}
        @endif

        {{-- MAIN CONTENT --}}
        <main class="flex-1 min-h-screen overflow-x-hidden">

            {{-- 🛠 OTOMATIS PILIH NAVBAR BERDASARKAN ROLE --}}
            @if(auth()->user()->role === 'pimpinan')
                @include('components.pimpinan.navbar')
            @else
                @include('components.navbar_admin') {{-- Sesuaikan nama file navbar admin kamu --}}
            @endif

            {{-- PAGE WRAPPER --}}
            <div class="p-4 sm:p-6 space-y-6">
                <div class="w-full min-w-0">
                        @include('components.Admin.Rekap.rekap_statistik')
                    </div>

                {{-- CONTENT --}}
                @yield('content')

                {{-- STATISTIK & REKAP ADMIN (Otomatis tampil di kedua halaman) --}}
                <div class="space-y-6">
                    @include('components.Admin.Rekap.rekap')
                </div>

            </div>
        </main>

    </div>

</body>
</html>