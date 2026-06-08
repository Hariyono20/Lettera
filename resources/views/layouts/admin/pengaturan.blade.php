<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Portal' }}</title>

    {{-- Vite --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Alpine.js (Sangat disarankan untuk handle buka-tutup sidebar di HP tanpa nulis JS manual) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        {{-- 1. SIDEBAR WRAPPER (Responsif) --}}
        {{-- Di Desktop (lg:) otomatis menetap, di HP (max-lg) jadi Overlay laci geser --}}
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
             class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 flex-shrink-0">
            
            {{-- Mengisi komponen sidebar asli Anda --}}
            @include('components.sidebar_admin')
        </div>

        {{-- 2. BACKDROP OVERLAY (Hanya Muncul di HP saat Sidebar Terbuka) --}}
        <div x-show="sidebarOpen" 
             x-transition:opacity
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-gray-900/40 backdrop-blur-xs lg:hidden">
        </div>

        {{-- 3. MAIN CONTENT AREA --}}
        <div class="flex-1 flex flex-col h-screen min-w-0 overflow-hidden">

            {{-- NAVBAR --}}
            {{-- Catatan: Pastikan di dalam komponen navbar Anda, tombol hamburger menu HP diberikan atribut `@click="sidebarOpen = !sidebarOpen"` --}}
            @include('components.navbar_admin')

            {{-- BODY LAYOUT SCROLLABLE --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 focus:outline-none">
                
                {{-- Page Content dengan padding yang adaptif terhadap ukuran layar --}}
                <div class="p-4 sm:p-5 md:p-6 lg:p-8 max-w-[1600px] mx-auto w-full space-y-6">
                    
                    @yield('content')

                    {{-- Pengaturan Kelurahan --}}
                    @include('components.Admin.Pengaturan.pengaturan')
                    
                </div>
            </main>

        </div>

    </div>

</body>
</html>