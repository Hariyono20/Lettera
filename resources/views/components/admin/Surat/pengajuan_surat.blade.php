<div class="max-w-4xl mx-auto px-4 py-6 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800 tracking-tight sm:text-3xl">
            Buat Template Surat
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Buat template surat dinamis untuk digunakan pengguna saat pengajuan surat.
        </p>

    </div>

    {{-- Error Session --}}
    @if (session('error'))

        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2 shadow-sm">

            <i class="fas fa-exclamation-circle text-red-500 flex-shrink-0"></i>
            <span>{{ session('error') }}</span>

        </div>

    @endif

    {{-- Validation Error --}}
    @if ($errors->any())

        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm">

            <div class="font-medium text-sm mb-1.5 flex items-center gap-2">
                <i class="fas fa-ban text-red-500 flex-shrink-0"></i> Periksa kembali isian Anda:
            </div>

            <ul class="list-disc pl-5 text-xs space-y-0.5 text-red-600">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{-- MAIN CARD --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6"
        x-data="templateBuilder">

        <form action="{{ route('admin.surat.store') }}"
            method="POST">

            @csrf

            {{-- ========================================================= --}}
            {{-- INFORMASI SURAT --}}
            {{-- ========================================================= --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

                {{-- Nama Surat --}}
                <div>

                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                        Nama Surat
                    </label>

                    <input type="text"
                        name="nama_surat"
                        value="{{ old('nama_surat') }}"
                        placeholder="Contoh: Surat Keterangan Domisili"
                        class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition outline-none shadow-sm">

                </div>

                {{-- Jenis/Kategori Surat (Hybrid: Select + Manual Input) --}}
                <div>

                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kategori Surat
                        </label>
                        
                        <!-- Toggle Manual Input Button -->
                        <button type="button" 
                            @click="isManualCategory = !isManualCategory; if(!isManualCategory) manualCategory = ''"
                            class="text-[11px] text-blue-600 font-medium hover:underline flex items-center gap-1 focus:outline-none">
                            <span x-text="isManualCategory ? '✕ Pilih dari Daftar' : '+ Ketik Manual'"></span>
                        </button>
                    </div>

                    <!-- Mode 1: Dropdown Select -->
                    <div x-show="!isManualCategory" x-transition:enter="transition ease-out duration-200">
                        <select :name="!isManualCategory ? 'jenis' : ''"
                            class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition outline-none bg-white shadow-sm">

                            <option value="">-- Pilih Kategori --</option>
                            <option value="sktm">Surat Ket. Tidak Mampu (SKTM)</option>
                            <option value="domisili">Surat Ket. Domisili</option>
                            <option value="usaha">Surat Ket. Izin Usaha (SKU)</option>
                            <option value="kerja">Surat Ket. Kerja / Penghasilan</option>
                            <option value="kelahiran">Surat Ket. Kelahiran</option>
                            <option value="kematian">Surat Ket. Kematian</option>
                            <option value="pindah">Surat Ket. Pindah Datang</option>
                            <option value="pernyataan">Surat Pernyataan / Kuasa</option>
                            <option value="lainnya">Lainnya</option>

                        </select>
                    </div>

                    <!-- Mode 2: Teks Input Manual -->
                    <div x-show="isManualCategory" x-cloak x-transition:enter="transition ease-out duration-200">
                        <input type="text"
                            :name="isManualCategory ? 'jenis' : ''"
                            x-model="manualCategory"
                            placeholder="Masukkan nama kategori baru..."
                            class="w-full px-3.5 py-2.5 text-sm border border-blue-400 bg-blue-50/10 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition outline-none shadow-sm font-medium">
                    </div>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- DESKRIPSI --}}
            {{-- ========================================================= --}}

            <div class="mb-5">

                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">
                    Deskripsi Surat
                </label>

                <textarea name="deskripsi"
                    rows="2"
                    placeholder="Masukkan deskripsi singkat fungsi surat ini..."
                    class="w-full px-3.5 py-2.5 text-sm border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition outline-none resize-none shadow-sm">{{ old('deskripsi') }}</textarea>

            </div>

            {{-- ========================================================= --}}
            {{-- TEMPLATE SURAT --}}
            {{-- ========================================================= --}}

            <div class="mb-5">

                <div class="flex justify-between items-center mb-2">

                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Template Surat
                    </label>

                    <div class="text-[10px] text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md font-semibold">
                        Dinamis Editor
                    </div>

                </div>

                <textarea name="template_surat"
                    rows="8"
                    placeholder="Tulis isi format isi atau draft surat di sini..."
                    class="w-full px-3.5 py-3 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-mono text-sm tracking-normal transition outline-none shadow-sm">{{ old('template_surat') }}</textarea>

                {{-- Placeholder Clean Inline Style --}}
                <div class="mt-2.5 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-gray-500 border-t border-gray-100 pt-2.5">
                    
                    <span class="font-medium text-gray-600 flex items-center gap-1">
                        <i class="fas fa-info-circle text-blue-500"></i> Placeholder Tersedia:
                    </span>
                    
                    <div class="flex flex-wrap items-center gap-1.5 font-mono">
                        <code class="bg-gray-50 px-1.5 py-0.5 border border-gray-200 text-blue-600 rounded text-[11px] shadow-sm select-all">@{{nama}}</code>
                        <code class="bg-gray-50 px-1.5 py-0.5 border border-gray-200 text-blue-600 rounded text-[11px] shadow-sm select-all">@{{nik}}</code>
                        <code class="bg-gray-50 px-1.5 py-0.5 border border-gray-200 text-blue-600 rounded text-[11px] shadow-sm select-all">@{{alamat}}</code>
                        <code class="bg-gray-50 px-1.5 py-0.5 border border-gray-200 text-blue-600 rounded text-[11px] shadow-sm select-all">@{{keperluan}}</code>
                    </div>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- DYNAMIC FIELD --}}
            {{-- ========================================================= --}}

            <div class="mb-6">

                <div class="flex justify-between items-center mb-3">

                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Dynamic Form Field
                    </label>

                    {{-- Button --}}
                    <button type="button"
                        @click="addField()"
                        class="px-3 py-2 bg-blue-600 text-white rounded-xl text-xs font-medium hover:bg-blue-700 active:scale-[0.98] transition flex items-center gap-1.5 shadow-sm shadow-blue-500/10">

                        <i class="fas fa-plus text-[10px]"></i>
                        Tambah Input

                    </button>

                </div>

                {{-- Dynamic List --}}
                <div class="space-y-3">

                    <template x-for="(field, index) in fields"
                        :key="index">

                        <div class="bg-gray-50/60 border border-gray-200 rounded-xl p-4 relative transition hover:border-gray-300">

                            <!-- Grid Responsif: Stack di HP (1 kolom), Grid 12 Kolom di Desktop -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-3.5 items-end">

                                {{-- Name --}}
                                <div class="md:col-span-3">

                                    <label class="text-[11px] font-semibold text-gray-500 uppercase mb-1 block">
                                        Variable Name
                                    </label>

                                    <input type="text"
                                        x-model="field.name"
                                        :name="'fields[' + index + '][name]'"
                                        placeholder="contoh: nik"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:border-blue-500 outline-none shadow-sm">

                                </div>

                                {{-- Label --}}
                                <div class="md:col-span-4">

                                    <label class="text-[11px] font-semibold text-gray-500 uppercase mb-1 block">
                                        Label Form (User)
                                    </label>

                                    <input type="text"
                                        x-model="field.label"
                                        :name="'fields[' + index + '][label]'"
                                        placeholder="contoh: Nomor Induk Kependudukan"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:border-blue-500 outline-none shadow-sm">

                                </div>

                                {{-- Type --}}
                                <div class="md:col-span-3">

                                    <label class="text-[11px] font-semibold text-gray-500 uppercase mb-1 block">
                                        Tipe Inputan
                                    </label>

                                    <select x-model="field.type"
                                        :name="'fields[' + index + '][type]'"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs bg-white focus:border-blue-500 outline-none shadow-sm">

                                        <option value="text">Text (Pendek)</option>
                                        <option value="textarea">Textarea (Panjang)</option>
                                        <option value="date">Tanggal (Date)</option>
                                        <option value="number">Angka (Number)</option>

                                    </select>

                                </div>

                                {{-- Kontrol Aksi (Wajib & Hapus): Flexbox responsif --}}
                                <div class="md:col-span-2 flex items-center justify-between md:justify-around pt-2 md:pt-0 border-t border-gray-200/60 md:border-t-0">
                                    
                                    {{-- Required Checkbox --}}
                                    <label class="flex items-center gap-2 md:flex-col md:gap-1 cursor-pointer group">
                                        <span class="text-[10px] text-gray-400 group-hover:text-gray-600 font-semibold uppercase tracking-wider transition">Wajib</span>
                                        <input type="checkbox"
                                            x-model="field.required"
                                            :name="'fields[' + index + '][required]'"
                                            class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-0 focus:ring-offset-0 cursor-pointer shadow-sm">
                                    </label>

                                    {{-- Delete Button --}}
                                    <button type="button"
                                        @click="removeField(index)"
                                        class="text-red-500 hover:text-red-700 p-2 transition rounded-xl hover:bg-red-50 flex items-center gap-1 md:gap-0">
                                        <i class="fas fa-trash text-xs"></i>
                                        <span class="text-xs font-medium md:hidden">Hapus Field</span>
                                    </button>

                                </div>

                            </div>

                        </div>

                    </template>

                </div>

                {{-- Empty State --}}
                <div x-show="fields.length === 0"
                    class="text-center py-8 border border-dashed border-gray-200 rounded-xl mt-2 bg-gray-50/30">

                    <i class="fas fa-sliders-h text-xl text-gray-300 mb-1"></i>

                    <p class="text-xs text-gray-400">
                        Belum ada kustomisasi form field data pemohon.
                    </p>

                </div>

            </div>

            {{-- ========================================================= --}}
            {{-- ACTION BUTTONS --}}
            {{-- ========================================================= --}}

            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5 border-t border-gray-100 pt-4">

                {{-- Cancel --}}
                <a href="{{ route('admin.surat') }}"
                    class="w-full sm:w-auto text-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-semibold transition active:scale-[0.98]">
                    Batal
                </a>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition active:scale-[0.98] shadow-sm shadow-blue-500/10 flex items-center justify-center gap-1.5">
                    <i class="fas fa-check text-[10px]"></i>
                    Simpan Template
                </button>

            </div>

        </form>

    </div>

</div>

{{-- ========================================================= --}}
{{-- ALPINE JS --}}
{{-- ========================================================= --}}

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>

    document.addEventListener('alpine:init', () => {

        Alpine.data('templateBuilder', () => ({

            fields: [],
            isManualCategory: false, // State pendeteksi input manual/select
            manualCategory: '',

            addField() {

                this.fields.push({

                    name: '',
                    label: '',
                    type: 'text',
                    required: false

                });

            },

            removeField(index) {

                this.fields.splice(index, 1);

            }

        }));

    });

</script>