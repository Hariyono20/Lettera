@extends('layouts.auth')

@section('content')
    <div class="py-6">
        <h2 class="text-3xl font-extrabold text-gray-800 text-center mb-2 tracking-tight">
            Daftar Akun Baru
        </h2>
        <p class="text-gray-500 text-center text-sm mb-8 leading-relaxed">
            Lengkapi data diri Anda dengan benar sesuai dengan dokumen resmi (KTP/KK)
        </p>

        {{-- Alert Success --}}
        @if (session('success'))
            <div x-data="{ open: true }" x-show="open" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 backrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl p-8 text-center w-[90%] max-w-md animate-fade-in border border-gray-100">
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Berhasil!</h3>
                    <p class="text-gray-600 text-sm mb-6">{{ session('success') }}</p>
                    <button @click="open = false"
                        class="w-full bg-blue-600 text-white px-6 py-2.5 rounded-xl font-medium hover:bg-blue-700 transition shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm flex items-start gap-3">
                <i class="fas fa-exclamation-circle text-base text-red-500 mt-0.5"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST" class="space-y-5">
            @csrf

            {{-- BARIS 1: NAMA + NIK --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                        placeholder="Contoh: Budi Santoso"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('nama') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                    <p class="text-gray-400 text-[11px] mt-1">Sesuai dengan KTP Anda.</p>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="16"
                        placeholder="Contoh: 327501xxxxxxxxxx"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('nik') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                    <p class="text-gray-400 text-[11px] mt-1">Wajib **16 digit angka** KTP/KK.</p>
                    @error('nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- BARIS 2: EMAIL + NO WHATSAPP --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="contoh: budi@gmail.com"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('email') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                    <p class="text-gray-400 text-[11px] mt-1">Untuk menerima pemberitahuan sistem.</p>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_wa" value="{{ old('no_wa') }}" required
                        placeholder="Contoh: 081234567890"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('no_wa') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                    <p class="text-gray-400 text-[11px] mt-1">Gunakan nomor aktif Anda (10-15 digit).</p>
                    @error('no_wa')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- BARIS 3: TANGGAL LAHIR + JENIS KELAMIN --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                        max="{{ date('Y-m-d') }}"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('tanggal_lahir') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                    <p class="text-gray-400 text-[11px] mt-1">Sesuaikan dengan data KTP Anda.</p>
                    @error('tanggal_lahir')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_kelamin" required
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800 bg-white cursor-pointer
                                  @error('jenis_kelamin') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                        <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <p class="text-gray-400 text-[11px] mt-1">Pilih salah satu opsi.</p>
                    @error('jenis_kelamin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- BARIS 4: ALAMAT LENGKAP --}}
            <div>
                <label class="font-semibold text-gray-700 text-sm">
                    Alamat Rumah Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea name="alamat" rows="3" required 
                    placeholder="Tulis alamat rumah lengkap (Nama jalan, RT/RW, Blok, No Rumah, Dusun/Desa)"
                    class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800 resize-none leading-relaxed
                          @error('alamat') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">{{ old('alamat') }}</textarea>
                <p class="text-gray-400 text-[11px] mt-1">Tulis domisili saat ini dengan jelas agar mudah diverifikasi.</p>
                @error('alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BARIS 5: PASSWORD + KONFIRMASI PASSWORD --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required placeholder="Buat password baru"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('password') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                    <p class="text-gray-400 text-[11px] mt-1">Gunakan **minimal 8 karakter** kombinasi.</p>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password Anda"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                    <p class="text-gray-400 text-[11px] mt-1">Ulangi password yang sama persis.</p>
                </div>
            </div>

            {{-- TOMBOL SUBMIT --}}
            <button type="submit"
                class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold mt-4 text-base
                       hover:bg-blue-700 active:scale-[0.99] transition shadow-md hover:shadow-lg hover:shadow-blue-600/20
                       flex items-center justify-center gap-2">
                <i class="fas fa-user-plus text-sm"></i>
                <span>Daftar Sekarang</span>
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6 text-sm">
            Sudah memiliki akun penduduk?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">
                Masuk di sini
            </a>
        </p>
    </div>
@endsection