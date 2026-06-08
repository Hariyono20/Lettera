<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pelayanan Administrasi Surat Kelurahan' }}</title>

    {{-- Tailwind CSS --}}
    @vite('resources/css/app.css')

    {{-- Font Awesome --}}
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="flex h-screen bg-gray-50 overflow-hidden">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Navbar --}}
        @include('components.navbar')

        {{-- Content Section --}}
        <main class="flex-1 p-4 md:p-6 overflow-auto">

            {{-- Statistik --}}
            @include('components.Pengguna.Dashboard.statistik')

            {{-- WRAPPER UTAMA → Distribusi Proporsi Dua Kolom yang Ideal --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

                {{-- Kolom Kiri: Tabel Pengajuan Terbaru (Mengambil Porsi Lebih Luas: 2/3) --}}
                <div class="lg:col-span-2 w-full">
                    @include('components.Pengguna.pengajuan')
                </div>

                {{-- Kolom Kanan: Tracking & Support (Mengambil Porsi Pas: 1/3) --}}
                <div class="flex flex-col gap-6 w-full">

                    {{-- Card Tracking (Wrapper kaku dihapus agar fleksibel mengikuti kolom) --}}
                    <div class="w-full">
                        @include('components.Pengguna.tracking')
                    </div>

                    {{-- Card Support (Wrapper kaku dihapus agar fleksibel mengikuti kolom) --}}
                    <div class="w-full">
                        @include('components.Pengguna.support')
                    </div>

                </div>
            </div>

        </main>

        {{-- Footer --}}
        <footer class="bg-white border-t border-gray-100 py-3.5 text-center flex-shrink-0">
            <small class="text-xs text-gray-500 font-medium tracking-wide">
                © {{ date('Y') }} Sistem Pelayanan Administrasi Kelurahan Argomulyo. All rights reserved.
            </small>
        </footer>

    </div>

</body>
</html>