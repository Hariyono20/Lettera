<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Digitalisasi Surat' }}</title>

    @vite('resources/css/app.css')
   
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        @include('components.pimpinan.sidebar')

        {{-- MAIN CONTENT --}}
        <main class="flex-1 min-h-screen overflow-x-hidden">

            {{-- NAVBAR --}}
            @include('components.pimpinan.navbar')

            {{-- PAGE WRAPPER --}}
            <div class="p-4 sm:p-6 space-y-6">

                {{-- CONTENT --}}
                @yield('content')

                {{-- STATISTIK ADMIN --}}
                <div class="space-y-6">
                    @include('components.pimpinan.dashboard')
                    @include('components.admin.dashboard.statistik')
                    @include('components.pimpinan.terbaru')
                </div>

            </div>
        </main>

    </div>

</body>
</html>
