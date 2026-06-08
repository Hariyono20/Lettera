<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Portal' }}</title>

    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Alpine.js untuk Handling Buka-Tutup Sidebar di HP --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden w-full">

        {{-- SIDEBAR WRAPPER (Responsif Drawer) --}}
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" 
             class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 flex-shrink-0">
            @include('components.sidebar_admin')
        </div>

        {{-- BACKDROP OVERLAY (Muncul hanya di HP saat sidebar terbuka) --}}
        <div x-show="sidebarOpen" 
             x-transition:opacity
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-gray-900/40 backdrop-blur-xs lg:hidden">
        </div>

        {{-- MAIN CONTENT AREA CONTAINER --}}
        <div class="flex-1 flex flex-col h-screen min-w-0 overflow-hidden">

            {{-- NAVBAR HEADER --}}
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200 flex-shrink-0">
                @include('components.navbar_admin')
            </header>

            {{-- CONTENT SCROLLABLE AREA --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 focus:outline-none">
                
                {{-- Padding adaptif (p-4 di HP, p-6 di desktop) untuk membungkus konten Surat --}}
                <div class="p-4 md:p-6 max-w-[1600px] mx-auto w-full flex flex-col gap-6">
                    
   
                    <div class="w-full min-w-0">
                        @yield('content')
                    </div>

                    {{-- Komponen Surat --}}
                    <div class="w-full min-w-0">
                        @include('components.Admin.Surat.surat')
                    </div>
                    
                </div>

            </main>

        </div>

    </div>

</body>
</html>