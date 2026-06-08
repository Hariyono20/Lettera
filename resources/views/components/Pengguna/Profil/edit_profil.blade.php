@extends('layouts.pengajuan_surat')

@section('content')
    <div class="w-full max-w-full mx-auto px-4 sm:px-6 py-2">

        {{-- Back Button --}}
        <a href="{{ route('profil.saya') }}"
            class="inline-flex items-center text-gray-500 hover:text-blue-700 mb-4 text-xs font-semibold transition">
            <span class="text-sm mr-1.5">←</span> Kembali ke Profil
        </a>

        <form class="bg-white shadow-sm rounded-2xl p-5 lg:p-7 max-w-5xl w-full mx-auto space-y-6 border border-gray-200"
            id="editProfileForm" action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Header Form --}}
            <div class="relative pb-3 border-b border-gray-100">
                <span class="absolute top-0 right-0 bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                    Formulir Modifikasi
                </span>
                <h2 class="text-base font-bold text-gray-900">Ubah Profil Anda</h2>
                <p class="text-xs text-gray-400 mt-0.5">Pastikan data kependudukan diisi dengan data yang valid</p>
            </div>

            {{-- SEKSI 1: DATA PRIBADI --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4">Data Pribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- NIK --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">NIK</label>
                        <input name="nik" type="text" value="{{ old('nik', auth()->user()->nik) }}"
                            class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        @error('nik') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- NAMA --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nama Lengkap</label>
                        <input name="nama" type="text" value="{{ old('nama', auth()->user()->nama) }}"
                            class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        @error('nama') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition cursor-pointer">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            {{-- Pengecekan disamakan dengan inisial database L atau P --}}
                            <option value="laki-laki" {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'L' || old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="perempuan" {{ old('jenis_kelamin', auth()->user()->jenis_kelamin) == 'P' || old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- TANGGAL LAHIR --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Tanggal Lahir</label>
                        <input name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', auth()->user()->tanggal_lahir) }}"
                            class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        @error('tanggal_lahir') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- USERNAME --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Username</label>
                        <input name="username" type="text" value="{{ old('username', auth()->user()->username) }}"
                            class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        @error('username') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- ALAMAT --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full border border-gray-300 rounded-xl p-3 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition resize-none leading-relaxed">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                        @error('alamat') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- SEKSI 2: INFORMASI KONTAK --}}
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4">Informasi Kontak</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- NO WA --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">No. WhatsApp</label>
                        <input name="no_wa" type="text" value="{{ old('no_wa', auth()->user()->no_wa) }}"
                            class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        @error('no_wa') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Email</label>
                        <input name="email" type="email" value="{{ old('email', auth()->user()->email) }}"
                            class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- BIO --}}
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Bio Deskripsi</label>
                        <textarea name="bio" rows="2" class="w-full border border-gray-300 rounded-xl p-3 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition resize-none leading-relaxed">{{ old('bio', auth()->user()->bio) }}</textarea>
                        @error('bio') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- SEKSI 3: PASSWORD --}}
            <div class="pt-4 border-t border-gray-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-1">Ubah Keamanan Akun</h3>
                <p class="text-[11px] text-gray-400 mb-4">Kosongkan kolom sandi jika Anda tidak berniat merubah password akun.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Password Baru</label>
                        <input name="password" type="password" placeholder="Minimal 6 karakter"
                            class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                        @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Konfirmasi Password</label>
                        <input name="password_confirmation" type="password" placeholder="Ulangi sandi baru"
                            class="w-full h-10 px-3 border border-gray-300 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    </div>
                </div>
            </div>

            {{-- SEKSI 4: FOTO PROFIL (ALPINE JS) --}}
            <div class="pt-4 border-t border-gray-100" x-data="{
                fileName: '',
                previewUrl: '{{ auth()->user()->foto ? asset('storage/foto/' . auth()->user()->foto) : '' }}',
                hasPreview: {{ auth()->user()->foto ? 'true' : 'false' }},
                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.fileName = file.name;
                        this.previewUrl = URL.createObjectURL(file);
                        this.hasPreview = true;
                    }
                },
                removeImage() {
                    this.fileName = '';
                    this.previewUrl = '';
                    this.hasPreview = false;
                    document.getElementById('file-upload').value = '';
                }
            }">
                <label class="text-xs font-semibold text-gray-600 mb-2 block">Unggah Foto Profil Baru</label>

                <div x-show="hasPreview" class="mb-3 relative inline-block">
                    <img :src="previewUrl" alt="Preview" class="w-24 h-24 object-cover rounded-full border-2 border-gray-200 shadow-sm">
                    <button type="button" @click="removeImage"
                        class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition shadow text-xs">
                        <i class="fa-solid fa-times text-[10px]"></i>
                    </button>
                </div>

                <div x-show="!hasPreview">
                    <input type="file" name="foto" id="file-upload" class="hidden" accept=".jpg,.jpeg,.png" @change="previewImage($event)">
                    <label for="file-upload"
                        class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-gray-50 transition cursor-pointer flex flex-col items-center justify-center">
                        <i class="fa-solid fa-upload text-xl text-gray-400 mb-1.5"></i>
                        <p class="text-gray-700 text-xs font-semibold mb-0.5">Pilih Berkas Gambar</p>
                        <p class="text-[10px] text-gray-400">JPG, PNG up to 2MB</p>
                    </label>
                </div>
                @error('foto') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="flex justify-end items-center pt-4 gap-3 border-t border-gray-100">
                <button type="reset"
                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-xl text-xs font-semibold transition">
                    <i class="fa-solid fa-rotate-left text-[11px]"></i>
                    <span>Reset</span>
                </button>

                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-5 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition shadow-sm">
                    <i class="fa-solid fa-save text-[11px]"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>
    </div>

    {{-- SCRIPTS --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const form = document.getElementById('editProfileForm');

        form.addEventListener('reset', () => {
            setTimeout(() => {
                const alpineData = document.querySelector('[x-data]').__x.$data;
                alpineData.fileName = '';
                alpineData.previewUrl = '{{ auth()->user()->foto ? asset("storage/foto/" . auth()->user()->foto) : "" }}';
                alpineData.hasPreview = {{ auth()->user()->foto ? 'true' : 'false' }};
            }, 10);
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'question',
                title: 'Simpan Data?',
                text: 'Pastikan data yang Anda masukkan sudah benar.',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'p-5 rounded-2xl text-xs',
                    confirmButton: 'bg-blue-600 text-white px-4 py-2 rounded-xl font-bold mr-2 text-xs',
                    cancelButton: 'bg-gray-100 text-gray-700 px-4 py-2 rounded-xl font-bold text-xs'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection