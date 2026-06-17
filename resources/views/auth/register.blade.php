@extends('layouts.auth')

{{-- Menyisipkan FontAwesome secara aman --}}
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
    <div class="py-2">
        <h2 class="text-3xl font-extrabold text-gray-800 text-center mb-1.5 tracking-tight">
            Daftar Akun Baru
        </h2>
        <p class="text-gray-500 text-center text-sm mb-8 leading-relaxed">
            Lengkapi data diri Anda dengan benar sesuai dengan dokumen resmi (KTP/KK)
        </p>

        {{-- ALERT SUCCESS (Global Pop-up via Alpine.js jika layout mendukung) --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm font-medium flex items-center gap-3">
                <i class="fas fa-check-circle text-base text-green-500 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ALERT ERROR DARI SYSTEM/SERVER --}}
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm font-medium flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-base text-red-500 flex-shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- KUMPULAN ERROR VALIDASI (Jika ada input yang salah) --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm text-sm flex gap-3">
                <i class="fas fa-exclamation-circle text-base text-red-500 mt-0.5 flex-shrink-0"></i>
                <div>
                    <p class="font-semibold mb-1">Pendaftaran gagal, mohon periksa kembali:</p>
                    <ul class="list-disc list-inside space-y-0.5 opacity-90 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST" class="space-y-4">
            @csrf

            {{-- BARIS 1: NAMA LENGKAP --}}
            <div>
                <label class="font-semibold text-gray-700 text-sm">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                    placeholder="Contoh: Budi Santoso"
                    class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                              @error('nama') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                <p class="text-gray-400 text-[11px] mt-1">Masukkan nama tanpa gelar sesuai KTP Anda.</p>
                @error('nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BARIS 2: EMAIL + NO WHATSAPP --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="budi@gmail.com"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('email') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_wa" value="{{ old('no_wa') }}" required
                        placeholder="Contoh: 08123456789"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('no_wa') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
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
                    @error('jenis_kelamin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- BARIS 4: ALAMAT LENGKAP RUMAH --}}
            <div>
                <label class="font-semibold text-gray-700 text-sm">
                    Alamat Rumah Lengkap <span class="text-red-500">*</span>
                </label>
                <textarea name="alamat" rows="3" required 
                    placeholder="Tulis alamat lengkap domisili saat ini (Nama jalan, No. Rumah, RT/RW, Dusun/Blok)"
                    class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800 resize-none leading-relaxed
                          @error('alamat') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- BARIS 5: PASSWORD + KONFIRMASI --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                        class="w-full border rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  @error('password') border-red-500 focus:ring-4 focus:ring-red-100 @else border-gray-300 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="font-semibold text-gray-700 text-sm">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                        class="w-full border border-gray-300 rounded-xl px-4 py-3 mt-1.5 transition outline-none text-sm text-gray-800
                                  focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500">
                </div>
            </div>

            {{-- TOMBOL SUBMIT REGISTER --}}
            <button type="submit"
                class="w-full bg-blue-600 text-white py-3.5 rounded-xl font-bold mt-4 text-base
                       hover:bg-blue-700 active:scale-[0.99] transition shadow-md hover:shadow-lg hover:shadow-blue-600/20
                       flex items-center justify-center gap-2">
                <i class="fas fa-user-plus text-xs"></i>
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