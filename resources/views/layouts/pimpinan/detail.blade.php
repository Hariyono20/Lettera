<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Pimpinan' }}</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex">

        {{-- SIDEBAR OVERLAY (Mobile) --}}
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false"
             class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden">
        </div>

        {{-- SIDEBAR WRAPPER --}}
        <div class="fixed inset-y-0 left-0 z-30 w-64 transform lg:translate-x-0 transition-transform duration-300 ease-in-out bg-white border-r"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            @include('components.pimpinan.sidebar')
        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col min-w-0 lg:pl-64 transition-all duration-300">

            {{-- NAVBAR --}}
            <header class="bg-white border-b sticky top-0 z-10">
                <div class="flex items-center px-4 py-3">
                    {{-- Tombol Toggle Sidebar --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 mr-3 text-gray-600 hover:text-indigo-600">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <div class="flex-1">
                        @include('components.pimpinan.navbar')
                    </div>
                </div>
            </header>

            {{-- PAGE WRAPPER --}}
            <main class="flex-1 p-4 sm:p-6 overflow-hidden">
                <div class="max-w-7xl mx-auto space-y-6">
                    
                    @yield('content')

                    {{-- STATISTIK/DETAIL --}}
                    <div>
                        @include('components.pimpinan.detail_permohonan')
                    </div>
                </div>
            </main>

            {{-- FOOTER --}}
            <footer class="bg-white border-t py-4 text-center text-xs text-gray-500">
                © {{ date('Y') }} Sistem Administrasi Kelurahan. All rights reserved.
            </footer>

        </div>

    </div>

</body>
</html>