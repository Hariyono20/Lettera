@extends('layouts.auth')

@section('content')
    {{-- Pembungkus luar untuk memastikan form memiliki ruang yang luas --}}
    <div class="max-w-3xl mx-auto px-2 py-4">
        <h2 class="text-4xl font-extrabold text-gray-800 text-center mb-2 tracking-tight">
            Daftar Akun Baru
        </h2>
        <p class="text-gray-500 text-center text-base mb-10">
            Lengkapi data diri Anda dengan benar untuk membuat akun penduduk
        </p>

        {{-- Alert Success --}}
        @if (session('success'))
            <div x-data="{ open: true }" x-show="open" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-xl p-8 text-center w-[90%] max-w-md animate-fade-in">
                    <h3 class="text-xl font-bold text-green-600 mb-2">Berhasil!</h3>
                    <p class="text-gray-600 mb-5">{{ session('success') }}</p>

                    <button @click="open = false"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-5 mb-6 rounded-lg shadow-sm text-base">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-lg text-red-500"></i>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-5 mb-6 rounded-lg shadow-sm text-base">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mr-3 mt-1 text-lg text-red-500"></i>
                    <div>
                        <p class="font-semibold mb-2">Terdapat kesalahan pengisian:</p>
                        <ul class="list-disc list-inside space-y-1 text-sm opacity-90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST" class="space-y-6">
            @csrf

            {{-- BARIS 1: NAMA + EMAIL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-bold text-gray-700 text-base">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        placeholder="Masukkan nama lengkap sesuai KTP"
                        class="w-full border rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800
                                  @error('nama') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 @enderror">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-bold text-gray-700 text-base">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="contoh: user@example.com"
                        class="w-full border rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800
                                  @error('email') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- BARIS 2: TANGGAL LAHIR + JENIS KELAMIN (DROPDOWN) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-bold text-gray-700 text-base">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                        max="{{ date('Y-m-d') }}"
                        class="w-full border rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800
                                  @error('tanggal_lahir') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 @enderror">
                    @error('tanggal_lahir')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-bold text-gray-700 text-base">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_kelamin" required
                        class="w-full border rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800 bg-white cursor-pointer
                                  @error('jenis_kelamin') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 @enderror">
                        <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- BARIS 3: NOMOR WHATSAPP --}}
            <div>
                <label class="font-bold text-gray-700 text-base">
                    Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <input type="text" name="no_wa" value="{{ old('no_wa') }}" required placeholder="Contoh: 081234567890"
                    class="w-full border rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800
                              @error('no_wa') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 @enderror">
                @error('no_wa')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-gray-400 text-xs mt-1.5">Gunakan nomor aktif untuk keperluan verifikasi (10-15 digit)</p>
            </div>

            {{-- BARIS 4: ALAMAT LENGKAP --}}
            <div>
                <label class="font-bold text-gray-700 text-base">
                    Alamat Rumah Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea name="alamat" rows="4" required placeholder="Tulis alamat rumah lengkap (Nama jalan, RT/RW, Dusun, Desa)"
                    class="w-full border rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800 resize-none leading-relaxed
                          @error('alamat') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- BARIS 5: PASSWORD + KONFIRMASI PASSWORD --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="font-bold text-gray-700 text-base">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full border rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800
                                  @error('password') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-bold text-gray-700 text-base">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password Anda"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3.5 mt-2 transition outline-none text-base text-gray-800
                                  focus:ring-4 focus:ring-blue-100 focus:border-blue-500">
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <button type="submit"
                class="w-full bg-green-600 text-white py-4 rounded-xl font-bold mt-6 text-lg
                       hover:bg-green-700 active:bg-green-800 transition shadow-md hover:shadow-lg
                       flex items-center justify-center gap-2">
                <i class="fas fa-user-plus text-base"></i>
                <span>Daftar Sekarang</span>
            </button>
        </form>

        <p class="text-center text-gray-600 mt-8 text-base">
            Sudah memiliki akun penduduk?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">
                Masuk di sini
            </a>
        </p>
    </div>
@endsection