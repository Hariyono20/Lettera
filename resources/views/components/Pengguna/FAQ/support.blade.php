@php
    // Gunakan $pengaturan dari controller, jika tidak ada (di hal FAQ) ambil langsung dari DB
    $setting = $pengaturan ?? \App\Models\PengaturanSurat::first();

    // 1. Ekstraksi Nomor Telepon/WhatsApp saja (mengambil angka di dalam string)
    $raw_kontak = $setting->kontak_instansi ?? '';
    $no_wa = '';
    
    // Mencari deretan angka yang kemungkinan besar adalah nomor telepon (9-14 digit)
    if (preg_match('/[0-9]{9,14}/', str_replace([' ', '(', ')', '-'], '', $raw_kontak), $matches)) {
        $no_wa = $matches[0];
    }
    
    // Format agar link wa.me valid menggunakan kode negara 62
    if (str_starts_with($no_wa, '0')) {
        $no_wa = '62' . substr($no_wa, 1);
    }

    // Teks tampilan khusus untuk deskripsi WhatsApp (Biar bersih tanpa tulisan "Email: ...")
    $clean_phone_view = '';
    if (preg_match('/(?:Telp\.|WA|Telp)?[\s?:\.]?([0-9\-\(\)\s]{9,18})/i', $raw_kontak, $phone_matches)) {
        $clean_phone_view = trim($phone_matches[0]);
    }

    // 2. Ekstraksi Email saja secara akurat
    $email_instansi = null;
    if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $raw_kontak, $email_matches)) {
        $email_instansi = $email_matches[0];
    }

    // 3. Generate Link Google Maps berdasarkan Alamat Instansi di DB
    $alamat_instansi = $setting->alamat_instansi ?? '';
    $google_maps_link = '#';
    if (!empty($alamat_instansi)) {
        // Melakukan urlencode agar teks alamat aman digunakan sebagai query string URL Google Maps
        $google_maps_link = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($alamat_instansi);
    }

    $supports = [
        [
            'title'=>'Chat WhatsApp',
            'desc'=> !empty($clean_phone_view) ? $clean_phone_view : 'Respon cepat melalui WhatsApp',
            'color'=>'green',
            'icon'=>'fas fa-comment',
            'btn'=>'Hubungi Admin',
            'link'=> $no_wa ? 'https://wa.me/' . $no_wa : '#'
        ],
        [
            'title'=>'Email Pengaduan',
            'desc'=> $email_instansi ?? 'Kirim email untuk bantuan detail',
            'color'=>'blue',
            'icon'=>'fas fa-envelope',
            'btn'=>'Kirim Email',
            'link'=> $email_instansi ? 'mailto:' . $email_instansi : '#'
        ],
        [
            'title'=>'Kantor Desa',
            'desc'=> !empty($alamat_instansi) ? $alamat_instansi : 'Senin–Jumat: 08:00–16:00',
            'color'=>'gray',
            'icon'=>'fas fa-home',
            'btn'=>'Lihat Lokasi',
            'link'=> $google_maps_link
        ],
    ];
@endphp

<div class="max-w-6xl mx-auto text-center mb-16">

    {{-- Card Besar --}}
    <div class="bg-white shadow-xl rounded-2xl p-10 border border-gray-200">
           {{-- Judul --}}
           <h2 class="text-2xl font-bold text-gray-800 mb-3">Butuh Bantuan Tambahan?</h2>
           <p class="text-gray-600 mb-10">Tim support kami siap membantu Anda 24/7</p>

        {{-- 3 Card Kecil --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-3">

            @foreach($supports as $s)
            <div class="p-5 rounded-xl border border-gray-200 bg-gray-50 shadow-sm 
                        hover:shadow-lg transition-all duration-300 flex flex-col items-center">

                {{-- Icon lingkaran --}}
                <div class="w-14 h-14 flex items-center justify-center rounded-full 
                    bg-{{ $s['color'] }}-100 text-{{ $s['color'] }}-600 text-2xl mb-3
                    transition-all duration-200 hover:bg-{{ $s['color'] }}-600 hover:text-white">
                    <i class="{{ $s['icon'] }}"></i>
                </div>

                {{-- Judul --}}
                <h3 class="font-semibold text-gray-800 mb-1 text-sm">
                    {{ $s['title'] }}
                </h3>

                {{-- Deskripsi --}}
                <p class="text-xs text-gray-600 mb-4 leading-relaxed text-center">
                    {{ $s['desc'] }}
                </p>

                {{-- Button --}}
                <a href="{{ $s['link'] }}" target="{{ $s['link'] !== '#' ? '_blank' : '_self' }}"
                    class="w-full py-2 rounded-full font-medium text-white text-sm transition text-center
                    @if($s['color'] === 'gray')
                        bg-[#6B7280] hover:bg-[#4B5563]
                    @else
                        bg-{{ $s['color'] }}-600 hover:bg-{{ $s['color'] }}-700
                    @endif
                ">
                    {{ $s['btn'] }}
                </a>

            </div>
            @endforeach

        </div>
    </div>
</div>