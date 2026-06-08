@php
    // Gunakan $pengaturan dari controller, jika tidak ada ambil langsung dari DB
    $setting_sidebar = $pengaturan ?? \App\Models\PengaturanSurat::first();

    // Membersihkan nomor untuk link wa.me
    $no_wa_sidebar = isset($setting_sidebar->kontak_instansi) ? preg_replace('/[^0-9]/', '', $setting_sidebar->kontak_instansi) : '';
    
    // Jika string kontak mengandung banyak teks, kita bersihkan agar hanya mengambil nomor telp/wa saja
    // (Misal mendeteksi angka yang panjangnya antara 9-14 digit)
    if (preg_match('/[0-9]{9,14}/', $no_wa_sidebar, $matches)) {
        $no_wa_sidebar = $matches[0];
    }

    if (str_starts_with($no_wa_sidebar, '0')) {
        $no_wa_sidebar = '62' . substr($no_wa_sidebar, 1);
    }
@endphp

<div class="w-full lg:max-w-[360px] mt-6">
    <div class="rounded-2xl shadow-xl p-6 bg-gradient-to-br from-blue-600 to-blue-700 text-white font-inter min-h-[196px] flex flex-col justify-between border border-blue-500/20 group">
        
        <div>
            {{-- Judul & Ikon Headset --}}
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold tracking-tight">Butuh Bantuan?</h2>
                <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center backdrop-blur-sm">
                    <i class="fa-solid fa-headset text-white text-sm"></i>
                </div>
            </div>
            
            {{-- Deskripsi --}}
            <p class="text-[13px] text-blue-100 leading-relaxed mt-2.5 max-w-[260px]">
                Jika mengalami kendala teknis atau kebingungan mengenai pengajuan surat, hubungi staf pelayan kami.
            </p>
        </div>

        {{-- Tombol Hubungi Support --}}
        <a href="{{ $no_wa_sidebar ? 'https://wa.me/' . $no_wa_sidebar : '#' }}" target="_blank"
           class="mt-4 bg-white text-blue-600 font-bold text-[14px] py-3 px-4 rounded-xl text-center shadow-md hover:bg-blue-50 active:scale-[0.98] transition-all duration-250 flex items-center justify-center gap-2">
            <i class="fa-brands fa-whatsapp text-lg"></i>
            <span>Hubungi Support (WhatsApp)</span>
        </a>

    </div>
</div>