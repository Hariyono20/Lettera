@extends('layouts.pengajuan_surat')

@section('content')

    <div class="w-full max-w-full mx-auto px-4 sm:px-6 py-6 mt-2 space-y-5">

        {{-- BACK BUTTON --}}
        <div>
            <a href="{{ route('pengajuan.surat') }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-blue-600 transition">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>Kembali ke Daftar Surat</span>
            </a>
        </div>

        {{-- SUCCESS ALERT --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 flex items-start gap-3 text-xs">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-700 flex-shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h4 class="font-bold text-green-800">Berhasil</h4>
                    <p class="text-green-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- ERROR ALERT --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-3 text-xs">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-700 flex-shrink-0">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div>
                    <h4 class="font-bold text-red-800">Terdapat kesalahan pada form:</h4>
                    <ul class="list-disc list-inside text-red-700 mt-1 space-y-0.5 ml-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- MAIN FORM CONTAINER --}}
        <form
            action="{{ route('ajukan-surat.preview') }}"
            method="POST"
            id="pengajuanForm"
            class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            @csrf

            <input type="hidden" name="jenis_surat_id" value="{{ $jenisSurat->id }}">

            {{-- HEADER BANNER --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 p-5 sm:p-6 text-white">
                
                {{-- Dot Pattern Integration --}}
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg width="100%" height="100%">
                        <defs>
                            <pattern id="dotPattern" x="0" y="0" width="36" height="36" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="2" fill="white" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#dotPattern)" />
                    </svg>
                </div>

                <div class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-blue-100 text-xs mb-2">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>Form Pengajuan Surat</span>
                        </div>
                        <h1 class="text-2xl font-bold text-white">{{ $jenisSurat->nama_surat }}</h1>
                        <p class="text-blue-100 text-xs mt-1 leading-relaxed">{{ $jenisSurat->deskripsi }}</p>
                    </div>

                    {{-- Step Indicator --}}
                    <div class="bg-white/10 border border-white/20 backdrop-blur-sm rounded-xl px-4 py-3 min-w-[240px] xl:min-w-[260px]">
                        <p class="text-[10px] text-blue-100 uppercase tracking-wider font-semibold mb-1.5">Tahap Pengajuan</p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-white/10 border border-white/20 flex items-center justify-center text-xs font-bold text-white">
                                1
                            </div>
                            <div>
                                <h3 class="font-bold text-sm text-white leading-tight">Pengisian Data</h3>
                                <p class="text-[11px] text-blue-200">Langkah 1 dari 2</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENT SECTION --}}
            <div class="p-5 sm:p-6 space-y-6">
                
                {{-- Flow Information Notice --}}
                <div class="bg-blue-50/60 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 text-xs">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                    <p class="text-xs text-blue-800 leading-relaxed mt-0.5">
                        <strong>Informasi:</strong> Semua bidang data di bawah ini <strong>wajib diisi</strong>. Setelah menekan tombol preview, Anda akan diarahkan ke halaman draf untuk meninjau ketepatan data sebelum dikirimkan.
                    </p>
                </div>

                {{-- DYNAMIC FORM FIELDS --}}
                @if ($jenisSurat->fields && count($jenisSurat->fields) > 0)
                    <div class="space-y-4">
                        <div class="border-b border-gray-100 pb-2">
                            <h2 class="text-base font-bold text-gray-800">Data Pengajuan</h2>
                            <p class="text-gray-400 text-xs mt-0.5">Lengkapi seluruh data sesuai kebutuhan format surat.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($jenisSurat->fields as $field)
                                @php
                                    $fieldName = $field['name'];
                                    $fieldType = $field['type'];
                                    $fieldLabel = $field['label'];
                                    $user = auth()->user();
                                    
                                    $autoFillMap = [
                                        'nama' => $user->nama ?? '',
                                        'nik' => $user->nik ?? '',
                                        'email' => $user->email ?? '',
                                        'alamat' => $user->alamat ?? '',
                                        'no_hp' => $user->no_hp ?? '',
                                    ];

                                    $defaultValue = $autoFillMap[strtolower($fieldName)] ?? '';
                                    $value = old('data_surat.' . $fieldName, $defaultValue);
                                @endphp

                                <div class="{{ $fieldType === 'textarea' ? 'md:col-span-2' : '' }} space-y-1.5">
                                    <label class="block text-xs font-bold text-gray-700">
                                        {{ $fieldLabel }}
                                        <span class="text-red-500">*</span>
                                    </label>

                                    @if (in_array($fieldType, ['text', 'number', 'email']))
                                        <input type="{{ $fieldType }}" name="data_surat[{{ $fieldName }}]" value="{{ $value }}"
                                            placeholder="{{ $field['placeholder'] ?? 'Masukkan ' . strtolower($fieldLabel) }}"
                                            class="w-full h-10 px-3 border rounded-xl outline-none text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition {{ $errors->has('data_surat.' . $fieldName) ? 'border-red-400 ring-1 ring-red-100' : 'border-gray-200' }}"
                                            required>

                                    @elseif ($fieldType === 'date')
                                        <input type="date" name="data_surat[{{ $fieldName }}]" value="{{ $value }}"
                                            class="w-full h-10 px-3 border rounded-xl outline-none text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition {{ $errors->has('data_surat.' . $fieldName) ? 'border-red-400 ring-1 ring-red-100' : 'border-gray-200' }}"
                                            required>

                                    @elseif ($fieldType === 'textarea')
                                        <textarea name="data_surat[{{ $fieldName }}]" rows="{{ $field['rows'] ?? 3 }}"
                                            placeholder="{{ $field['placeholder'] ?? 'Masukkan penjelasan lengkap...' }}"
                                            class="w-full px-3 py-2 border rounded-xl outline-none text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition {{ $errors->has('data_surat.' . $fieldName) ? 'border-red-400 ring-1 ring-red-100' : 'border-gray-200' }}"
                                            required>{{ $value }}</textarea>

                                    @elseif ($fieldType === 'select')
                                        <select name="data_surat[{{ $fieldName }}]" 
                                            class="w-full h-10 px-3 border rounded-xl outline-none text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition {{ $errors->has('data_surat.' . $fieldName) ? 'border-red-400 ring-1 ring-red-100' : 'border-gray-200' }}"
                                            required>
                                            <option value="">-- Pilih {{ $fieldLabel }} --</option>
                                            @foreach ($field['options'] ?? [] as $option)
                                                <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>{{ $option }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    @error('data_surat.' . $fieldName)
                                        <p class="text-red-500 text-[11px] mt-1 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif


            {{-- FOOTER BUTTONS --}}
            <div class="bg-gray-50 border-t border-gray-200 px-5 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                    
                    <button type="reset" class="w-full sm:w-auto px-5 py-2 rounded-xl border border-gray-200 hover:bg-gray-100 text-gray-500 font-bold text-xs transition text-center">
                        Reset Form
                    </button>
                    
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-6 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs text-center transition shadow-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Preview Pengajuan
                    </button>

                </div>
            </div>
        </form>
    </div>

    {{-- INTERACTION & ACCESSIBILITY SCRIPT WITH NIK VALIDATION --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('pengajuanForm');
            
            // Ambil semua elemen input di dalam container dynamic fields saja
            const dynamicInputs = form.querySelectorAll('input[name^="data_surat"], textarea[name^="data_surat"], select[name^="data_surat"]');
            
            // Mencari element input khusus NIK berdasarkan format nama array-nya
            const nikInput = form.querySelector('input[name="data_surat[nik]"]');

            // Proteksi Real-time Input NIK
            if (nikInput) {
                // Dipaksa bertipe text agar batasan karakter 'maxlength' bekerja optimal di mobile browser
                nikInput.setAttribute('type', 'text');
                nikInput.setAttribute('maxlength', '16');
                
                nikInput.addEventListener('input', function() {
                    // Blokir total jika user mencoba memasukkan selain angka 0-9
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            form.addEventListener('submit', function(e) {
                let valid = true;
                let firstInvalidField = null;
                let errorMessage = 'Mohon lengkapi semua field data pengajuan surat yang wajib diisi.';
                
                dynamicInputs.forEach(field => {
                    const isNikField = field.getAttribute('name') === 'data_surat[nik]';
                    const valueTrimmed = field.value.trim();

                    // 1. Validasi Keadaan Kosong
                    if (!valueTrimmed) {
                        valid = false;
                        field.classList.add('border-red-400', 'ring-2', 'ring-red-100');
                        if (!firstInvalidField) firstInvalidField = field;
                    } 
                    // 2. Validasi Khusus Panjang Karakter NIK (Harus Tepat 16 Digit)
                    else if (isNikField && valueTrimmed.length !== 16) {
                        valid = false;
                        errorMessage = 'Format input salah. NIK wajib berjumlah tepat 16 digit angka!';
                        field.classList.add('border-red-400', 'ring-2', 'ring-red-100');
                        if (!firstInvalidField) firstInvalidField = field;
                    } 
                    // Jika data beres
                    else {
                        field.classList.remove('border-red-400', 'ring-2', 'ring-red-100');
                    }
                });

                if (!valid) {
                    e.preventDefault(); // Batalkan pengiriman form ke backend
                    alert(errorMessage);
                    
                    if (firstInvalidField) {
                        firstInvalidField.focus();
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });

            // Berikan feedback instan saat user mulai memperbaiki datanya
            dynamicInputs.forEach(field => {
                const isNikField = field.getAttribute('name') === 'data_surat[nik]';

                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        if (isNikField && this.value.trim().length !== 16) {
                            // Tetap biarkan border merah menyala jika ketikan NIK belum menyentuh angka 16
                            this.classList.add('border-red-400', 'ring-2', 'ring-red-100');
                        } else {
                            this.classList.remove('border-red-400', 'ring-2', 'ring-red-100');
                        }
                    }
                });

                field.addEventListener('change', function() {
                    if (this.value.trim()) {
                        this.classList.remove('border-red-400', 'ring-2', 'ring-red-100');
                    }
                });
            });
        });
    </script>

@endsection