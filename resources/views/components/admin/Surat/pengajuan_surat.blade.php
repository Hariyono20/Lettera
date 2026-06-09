<div class="h-full flex flex-col overflow-hidden max-w-[1600px] mx-auto p-4 gap-3.5" x-data="templateBuilder">

    {{-- HEADER BAR & NOTIFIKASI --}}
    <div
        class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200/60 pb-3 flex-shrink-0">
        <div>
            <h1 class="text-lg font-bold text-slate-800 tracking-tight flex items-center gap-2">
                <i class="fas fa-file-medical text-blue-600"></i> Rancangan Template Surat
            </h1>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Konfigurasi draf surat dinamis beserta field isian formulir kustom pemohon.
            </p>
        </div>

        {{-- Notifikasi Session & Validasi --}}
        <div class="flex flex-col gap-1.5 max-w-md w-full sm:w-auto">
            @if (session('error'))
                <div
                    class="bg-rose-50 border border-rose-200 text-rose-700 px-3 py-1.5 rounded-lg text-xs flex items-center gap-2 shadow-sm animate-fade-in">
                    <i class="fas fa-exclamation-circle text-rose-500 flex-shrink-0"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 px-3 py-1.5 rounded-lg shadow-sm text-xs">
                    <span class="font-bold flex items-center gap-1.5 text-rose-600 mb-0.5"><i class="fas fa-ban"></i>
                        Validasi Gagal:</span>
                    <ul class="list-disc pl-4 text-[11px] text-rose-600/90 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- SPLIT CONTAINER WORKSPACE --}}
    <form action="{{ route('admin.surat.store') }}" method="POST"
        class="flex-1 flex flex-col lg:flex-row gap-4 overflow-hidden min-h-0">
        @csrf

        {{-- KOLOM KIRI: METADATA & EDITOR TEXTAREA --}}
        <div
            class="w-full lg:w-[480px] flex flex-col gap-3 flex-shrink-0 overflow-y-auto lg:overflow-visible pr-0 lg:pr-1">

            <div class="bg-white shadow-sm rounded-xl border border-slate-200/80 p-4 space-y-3.5">

                {{-- Nama Surat --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Nama Surat / Dokumen
                    </label>
                    <input type="text" name="nama_surat" value="{{ old('nama_surat') }}"
                        placeholder="Contoh: Surat Keterangan Domisili"
                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition outline-none bg-slate-50/30 font-medium">
                </div>

                {{-- Kategori Surat Hybrid --}}
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            Kategori Surat
                        </label>
                        <button type="button"
                            @click="isManualCategory = !isManualCategory; if(!isManualCategory) manualCategory = ''"
                            class="text-[10px] text-blue-600 font-bold hover:text-blue-700 flex items-center gap-1 focus:outline-none transition">
                            <span x-text="isManualCategory ? '✕ Daftar Pilihan' : '+ Kategori Baru'"></span>
                        </button>
                    </div>

                    <div x-show="!isManualCategory" x-transition:enter="transition ease-out duration-150">
                        <select :name="!isManualCategory ? 'jenis' : ''"
                            class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition outline-none bg-slate-50/30 font-medium cursor-pointer">
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

                    <div x-show="isManualCategory" x-cloak x-transition:enter="transition ease-out duration-150">
                        <input type="text" :name="isManualCategory ? 'jenis' : ''" x-model="manualCategory"
                            placeholder="Masukkan nama kategori baru..."
                            class="w-full px-3 py-2 text-xs border border-blue-400 bg-blue-50/20 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition outline-none font-semibold text-blue-900">
                    </div>
                </div>

                {{-- Deskripsi Surat --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                        Deskripsi Singkat Layanan
                    </label>
                    <textarea name="deskripsi" rows="2" placeholder="Masukkan deskripsi singkat fungsi surat ini..."
                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition outline-none resize-none bg-slate-50/30 leading-normal">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Template Draft Surat --}}
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            Format Naskah / Draf Surat
                        </label>
                        <span
                            class="text-[9px] text-blue-600 bg-blue-50 border border-blue-100 px-1.5 py-0.5 rounded font-bold uppercase tracking-wide">
                            Raw Editor
                        </span>
                    </div>

                    <textarea name="template_surat" rows="7" placeholder="Tulis isi format isi atau draft surat di sini..."
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 font-mono text-xs tracking-normal transition outline-none bg-white text-slate-800 shadow-sm leading-relaxed placeholder:text-slate-400">{{ old('template_surat') }}</textarea>
                    {{-- Placeholder Token System --}}
                    <div class="mt-2 pt-2 border-t border-slate-100">
                        <span class="font-semibold text-slate-600 text-[10px] flex items-center gap-1 mb-1.5">
                            <i class="fas fa-tags text-blue-500"></i> Token Sinkronisasi Form (Klik untuk salin):
                        </span>
                        <div class="flex flex-wrap gap-1 font-mono text-[10px]">
                            <code @click="navigator.clipboard.writeText('@{{ nama }}')"
                                class="bg-slate-100 px-1.5 py-0.5 border border-slate-200 text-blue-600 rounded cursor-pointer hover:bg-blue-50 transition active:scale-95 select-none"
                                title="Klik untuk menyalin">@{{ nama }}</code>
                            <code @click="navigator.clipboard.writeText('@{{ nik }}')"
                                class="bg-slate-100 px-1.5 py-0.5 border border-slate-200 text-blue-600 rounded cursor-pointer hover:bg-blue-50 transition active:scale-95 select-none"
                                title="Klik untuk menyalin">@{{ nik }}</code>
                            <code @click="navigator.clipboard.writeText('@{{ alamat }}')"
                                class="bg-slate-100 px-1.5 py-0.5 border border-slate-200 text-blue-600 rounded cursor-pointer hover:bg-blue-50 transition active:scale-95 select-none"
                                title="Klik untuk menyalin">@{{ alamat }}</code>
                            <code @click="navigator.clipboard.writeText('@{{ keperluan }}')"
                                class="bg-slate-100 px-1.5 py-0.5 border border-slate-200 text-blue-600 rounded cursor-pointer hover:bg-blue-50 transition active:scale-95 select-none"
                                title="Klik untuk menyalin">@{{ keperluan }}</code>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- KOLOM KANAN: DYNAMIC FORM FIELD BUILDER (Independent Scroll) --}}
        <div class="flex-1 flex flex-col bg-white shadow-sm rounded-xl border border-slate-200 overflow-hidden min-w-0">

            {{-- Header Dynamic Component --}}
            <div
                class="flex justify-between items-center px-4 py-2.5 bg-slate-50 border-b border-slate-200 flex-shrink-0">
                <div>
                    <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Dynamic Form Field Builder
                    </h2>
                    <p class="text-slate-400 text-[10px] mt-0.5">Komponen inputan formulir yang akan diisi oleh warga
                        pemohon.</p>
                </div>

                <button type="button" @click="addField()"
                    class="px-2.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition flex items-center gap-1.5 shadow-sm shadow-blue-500/10 cursor-pointer">
                    <i class="fas fa-plus text-[9px]"></i> Tambah Input
                </button>
            </div>

            {{-- Area Scroll List Form Field --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/40">

                <template x-for="(field, index) in fields" :key="index">
                    <div
                        class="bg-white border border-slate-200 rounded-xl p-3.5 relative transition hover:border-slate-300 shadow-sm animate-fade-in">

                        {{-- Row Input Field Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">

                            {{-- Variable Token Name --}}
                            <div class="sm:col-span-3">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block">
                                    Token / Variabel <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" x-model="field.name" :name="'fields[' + index + '][name]'"
                                    placeholder="Contoh: nik"
                                    class="w-full px-2.5 py-1.5 border border-slate-300 rounded-md text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-mono">
                            </div>

                            {{-- Label Interface Warga --}}
                            <div class="sm:col-span-4">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block">
                                    Label Form (User Warga)
                                </label>
                                <input type="text" x-model="field.label" :name="'fields[' + index + '][label]'"
                                    placeholder="Contoh: Nomor Induk Kependudukan"
                                    class="w-full px-2.5 py-1.5 border border-slate-300 rounded-md text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                            </div>

                            {{-- HTML Type Selector --}}
                            <div class="sm:col-span-3">
                                <label
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 block">
                                    Tipe Inputan Data
                                </label>
                                <select x-model="field.type" :name="'fields[' + index + '][type]'"
                                    class="w-full px-2.5 py-1.5 border border-slate-300 rounded-md text-xs bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none cursor-pointer">
                                    <option value="text">Text (Pendek / Baris)</option>
                                    <option value="textarea">Textarea (Paragraf)</option>
                                    <option value="date">Tanggal (Kalender)</option>
                                    <option value="number">Angka (Numerik)</option>
                                </select>
                            </div>

                            {{-- Control Actions (Required Switch & Delete) --}}
                            <div
                                class="sm:col-span-2 flex items-center justify-between sm:justify-around pt-2 sm:pt-0 border-t border-slate-100 sm:border-t-0">

                                {{-- Checkbox Wajib Diisi --}}
                                <label class="flex items-center gap-2 sm:flex-col sm:gap-1 cursor-pointer group">
                                    <span
                                        class="text-[9px] text-slate-400 group-hover:text-slate-600 font-bold uppercase tracking-wider transition">Wajib</span>
                                    <input type="checkbox" x-model="field.required"
                                        :name="'fields[' + index + '][required]'"
                                        class="w-3.5 h-3.5 text-blue-600 rounded border-slate-300 focus:ring-0 cursor-pointer transition">
                                </label>

                                {{-- Delete Button --}}
                                <button type="button" @click="removeField(index)"
                                    class="text-rose-500 hover:text-rose-700 p-1.5 transition rounded-lg hover:bg-rose-50 flex items-center gap-1 sm:gap-0 cursor-pointer"
                                    title="Hapus field ini">
                                    <i class="fas fa-trash text-xs"></i>
                                    <span class="text-xs font-semibold sm:hidden">Hapus</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </template>

                {{-- Empty State Area --}}
                <div x-show="fields.length === 0"
                    class="text-center py-12 border border-dashed border-slate-200 rounded-xl bg-white flex flex-col items-center justify-center select-none">
                    <i class="fas fa-sliders-h text-2xl text-slate-300 mb-2 animate-pulse"></i>
                    <p class="text-xs font-bold text-slate-500">Belum Ada Kustomisasi Form Field</p>
                    <p class="text-[11px] text-slate-400 max-w-xs mt-1">Klik tombol "Tambah Input" di sudut kanan atas
                        untuk menyusun parameter data pemohon warga.</p>
                </div>

            </div>

            {{-- FOOTER GLOBAL ACTIONS BANNER (Fixed Bottom Right Column) --}}
            <div
                class="px-4 py-3 bg-slate-50 border-t border-slate-200 flex flex-col-reverse sm:flex-row items-center justify-end gap-2 flex-shrink-0">
                <a href="{{ route('admin.surat') }}"
                    class="w-full sm:w-auto text-center px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-600 rounded-lg text-xs font-bold transition">
                    Batal
                </a>
                <button type="submit"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition shadow-sm shadow-blue-500/10 flex items-center justify-center gap-1.5 cursor-pointer">
                    <i class="fas fa-check text-[10px]"></i> Simpan Konten Template
                </button>
            </div>

        </div>

    </form>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('templateBuilder', () => ({
            fields: [],
            isManualCategory: false,
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
