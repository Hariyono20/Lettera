{{-- Card Filter (Lebar max-w-full, space vertikal proporsional) --}}
<div class="bg-white p-5 w-full max-w-full mx-auto rounded-xl shadow-md border border-gray-100 mb-5 mt-5 px-6">

    {{-- Header & Button --}}
    <div class="flex justify-between items-center mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Manajemen Surat</h2>

        <a href="{{ route('admin.surat.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-medium flex items-center gap-2 transition">
            <i class="fa fa-plus"></i>
            Buat Template Surat
        </a>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2.5 rounded-lg mb-4 text-xs">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter + Search --}}
    <div class="flex flex-wrap gap-4 items-start">

        {{-- Filter Status --}}
        <div>
            <label class="text-gray-700 text-xs font-semibold mb-1.5 block">
                Filter Status
            </label>

            <select id="filter-status"
                class="w-[200px] h-9 px-3 border border-gray-300 bg-white text-gray-600 text-xs rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                <option value="">Semua Status</option>
                <option value="aktif">Template Tersedia</option>
                <option value="tidak_aktif">Belum Ada Template</option>
            </select>
        </div>

        {{-- Filter Jenis --}}
        <div>
            <label class="text-gray-700 text-xs font-semibold mb-1.5 block">
                Filter Jenis Surat
            </label>

            <select id="filter-jenis"
                class="w-[200px] h-9 px-3 border border-gray-300 bg-white text-gray-600 text-xs rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                <option value="">Semua Jenis</option>
                <option value="sktm">SKTM</option>
                <option value="domisili">Domisili</option>
                <option value="usaha">Usaha</option>
                <option value="lainnya">Lainnya</option>
            </select>
        </div>

        {{-- Search --}}
        <div class="flex-1 min-w-[250px]">
            <label class="text-gray-700 text-xs font-semibold mb-1.5 block">
                Pencarian
            </label>

            <div class="relative h-9">
                <input type="text"
                    id="search"
                    placeholder="Cari Berdasarkan Nama Surat"
                    class="w-full h-full pl-9 pr-3 border border-gray-300 bg-white text-gray-600 text-xs rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">

                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa fa-search text-gray-400 text-xs"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Cards Grid (Dibatasi Maksimal Cuma 4 Card Sejajar ke Samping, Sisanya ke Bawah) --}}
