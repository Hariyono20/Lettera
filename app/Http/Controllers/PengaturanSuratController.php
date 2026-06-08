<?php

namespace App\Http\Controllers; // <--- Pastikan pakai (\) bukan (/)

use App\Models\PengaturanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanSuratController extends Controller
{
    /**
     * Tampilkan Form Pengaturan Kop & Footer Surat
     */
    public function index()
    {
        // Mengambil data baris pertama, jika belum ada otomatis buat data default
        $pengaturan = PengaturanSurat::firstOrCreate(
            ['id' => 1],
            [
                'instansi_1' => 'PEMERINTAH KOTA YOGYAKARTA',
                'instansi_2' => 'KECAMATAN UMBULHARJO',
                'instansi_3' => 'KELURAHAN ARGOMULYO',
                'alamat_instansi' => 'Jl. Argomulyo Raya No. 123 Yogyakarta 55167',
                'kontak_instansi' => 'Telp. (0274) 555xxx | Email: administrative@argomulyokel.go.id',
                'kode_pola_surat' => '470/{NUMBER}/Kel-Argo/' . date('Y'),
                'jumlah_tdd' => '1',
                'jabatan_pejabat' => 'LURAH ARGOMULYO',
                'nama_pejabat' => 'SUGIRAN, S.IP',
                'nip_pejabat' => '197405122002121003',
                'kalimat_penutup' => 'Demikian surat keterangan ini kami sampaikan dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.',
            ]
        );

        return view('layouts.admin.pengaturan', compact('pengaturan'));
    }

    /**
     * Simpan Perubahan Pengaturan Kop & Footer
     */
    public function update(Request $request)
    {
        $request->validate([
            'logo_daerah'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'instansi_1'      => 'required|string|max:255',
            'instansi_2'      => 'required|string|max:255',
            'instansi_3'      => 'required|string|max:255',
            'alamat_instansi' => 'required|string|max:255',
            'kontak_instansi' => 'required|string|max:255',
            'kode_pola_surat' => 'required|string|max:255',
            'jumlah_tdd'      => 'required|in:1,2',
            'jabatan_pejabat' => 'required|string|max:255',
            'nama_pejabat'    => 'required|string|max:255',
            'nip_pejabat'     => 'required|string|max:255',
            'kalimat_penutup' => 'nullable|string',
        ]);

        $pengaturan = PengaturanSurat::find(1);

        $data = $request->except('logo_daerah');

        // Logic Handle Upload Logo Instansi
        if ($request->hasFile('logo_daerah')) {
            // Hapus logo lama jika ada baru
            if ($pengaturan->logo_daerah && Storage::disk('public')->exists($pengaturan->logo_daerah)) {
                Storage::disk('public')->delete($pengaturan->logo_daerah);
            }
            
            $path = $request->file('logo_daerah')->store('logos', 'public');
            $data['logo_daerah'] = $path;
        }

        $pengaturan->update($data);

        return redirect()->back()->with('success', 'Konfigurasi Template Master Surat berhasil diperbarui!');
    }
}