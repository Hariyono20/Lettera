<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth | Desa Digital</title>

    @vite('resources/css/app.css')
    {{-- FontAwesome jika belum ada --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
</head>

<body class="bg-slate-50 min-h-screen antialiased text-slate-800">

    <div class="flex min-h-screen">

        {{-- ========================= --}}
        {{-- LEFT SIDE (Hanya Desktop) --}}
        {{-- ========================= --}}
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 relative bg-blue-50 items-center justify-center overflow-hidden border-r border-blue-100/50">

            {{-- Background Circular Lines --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -left-40 top-1/2 -translate-y-1/2 w-[900px] h-[900px] rounded-full border border-blue-100/60"></div>
                <div class="absolute -left-28 top-1/2 -translate-y-1/2 w-[760px] h-[760px] rounded-full border border-blue-100/60"></div>
                <div class="absolute -left-16 top-1/2 -translate-y-1/2 w-[620px] h-[620px] rounded-full border border-blue-100/60"></div>
                <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-[480px] h-[480px] rounded-full border border-blue-100/60"></div>
            </div>

            {{-- Soft Blur --}}
            <div class="absolute top-10 left-10 w-72 h-72 bg-blue-200 rounded-full blur-3xl opacity-30"></div>
            <div class="absolute bottom-10 right-10 w-72 h-72 bg-cyan-200 rounded-full blur-3xl opacity-30"></div>

            {{-- Main Content Sisi Kiri (max-w kita naikkan ke max-w-2xl agar Lottie bisa melebar bebas) --}}
            <div class="relative z-10 flex flex-col items-center w-full max-w-2xl px-6 text-center">
                
                {{-- UKURAN LOTTIE DIGEDEIN: Diubah dari 500px ke w-[680px] h-[680px] untuk tampilan super tajam & dominan --}}
                <div class="w-[620px] h-[620px] xl:w-[680px] xl:h-[680px] -my-24 flex justify-center items-center pointer-events-none select-none">
                    <dotlottie-player
                        src="{{ asset('images/3cc5f6fe-117f-11ee-8951-97f878212d11.lottie') }}"
                        background="transparent"
                        speed="1"
                        style="width: 100%; height: 100%;"
                        loop
                        autoplay>
                    </dotlottie-player>
                </div>

                {{-- Teks Deskripsi (Diberi z-index & margin-top pas agar tidak terpotong Lottie) --}}
                <div class="relative z-20 mt-4">
                    <h1 class="text-2xl xl:text-3xl font-extrabold text-blue-900 mb-3 tracking-tight">
                        Sistem Administrasi Desa Digital
                    </h1>
                    <p class="text-slate-600 text-base font-medium leading-relaxed max-w-md mx-auto">
                        Mempermudah pelayanan administrasi desa melalui sistem digital yang cepat, aman, transparan, dan dapat diakses kapan saja.
                    </p>
                </div>
            </div>

        </div>
       

        {{-- ========================= --}}
        {{-- RIGHT SIDE (Form Pendaftaran) --}}
        {{-- ========================= --}}
        <div class="w-full lg:w-7/12 xl:w-1/2 bg-white flex items-center justify-center p-6 sm:p-12 md:p-16 relative min-h-screen overflow-y-auto">

            {{-- Background Soft --}}
            <div class="absolute top-0 right-0 w-80 h-80 bg-blue-50 rounded-full blur-3xl opacity-70 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-cyan-50 rounded-full blur-3xl opacity-70 pointer-events-none"></div>

            <div class="relative z-10 w-full max-w-2xl bg-white lg:bg-transparent p-6 sm:p-8 lg:p-0 rounded-2xl shadow-xl lg:shadow-none border border-gray-100 lg:border-none">
                @yield('content')
            </div>

        </div>

    </div>

</body>
</html>