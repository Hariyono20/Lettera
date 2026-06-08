<div class="w-full mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    
    {{-- Header Form Website Full Width --}}
    <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fa-solid fa-file-pen text-sm"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-gray-800">Edit Template Surat</h2>
                <p class="text-xs text-gray-400 font-normal mt-0.5">Sesuaikan informasi dasar, tata letak dokumen, dan parameter field formulir.</p>
            </div>
        </div>
        
        {{-- Action Buttons Header (Opsional untuk akses cepat di web lebar) --}}
        <div class="hidden sm:flex items-center gap-2">
            <button type="button" onclick="window.history.back()" class="px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-500 font-bold text-xs transition">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('form-edit-template').submit();" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
        </div>
    </div>

    <form id="form-edit-template" action="{{ route('admin.surat.update', $jenisSurat->id) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Grid Atas Dokumen (Nama Surat & Konten Bersandingan di Layar Lebar) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Kolom Kiri: Nama Surat --}}
            <div class="lg:col-span-1 space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Surat</label>
                <input type="text"
                       name="nama_surat"
                       value="{{ $jenisSurat->nama_surat }}"
                       class="w-full border border-gray-200 px-3 py-2.5 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-150"
                       placeholder="Contoh: Surat Keterangan Domisili"
                       required>
                <p class="text-[11px] text-gray-400 leading-normal">Nama ini akan muncul pada menu dropdown pilihan warga saat mengajukan permohonan surat.</p>
            </div>

            {{-- Kolom Kanan: Template Editor --}}
            <div class="lg:col-span-2 space-y-1.5">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Template Isi Surat</label>
                <textarea name="template_surat"
                          class="w-full border border-gray-200 p-3 rounded-lg text-sm text-gray-800 font-mono h-48 lg:h-56 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-150 leading-relaxed"
                          placeholder="Tuliskan format draft dokumen disini..."
                          required>{{ $jenisSurat->template_surat }}</textarea>
            </div>
        </div>

        <hr class="border-gray-100">

        {{-- FIELDS SECTION --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                <div>
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Parameter Form (Fields Dinamis)</h3>
                    <p class="text-[11px] text-gray-400 font-normal mt-0.5">Daftar variabel input yang wajib diisi warga untuk melengkapi template surat di atas.</p>
                </div>

                <button type="button"
                        onclick="addField()"
                        class="inline-flex items-center justify-center gap-1.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-bold shadow-xs transition duration-150 self-start sm:self-center">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    Tambah Field Baru
                </button>
            </div>

            {{-- Container Wrapper List Fields (Ukuran Website Lebar) --}}
            <div id="fields-wrapper" class="space-y-3">

                {{-- Judul Kolom (Hanya muncul di Desktop laptop agar seperti Tabel Rapi) --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-4 py-1 text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                    <div class="md:col-span-3">Nama Variabel (Name)</div>
                    <div class="md:col-span-4">Label Input Form</div>
                    <div class="md:col-span-2">Tipe Data</div>
                    <div class="md:col-span-2 text-center">Sifat</div>
                    <div class="md:col-span-1 text-right">Aksi</div>
                </div>

                @foreach ($jenisSurat->fields ?? [] as $index => $field)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-4 p-3 md:px-4 bg-white border border-gray-100 md:border-gray-200/60 rounded-xl md:rounded-lg items-center group hover:border-blue-200 hover:bg-blue-50/10 transition duration-150">
                        
                        {{-- Input Name --}}
                        <div class="md:col-span-3 space-y-1">
                            <label class="block md:hidden text-[10px] font-bold text-gray-400 uppercase">Variable Name</label>
                            <input type="text"
                                   name="fields[{{ $index }}][name]"
                                   value="{{ $field['name'] }}"
                                   placeholder="Contoh: nama_lengkap"
                                   class="w-full border border-gray-200 px-3 py-2 rounded-lg md:rounded text-xs font-mono focus:outline-none focus:border-blue-500 transition duration-150">
                        </div>

                        {{-- Input Label --}}
                        <div class="md:col-span-4 space-y-1">
                            <label class="block md:hidden text-[10px] font-bold text-gray-400 uppercase">Field Label</label>
                            <input type="text"
                                   name="fields[{{ $index }}][label]"
                                   value="{{ $field['label'] }}"
                                   placeholder="Contoh: Nama Lengkap"
                                   class="w-full border border-gray-200 px-3 py-2 rounded-lg md:rounded text-xs focus:outline-none focus:border-blue-500 transition duration-150">
                        </div>

                        {{-- Select Type --}}
                        <div class="md:col-span-2 space-y-1">
                            <label class="block md:hidden text-[10px] font-bold text-gray-400 uppercase">Data Type</label>
                            <select name="fields[{{ $index }}][type]"
                                    class="w-full border border-gray-200 px-2 py-2 rounded-lg md:rounded text-xs bg-white focus:outline-none focus:border-blue-500 transition duration-150">
                                @foreach(['text','textarea','date','number'] as $type)
                                    <option value="{{ $type }}" {{ $field['type'] == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Checkbox Required --}}
                        <div class="md:col-span-2 flex items-center md:justify-center pt-2 md:pt-0">
                            <label class="flex items-center gap-2 text-xs font-medium text-gray-600 cursor-pointer select-none">
                                <input type="checkbox"
                                       name="fields[{{ $index }}][required]"
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                       {{ !empty($field['required']) ? 'checked' : '' }}>
                                Required
                            </label>
                        </div>

                        {{-- Button Delete --}}
                        <div class="md:col-span-1 flex justify-end">
                            <button type="button"
                                    onclick="this.closest('.grid').remove()"
                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center text-xs transition duration-150"
                                    title="Hapus Field">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        {{-- Bottom Footer Action Buttons --}}
        <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-3">
            <button type="button"
                    onclick="window.history.back()"
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-gray-500 font-bold text-xs transition duration-150">
                Batal
            </button>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition duration-150">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan & Update Template
            </button>
        </div>

    </form>
</div>

{{-- SCRIPT TAMBAH FIELD --}}
<script>
let index = {{ count($jenisSurat->fields ?? []) }};

function addField() {
    const wrapper = document.getElementById('fields-wrapper');

    const html = `
    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-4 p-3 md:px-4 bg-white border border-gray-100 md:border-gray-200/60 rounded-xl md:rounded-lg items-center group hover:border-blue-200 hover:bg-blue-50/10 transition duration-150">
        
        <div class="md:col-span-3 space-y-1">
            <label class="block md:hidden text-[10px] font-bold text-gray-400 uppercase">Variable Name</label>
            <input type="text" name="fields[${index}][name]" placeholder="Contoh: nama_lengkap" class="w-full border border-gray-200 px-3 py-2 rounded-lg md:rounded text-xs font-mono focus:outline-none focus:border-blue-500 transition duration-150">
        </div>

        <div class="md:col-span-4 space-y-1">
            <label class="block md:hidden text-[10px] font-bold text-gray-400 uppercase">Field Label</label>
            <input type="text" name="fields[${index}][label]" placeholder="Contoh: Nama Lengkap" class="w-full border border-gray-200 px-3 py-2 rounded-lg md:rounded text-xs focus:outline-none focus:border-blue-500 transition duration-150">
        </div>

        <div class="md:col-span-2 space-y-1">
            <label class="block md:hidden text-[10px] font-bold text-gray-400 uppercase">Data Type</label>
            <select name="fields[${index}][type]" class="w-full border border-gray-200 px-2 py-2 rounded-lg md:rounded text-xs bg-white focus:outline-none focus:border-blue-500 transition duration-150">
                <option value="text">text</option>
                <option value="textarea">textarea</option>
                <option value="date">date</option>
                <option value="number">number</option>
            </select>
        </div>

        <div class="md:col-span-2 flex items-center md:justify-center pt-2 md:pt-0">
            <label class="flex items-center gap-2 text-xs font-medium text-gray-600 cursor-pointer select-none">
                <input type="checkbox" name="fields[${index}][required]" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                Required
            </label>
        </div>

        <div class="md:col-span-1 flex justify-end">
            <button type="button"
                    onclick="this.closest('.grid').remove()"
                    class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white flex items-center justify-center text-xs transition duration-150"
                    title="Hapus Field">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>

    </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);
    index++;
}
</script>