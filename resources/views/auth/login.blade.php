@extends('layouts.auth')

{{-- Menyisipkan FontAwesome dan Alpine.js secara mandiri untuk menjamin fitur mata berfungsi --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@section('content')
    <div class="max-w-md mx-auto px-2 py-4">
        <h2 class="text-4xl font-extrabold text-gray-800 text-center mb-2 tracking-tight">
            Selamat Datang
        </h2>
        <p class="text-gray-500 text-center text-base mb-8">
            Silakan masuk untuk melanjutkan akses ke sistem
        </p>

        {{-- TAMPILKAN ERROR VALIDASI BAWAAN LARAVEL --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-5 rounded-lg shadow-sm text-sm">
                <ul class="list-disc list-inside space-y-0.5 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TAMPILKAN FLASH MESSAGE (SUKSES SETELAH REGISTER) --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-5 rounded-lg shadow-sm text-center text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR MESSAGE DARI SESSION --}}
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-5 rounded-lg shadow-sm text-center text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-6">
            @csrf

            {{-- EMAIL --}}
            <div>
                <label class="font-bold text-gray-700 text-base">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh: user@example.com" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800
                           focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
            </div>

            {{-- PASSWORD + FITUR MATA OTOMATIS (Alpine.js) --}}
            <div x-data="{ show: false, password: '' }">
                <label class="font-bold text-gray-700 text-base">Password</label>
                <div class="relative mt-2">
                    <input :type="show ? 'text' : 'password'" 
                        name="password" 
                        x-model="password"
                        placeholder="••••••••" 
                        required
                        class="w-full border border-gray-300 rounded-xl pl-4 pr-12 py-3.5 transition outline-none text-base text-gray-800
                               focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                    
                    {{-- Tombol Mata: Otomatis muncul menggunakan x-show jika password diisi --}}
                    <button type="button" 
                        x-show="password.length > 0"
                        x-transition
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none text-lg z-10">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            {{-- Remember + Forgot --}}
            <div class="flex items-center justify-between text-base">
                <label class="flex items-center gap-2 text-gray-600 cursor-pointer select-none">
                    <input type="checkbox" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-400" name="remember">
                    <span class="text-sm font-medium">Ingat saya</span>
                </label>

                <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">Lupa password?</a>
            </div>

            {{-- BUTTON LOGIN --}}
            <button type="submit"
                class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold mt-2 text-lg
                       hover:bg-blue-700 active:bg-blue-800 transition shadow-md hover:shadow-lg">
                Masuk
            </button>
        </form>

        {{-- REGISTER LINK --}}
        <p class="text-center text-gray-600 mt-8 text-base">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-bold">
                Daftar sekarang
            </a>
        </p>
    </div>
@endsection