<div id="cards-grid"
    class="w-full max-w-full mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">

    @forelse($jenisSuratList as $jenis)

        {{-- Card Item (Padding dinaikkan ke p-5 agar dimensi boks terasa lebih besar & pas) --}}
        <div class="bg-white p-5 border border-gray-200 rounded-xl shadow-sm flex flex-col justify-between h-full relative card-item transition-all hover:shadow-md hover:border-gray-300"
            data-status="{{ $jenis->template_surat ? 'aktif' : 'tidak_aktif' }}"
            data-jenis="{{ $jenis->jenis ?? 'lainnya' }}"
            data-title="{{ strtolower($jenis->nama_surat) }}">

            {{-- Action Button --}}
            <div class="absolute top-4 right-4 flex items-center gap-1">

                {{-- Edit --}}
                <a href="{{ route('admin.surat.edit', $jenis->id) }}"
                class="text-gray-400 hover:text-blue-500 p-1.5 transition"
                title="Edit Template">
                    <i class="fa fa-pen text-xs"></i>
                </a>

                {{-- Delete --}}
                <form action="{{ route('admin.surat.hapus', $jenis->id) }}"
                    method="POST"
                    class="inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                        onclick="return confirm('Yakin ingin menghapus template surat ini?')"
                        class="text-gray-400 hover:text-red-500 p-1.5 transition"
                        title="Hapus Template">
                        <i class="fa fa-trash text-xs"></i>
                    </button>
                </form>
            </div>

            {{-- Icon --}}
            <div class="flex justify-start mb-4">

                @php
                    $iconConfig = [
                        'sktm' => [
                            'icon' => 'fa-hand-holding-usd',
                            'bg' => 'bg-yellow-100',
                            'color' => 'text-yellow-600',
                        ],
                        'domisili' => [
                            'icon' => 'fa-home',
                            'bg' => 'bg-green-100',
                            'color' => 'text-green-600',
                        ],
                        'usaha' => [
                            'icon' => 'fa-briefcase',
                            'bg' => 'bg-purple-100',
                            'color' => 'text-purple-600',
                        ],
                        'lainnya' => [
                            'icon' => 'fa-file-alt',
                            'bg' => 'bg-blue-100',
                            'color' => 'text-blue-600',
                        ],
                    ];

                    $config = $iconConfig[$jenis->jenis ?? 'lainnya'] ?? $iconConfig['lainnya'];
                @endphp

                <div class="{{ $config['bg'] }} w-10 h-10 rounded-xl inline-flex items-center justify-center shadow-2xs">
                    <i class="fa {{ $config['icon'] }} {{ $config['color'] }} text-base"></i>
                </div>
            </div>

            {{-- Title --}}
            <div class="mb-4">
                <h3 class="text-sm font-bold text-gray-800 line-clamp-1" title="{{ $jenis->nama_surat }}">
                    {{ $jenis->nama_surat }}
                </h3>

                <p class="text-xs text-gray-400 font-normal mt-1 line-clamp-2 h-8 leading-relaxed">
                    {{ $jenis->deskripsi ?: 'Tidak ada deskripsi untuk template ini.' }}
                </p>
            </div>

            {{-- Fields --}}
            <div class="mb-5 bg-gray-50/50 border border-gray-100 rounded-lg p-2.5">
                <span class="font-bold text-gray-400 text-[10px] uppercase tracking-wider block mb-1">
                    Field yang digunakan:
                </span>

                <div class="mt-1 flex flex-wrap gap-1">
                    @if ($jenis->fields && count($jenis->fields) > 0)
                        @foreach (array_slice($jenis->fields, 0, 2) as $field)
                            <span class="inline-block bg-white border border-gray-200 text-gray-600 text-[10px] font-medium px-2 py-0.5 rounded-md max-w-[110px] truncate">
                                {{ $field['label'] }}
                            </span>
                        @endforeach

                        @if (count($jenis->fields) > 2)
                            <span class="inline-block bg-blue-50 border border-blue-100 text-blue-600 text-[10px] font-bold px-2 py-0.5 rounded-md">
                                +{{ count($jenis->fields) - 2 }}
                            </span>
                        @endif
                    @else
                        <span class="text-[11px] text-gray-400 italic">
                            Belum ada field
                        </span>
                    @endif
                </div>
            </div>

            {{-- Footer (Tinggi tombol pas) --}}
            <div class="mt-auto">
                @if ($jenis->template_surat)
                    <a href="{{ route('admin.surat.edit', $jenis->id) }}"
                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2.5 rounded-lg shadow-2xs transition text-center">
                        Edit Template
                    </a>
                @else
                    <a href="{{ route('admin.surat.create') }}"
                        class="block w-full bg-gray-400 hover:bg-gray-500 text-white text-xs font-bold py-2.5 rounded-lg shadow-2xs transition text-center">
                        Buat Template
                    </a>
                @endif
            </div>
        </div>

    @empty
        <div class="col-span-full text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
            <i class="fas fa-inbox text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-400 text-xs italic">Belum ada jenis surat</p>
        </div>
    @endforelse
</div>

<script>
    const filterStatus = document.getElementById('filter-status');
    const filterJenis = document.getElementById('filter-jenis');
    const searchInput = document.getElementById('search');
    const cards = document.querySelectorAll('.card-item');

    function filterCards() {
        const status = filterStatus.value;
        const jenis = filterJenis.value;
        const search = searchInput.value.toLowerCase();

        cards.forEach(card => {
            const cardStatus = card.dataset.status;
            const cardJenis = card.dataset.jenis;
            const cardTitle = card.dataset.title;

            const matchStatus = !status || cardStatus === status;
            const matchJenis = !jenis || cardJenis === jenis;
            const matchSearch = !search || cardTitle.includes(search);

            if (matchStatus && matchJenis && matchSearch) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    filterStatus.addEventListener('change', filterCards);
    filterJenis.addEventListener('change', filterCards);
    searchInput.addEventListener('input', filterCards);
</script>