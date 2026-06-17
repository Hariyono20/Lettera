@extends('layouts.auth')

{{-- Disarankan memindahkan asset ini ke layout utama, namun jika ingin tetap di sini, gunakan @push --}}
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush
@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    <div class="py-4">
        {{-- Header Login --}}
        <h2 class="text-3xl font-extrabold text-gray-800 text-center mb-2 tracking-tight">
            Selamat Datang
        </h2>
        <p class="text-gray-500 text-center text-sm mb-8 leading-relaxed">
            Silakan masuk untuk melanjutkan akses ke sistem layanan desa
        </p>

        {{-- TAMPILKAN ERROR VALIDASI BAWAAN LARAVEL --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm flex gap-3">
                <i class="fas fa-exclamation-circle text-base text-red-500 mt-0.5 flex-shrink-0"></i>
                <ul class="list-disc list-inside space-y-0.5 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TAMPILKAN FLASH MESSAGE (SUKSES SETELAH REGISTER) --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm font-medium flex items-center gap-3">
                <i class="fas fa-check-circle text-base text-green-500 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ERROR MESSAGE DARI SESSION --}}
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm font-medium flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-base text-red-500 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
            @csrf

            {{-- EMAIL --}}
            <div>
                <label class="font-semibold text-gray-700 text-sm">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh: user@example.com" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                           focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
            </div>

            {{-- PASSWORD + FITUR MATA OTOMATIS (Alpine.js) --}}
            <div x-data="{ show: false, password: '' }">
                <label class="font-semibold text-gray-700 text-sm">Password</label>
                <div class="relative mt-1.5">
                    <input :type="show ? 'text' : 'password'" 
                        name="password" 
                        x-model="password"
                        placeholder="••••••••" 
                        required
                        class="w-full border border-gray-300 rounded-xl pl-4 pr-12 py-3 transition outline-none text-sm text-gray-800
                               focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                    
                    {{-- Tombol Mata --}}
                    <button type="button" 
                        x-show="password.length > 0"
                        x-transition
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none text-base z-10">
                        <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 text-gray-600 cursor-pointer select-none">
                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500/30" name="remember">
                    <span class="text-sm font-medium text-gray-500 hover:text-gray-700 transition">Ingat saya</span>
                </label>
            </div>

            {{-- BUTTON LOGIN --}}
            <button type="submit"
                class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold mt-2 text-base
                       hover:bg-blue-700 active:scale-[0.99] transition shadow-md hover:shadow-lg hover:shadow-blue-600/20
                       flex items-center justify-center gap-2">
                <span>Masuk Ke Sistem</span>
                <i class="fas fa-arrow-right text-xs"></i>
            </button>
        </form>

        {{-- REGISTER LINK --}}
        <p class="text-center text-gray-600 mt-8 text-sm">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-bold">
                Daftar sekarang
            </a>
        </p>
    </div>
@endsection