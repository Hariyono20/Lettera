@extends('layouts.pengajuan_surat')

@section('content')

    @php
        use Illuminate\Support\Str;

        $categories = $jenisSuratList->pluck('jenis')->filter()->unique();

        $iconConfig = [
            'sktm' => [
                'icon' => 'fa-hand-holding-dollar',
                'color' => 'bg-yellow-100 text-yellow-700',
            ],
            'domisili' => [
                'icon' => 'fa-house',
                'color' => 'bg-blue-100 text-blue-700',
            ],
            'usaha' => [
                'icon' => 'fa-briefcase',
                'color' => 'bg-green-100 text-green-700',
            ],
            'lainnya' => [
                'icon' => 'fa-file-lines',
                'color' => 'bg-purple-100 text-purple-700',
            ],
        ];
    @endphp

    <div class="w-full max-w-full mx-auto px-4 sm:px-6 py-6 mt-2 space-y-5">

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 flex items-start gap-3 text-xs">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-700 flex-shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h4 class="font-bold text-green-800">Berhasil</h4>
                    <p class="text-green-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-3 text-xs">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-700 flex-shrink-0">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div>
                    <h4 class="font-bold text-red-800">Gagal</h4>
                    <p class="text-red-700 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

    

        {{-- Search & Filter Controls --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row gap-3 sm:items-center">

            {{-- Search Input --}}
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" id="searchInput" placeholder="Cari jenis surat..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-xs transition">
            </div>

            {{-- Filter Dropdown --}}
            <select id="filterSelect"
                class="px-4 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white transition min-w-[180px]">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $kategori)
                    <option value="{{ strtolower($kategori) }}">
                        {{ ucfirst($kategori) }}
                    </option>
                @endforeach
            </select>

        </div>

        {{-- Cards Grid Container --}}
<div id="cardsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

    @forelse($jenisSuratList as $jenis)
        @php
            $jenisKey = strtolower($jenis->jenis ?? 'lainnya');
            $config = $iconConfig[$jenisKey] ?? $iconConfig['lainnya'];
        @endphp

        <div class="card group bg-white border border-gray-200 rounded-3xl p-6 hover:shadow-xl hover:shadow-blue-50/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between"
            data-title="{{ strtolower($jenis->nama_surat) }}"
            data-category="{{ strtolower($jenis->jenis ?? 'lainnya') }}">

            <div class="flex flex-col h-full">
                {{-- Header: Icon & Category --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $config['color'] }} shadow-sm">
                        <i class="fa-solid {{ $config['icon'] }} text-lg"></i>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[11px] font-semibold tracking-wide uppercase">
                        {{ ucfirst($jenis->jenis ?? 'Lainnya') }}
                    </span>
                </div>

                {{-- Body: Title & Desc --}}
                <div class="flex-grow">
                    <h3 class="text-[17px] font-bold text-gray-900 mb-2 leading-tight group-hover:text-blue-600 transition-colors">
                        {{ $jenis->nama_surat }}
                    </h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">
                        {{ Str::limit($jenis->deskripsi, 80) }}
                    </p>
                </div>

                {{-- Footer: Fields & Button --}}
                <div class="mt-auto border-t border-gray-100 pt-5">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-bold text-gray-400 uppercase">Syarat</span>
                        <span class="text-[11px] bg-blue-50 text-blue-600 font-bold px-2 py-0.5 rounded-md">
                            {{ count($jenis->fields ?? []) }} Item
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-1.5 mb-5">
                        @if ($jenis->fields && count($jenis->fields) > 0)
                            @foreach (array_slice($jenis->fields, 0, 2) as $field)
                                <span class="px-2.5 py-1 rounded-lg bg-gray-50 text-gray-600 text-[11px] font-medium border border-gray-100">
                                    {{ Str::limit($field['label'], 12) }}
                                </span>
                            @endforeach
                            @if (count($jenis->fields) > 2)
                                <span class="px-2.5 py-1 rounded-lg bg-gray-50 text-gray-500 text-[11px] font-medium">
                                    +{{ count($jenis->fields) - 2 }}
                                </span>
                            @endif
                        @else
                            <span class="text-[11px] text-gray-400 italic">Tanpa syarat khusus</span>
                        @endif
                    </div>

                    <a href="{{ route('ajukan-surat.form', $jenis->id) }}"
                        class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-gray-900 hover:bg-blue-600 text-white font-semibold text-sm transition duration-300">
                        <span>Ajukan</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </div>

    @empty
        {{-- Empty State --}}
        <div class="col-span-full py-12 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-300">
            <div class="w-16 h-16 mx-auto rounded-full bg-white flex items-center justify-center text-gray-400 text-2xl mb-4 border shadow-sm">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Belum Ada Layanan</h3>
            <p class="text-sm text-gray-500 mt-1">Layanan surat saat ini belum tersedia.</p>
        </div>
    @endforelse
</div>

        {{-- Information Box --}}
        <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-5 shadow-sm">
            <div class="flex items-start gap-3.5">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 text-sm">
                        <i class="fa-solid fa-circle-info"></i>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-blue-900 mb-2.5">
                        Informasi Penting
                    </h4>

                    <ul class="space-y-2 text-xs text-blue-800">
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-blue-600 mt-0.5 text-[10px]"></i>
                            <span>Pastikan semua data yang diisi sudah benar dan sesuai dengan dokumen asli.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-blue-600 mt-0.5 text-[10px]"></i>
                            <span>Surat akan diproses admin maksimal 3x24 jam kerja.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-blue-600 mt-0.5 text-[10px]"></i>
                            <span>Anda akan mendapatkan notifikasi setelah surat disetujui atau ditolak.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-blue-600 mt-0.5 text-[10px]"></i>
                            <span>Jika pengajuan ditolak, Anda dapat mengajukan ulang dengan perbaikan data.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    {{-- Search & Filter Script --}}
    <script>
        const searchInput = document.getElementById('searchInput');
        const filterSelect = document.getElementById('filterSelect');
        const cards = document.querySelectorAll('#cardsContainer .card');

        function filterCards() {
            const searchTerm = searchInput.value.toLowerCase();
            const filterCategory = filterSelect.value.toLowerCase();

            cards.forEach(card => {
                const title = card.getAttribute('data-title').toLowerCase();
                const category = card.getAttribute('data-category').toLowerCase();

                const matchesSearch = title.includes(searchTerm);
                const matchesFilter = !filterCategory || category === filterCategory;

                card.style.display = matchesSearch && matchesFilter ? 'flex' : 'none';
            });
        }

        searchInput.addEventListener('input', filterCards);
        filterSelect.addEventListener('change', filterCards);
    </script>

@endsection