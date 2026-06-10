<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auth | Desa Digital</title>

    @vite('resources/css/app.css')

    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs"
        type="module"></script>
</head>

<body class="overflow-hidden">

    <div class="flex min-h-screen">

        {{-- ========================= --}}
        {{-- LEFT SIDE --}}
        {{-- ========================= --}}
        <div class="hidden lg:flex w-1/2 relative bg-blue-50 items-center justify-center overflow-hidden">

            {{-- Background Circular Lines --}}
            <div class="absolute inset-0">
                {{-- Circle 1 --}}
                <div class="absolute -left-40 top-1/2 -translate-y-1/2 w-[900px] h-[900px] rounded-full border border-blue-100"></div>
                {{-- Circle 2 --}}
                <div class="absolute -left-28 top-1/2 -translate-y-1/2 w-[760px] h-[760px] rounded-full border border-blue-100"></div>
                {{-- Circle 3 --}}
                <div class="absolute -left-16 top-1/2 -translate-y-1/2 w-[620px] h-[620px] rounded-full border border-blue-100"></div>
                {{-- Circle 4 --}}
                <div class="absolute -left-4 top-1/2 -translate-y-1/2 w-[480px] h-[480px] rounded-full border border-blue-100"></div>
                {{-- Circle 5 --}}
                <div class="absolute left-8 top-1/2 -translate-y-1/2 w-[340px] h-[340px] rounded-full border border-blue-100"></div>
            </div>

            {{-- Soft Blur --}}
            <div class="absolute top-10 left-10 w-72 h-72 bg-blue-200 rounded-full blur-3xl opacity-40"></div>
            <div class="absolute bottom-10 right-10 w-72 h-72 bg-cyan-200 rounded-full blur-3xl opacity-40"></div>

            {{-- Main Content --}}
            <div class="relative z-10 flex flex-col items-center w-full max-w-2xl px-6">

                {{-- Lottie Besar --}}
                <div class="w-[700px] h-[700px] -my-20 flex justify-center items-center">
                    <dotlottie-player
                        src="{{ asset('images/3cc5f6fe-117f-11ee-8951-97f878212d11.lottie') }}"
                        background="transparent"
                        speed="1"
                        style="width: 100%; height: 100%;"
                        loop
                        autoplay>
                    </dotlottie-player>
                </div>

                <p class="mt-2 text-center text-slate-600 text-lg font-medium leading-relaxed max-w-lg">
                    Mempermudah pelayanan administrasi desa melalui
                    sistem digital yang cepat, aman, transparan,
                    dan dapat diakses kapan saja.
                </p>

            </div>

        </div>
       

        {{-- ========================= --}}
        {{-- RIGHT SIDE --}}
        {{-- ========================= --}}
        <div class="w-full lg:w-1/2 bg-white flex items-center justify-center px-8 lg:px-20 relative">

            {{-- Background Soft --}}
            <div class="absolute top-0 right-0 w-80 h-80 bg-blue-100 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-cyan-100 rounded-full blur-3xl opacity-60"></div>

            <div class="relative z-10 w-full max-w-md">
                @yield('content')
            </div>

        </div>

    </div>

</body>
</html>