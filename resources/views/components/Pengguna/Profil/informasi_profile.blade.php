<div class="w-full lg:w-2/3 xl:w-3/4 bg-white border border-gray-200 shadow-sm rounded-2xl p-5 lg:p-6">

    <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-5">
        <div>
            <h2 class="text-base font-bold text-gray-900">Informasi Profil</h2>
            <p class="text-xs text-gray-400 mt-0.5">Detail data kependudukan yang terdaftar di sistem</p>
        </div>

        <a href="{{ route('profil.edit') }}"
            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition shadow-sm">
            <i class="fa-solid fa-pen text-[10px]"></i>
            Edit Profil
        </a>
    </div>

    {{-- Data Pribadi --}}
    <div class="mb-6">
        <h3 class="flex items-center text-xs font-bold uppercase tracking-wider text-blue-600 mb-3.5">
            <i class="fa-solid fa-id-card mr-2"></i>
            Data Pribadi
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">NIK</label>
                {{-- NIK Tersembunyi (Hanya 3 Angka Terakhir) pada Input --}}
                <input disabled 
                    value="@if(!empty($user->nik)){{ str_repeat('*', strlen($user->nik) - 3) . substr($user->nik, -3) }}@else-@endif"
                    class="block w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-gray-50 text-gray-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Lengkap</label>
                <input disabled value="{{ $user->nama }}"
                    class="block w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-gray-50 text-gray-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Jenis Kelamin</label>
                <input disabled value="{{ $user->jenis_kelamin ? ($user->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan') : 'Belum Diisi' }}"
                    class="block w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-gray-50 text-gray-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal Lahir</label>
                <input disabled value="{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') : '-' }}"
                    class="block w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-gray-50 text-gray-600">
            </div>
        </div>
    </div>

    {{-- Informasi Kontak --}}
    <div class="pt-4 border-t border-gray-100">
        <h3 class="flex items-center text-xs font-bold uppercase tracking-wider text-blue-600 mb-3.5">
            <i class="fa-solid fa-address-book mr-2"></i>
            Informasi Kontak & Deskripsi
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Nomor WhatsApp</label>
                <input disabled value="{{ $user->no_wa ?? '-' }}"
                    class="block w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-gray-50 text-gray-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Email</label>
                <input disabled value="{{ $user->email }}"
                    class="block w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-gray-50 text-gray-600">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Alamat Rumah</label>
                <textarea disabled rows="2" class="block w-full border border-gray-200 rounded-xl p-3 text-xs bg-gray-50 text-gray-600 resize-none leading-relaxed">{{ $user->alamat ?? '-' }}</textarea>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Bio Deskripsi</label>
                <textarea disabled rows="2" class="block w-full border border-gray-200 rounded-xl p-3 text-xs bg-gray-50 text-gray-600 resize-none leading-relaxed">{{ $user->bio ?? '-' }}</textarea>
            </div>
        </div>
    </div>

</div>