<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengaturanSurat;

class SuratController extends Controller
{
    /**
     * ============================================
     * ADMIN METHODS
     * ============================================
     */

    /**
     * List template surat
     */
    public function showAllSurat()
    {
        $jenisSuratList = JenisSurat::latest()->get();

        return view('layouts.admin.surat', [
            'jenisSuratList' => $jenisSuratList,
            'title' => 'Manajemen Template Surat'
        ]);
    }

    /**
     * Form tambah template
     */
    public function showCreateForm()
    {
        return view('layouts.admin.pengajuan_surat', [
            'title' => 'Tambah Template Surat'
        ]);
    }

    /**
     * Simpan template surat
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_surat' => 'required|string|max:255',
            'template_surat' => 'required|string',
            'fields' => 'required|array|min:1',

            'fields.*.name' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,textarea,date,number',
        ]);

        $fields = [];

        foreach ($request->fields as $field) {

            $fields[] = [
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                'required' => isset($field['required']),
            ];
        }

        JenisSurat::create([
            'nama_surat' => $request->nama_surat,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'template_surat' => $request->template_surat,
            'fields' => $fields,
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.surat')
            ->with('success', 'Template surat berhasil dibuat.');
    }

    /**
     * Form edit template
     */
    public function edit($id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);

        return view('layouts.admin.edit_surat', [
            'jenisSurat' => $jenisSurat,
            'title' => 'Edit Template Surat'
        ]);
    }

    /**
     * Update template surat
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_surat' => 'required|string|max:255',
            'template_surat' => 'required|string',
            'fields' => 'required|array|min:1',

            'fields.*.name' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.type' => 'required|in:text,textarea,date,number',
        ]);

        $jenisSurat = JenisSurat::findOrFail($id);

        $fields = [];

        foreach ($request->fields as $field) {

            $fields[] = [
                'name' => $field['name'],
                'label' => $field['label'],
                'type' => $field['type'],
                'required' => isset($field['required']),
            ];
        }

        $jenisSurat->update([
            'nama_surat' => $request->nama_surat,
            'jenis' => $request->jenis,
            'deskripsi' => $request->deskripsi,
            'template_surat' => $request->template_surat,
            'fields' => $fields,
        ]);

        return redirect()
            ->route('admin.surat')
            ->with('success', 'Template surat berhasil diperbarui.');
    }

    /**
     * Hapus template surat
     */
    public function hapus($id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);

        $jenisSurat->delete();

        return redirect()
            ->route('admin.surat')
            ->with('success', 'Template surat berhasil dihapus.');
    }


    /**
     * ============================================
     * ADMIN DASHBOARD UTAMA
     * ============================================
     */
    public function indexAdminDashboard()
    {
        $bulanIni = date('m');
        $tahunIni = date('Y');

        // 1. Statistik Card Ringkasan
        $dashboardStatistik = [
            'total_pengajuan' => Surat::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count(),
            'menunggu_verifikasi' => Surat::whereIn('status', ['pending', 'diajukan'])->count(),
            'sedang_diproses' => Surat::whereIn('status', ['proses', 'diproses', 'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'])->count(),
            'surat_selesai' => Surat::where('status', 'selesai')->whereMonth('tanggal_selesai', $bulanIni)->whereYear('tanggal_selesai', $tahunIni)->count(),
        ];

        // 2. DATA CHART 1: Pengajuan Per Bulan (6 Bulan Terakhir di Tahun Berjalan)
        $chartBulananData = [];
        $chartBulananLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun']; // Bisa disesuaikan dinamisasinya

        for ($m = 1; $m <= 6; $m++) {
            $count = Surat::whereMonth('created_at', $m)
                ->whereYear('created_at', $tahunIni)
                ->count();
            $chartBulananData[] = $count;
        }

        // 3. DATA CHART 2: 5 Jenis Surat Terbanyak (Menggunakan Eager Loading & Group By)
        $topJenisSurat = Surat::selectRaw('jenis_surat_id, count(*) as total')
            ->groupBy('jenis_surat_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->with('jenisSurat')
            ->get();

        $chartJenisLabels = [];
        $chartJenisData = [];

        foreach ($topJenisSurat as $item) {
            // Ambil nama surat, jika template dihapus pasang fallback nama alternatif
            $chartJenisLabels[] = $item->jenisSurat->nama_surat ?? 'Tidak Diketahui';
            $chartJenisData[] = $item->total;
        }

        // Jika data jenis surat kosong, berikan data kosong standar agar Chart.js tidak crash
        if (empty($chartJenisData)) {
            $chartJenisLabels = ['Belum Ada Data'];
            $chartJenisData = [0];
        }

        // 4. MENGAMBIL 5 PERMOHONAN TERBARU (Tambahan Baru)
        $permohonanTerbaru = Surat::with(['jenisSurat', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('components.admin.dashboard.dashboard', [
            'title' => 'Dashboard Admin',
            'statistik' => $dashboardStatistik,

            // Kirim data chart ke View
            'chartBulananLabels' => $chartBulananLabels,
            'chartBulananData'   => $chartBulananData,
            'chartJenisLabels'   => $chartJenisLabels,
            'chartJenisData'     => $chartJenisData,

            // Kirim data permohonan terbaru ke View
            'permohonanTerbaru'  => $permohonanTerbaru,
        ]);
    }
    /**
     * List semua permohonan
     */


    public function permohonanSuratPengguna()
    {
        // Mengambil data dengan Eager Loading dan membatasinya 7 data per halaman
        $rows = \App\Models\Surat::with(['jenisSurat', 'user'])
            ->latest()
            ->paginate(10); // <--- Diubah dari get() menjadi paginate(7)

        return view('layouts.admin.permohonan', [
            'title' => 'Data Permohonan Surat',
            'rows' => $rows
        ]);
    }
    /**
     * Detail pengajuan
     */
    public function show($suratId)
    {
        $surat = Surat::with([
            'user',
            'jenisSurat'
        ])->findOrFail($suratId);

        return view(
            'components.admin.surat.detail_pengajuan',
            [
                'surat' => $surat,
                'title' => 'Detail Pengajuan Surat'
            ]
        );
    }

    /**
     * ============================================
     * WORKFLOW ADMIN
     * ============================================
     */

    /**
     * Verifikasi oleh Admin
     */
    public function verifikasi(Request $request, $suratId)
{
    $surat = Surat::findOrFail($suratId);
    $action = $request->input('action');

    // JIKA ADMIN MEMILIH TOLAK (Hanya bisa dilakukan saat status awal masih pending/diajukan)
    if ($action === 'tolak') {
        if ($surat->status !== 'pending' && $surat->status !== 'diajukan') {
            return back()->with('error', 'Surat tidak dapat ditolak karena sudah berada dalam tahap pemrosesan.');
        }

        $request->validate([
            'catatan_admin' => 'required|string|min:5'
        ], [
            'catatan_admin.required' => 'Alasan penolakan wajib diisi jika pengajuan ditolak.'
        ]);

        $surat->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    // JIKA ADMIN MEMILIH ACC / VERIFIKASI (Meneruskan ke Pimpinan)
    if ($surat->status !== 'pending' && $surat->status !== 'diajukan') {
        return back()->with('error', 'Surat sudah diverifikasi sebelumnya atau sedang diproses.');
    }

    $surat->update([
        'status' => 'menunggu_persetujuan',
        'tanggal_verifikasi' => now(),
        'verified_by' => auth()->id(),
    ]);

    return back()->with('success', 'Surat berhasil diverifikasi oleh Admin dan diteruskan ke Pimpinan.');
}

    public function rekapSurat(Request $request)
    {
        $bulanFilter = $request->input('bulan');
        $searchFilter = $request->input('search');

        // 1. Ambil Pengaturan Pola Nomor Surat
        $masterSetting = PengaturanSurat::find(1) ?? new PengaturanSurat();
        $polaNomor = $masterSetting->kode_pola_surat ?? '000/{NUMBER}/ARG/' . date('Y');

        // 2. HITUNG STATISTIK RIIL (Mengikuti Filter Bulan atau Default Bulan Ini)
        $bulanStatistik = $bulanFilter ?? date('m');

        $statistik = [
            'total'    => Surat::whereMonth('created_at', $bulanStatistik)->count(),
            'selesai'  => Surat::where('status', 'selesai')->whereMonth('tanggal_selesai', $bulanStatistik)->count(),
            'proses'   => Surat::whereIn('status', ['diproses', 'pending', 'menunggu'])->whereMonth('created_at', $bulanStatistik)->count(),
            'ditolak'  => Surat::where('status', 'ditolak')->whereMonth('created_at', $bulanStatistik)->count(),
        ];

        // 3. AMBIL DATA UTAMA REKAP SURAT
        $rekapData = Surat::with(['user', 'jenisSurat'])
            ->where('status', 'selesai')
            ->when($bulanFilter, function ($query, $bulan) {
                return $query->whereMonth('tanggal_selesai', $bulan);
            })
            ->when($searchFilter, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', '%' . $search . '%');
                    })
                        ->orWhereHas('jenisSurat', function ($j) use ($search) {
                            $j->where('nama_surat', 'like', '%' . $search . '%');
                        })
                        ->orWhere('nomor_surat', 'like', '%' . $search . '%')
                        ->orWhere('data_surat', 'like', '%' . $search . '%');
                });
            })
            ->latest('tanggal_selesai')
            ->get()
            ->map(function ($surat) use ($polaNomor) {
                // Konversi JSON ke Array PHP
                $rawArray = is_string($surat->data_surat) ? json_decode($surat->data_surat, true) : $surat->data_surat;

                // Ubah semua KEY di dalam array menjadi HURUF KECIL (Mencegah case-sensitive)
                $dataArray = is_array($rawArray) ? array_change_key_case($rawArray, CASE_LOWER) : [];

                // Ambil data Nama dengan fallback berjenjang
                $surat->nama_pemohon = $dataArray['nama'] ??
                    ($dataArray['nama_lengkap'] ??
                        ($dataArray['nama_penduduk'] ??
                            ($surat->user->name ?? 'N/A')));

                // Ambil data Alamat dengan fallback berjenjang
                $surat->alamat_pemohon = $dataArray['alamat'] ??
                    ($dataArray['alamat_sekarang'] ??
                        ($dataArray['alamat_pindah'] ?? 'Kel. Argomulyo'));

                // Pola Penomoran Surat Otomatis jika kosong
                if (empty($surat->nomor_surat)) {
                    $angkaTigaDigit = str_pad($surat->id, 3, '0', STR_PAD_LEFT);
                    $surat->nomor_surat_fix = str_replace('{NUMBER}', $angkaTigaDigit, $polaNomor);
                } else {
                    $surat->nomor_surat_fix = $surat->nomor_surat;
                }

                return $surat;
            });

        // 4. Kirim data ke View
        $dataKirim = [
            'title'     => 'Rekap & Laporan Pelayanan',
            'rekapData' => $rekapData,
            'statistik' => $statistik
        ];

        if (auth()->user()->role === 'pimpinan') {
            return view('layouts.pimpinan.Rekap', $dataKirim);
        }

        return view('layouts.admin.rekap', $dataKirim);
    }
    /**
     * ============================================
     * WORKFLOW PIMPINAN
     * ============================================
     */

    /**
     * List surat masuk di dashboard pimpinan
     */
    public function permohonanSuratPimpinan()
    {
        $surats = Surat::with(['user', 'jenisSurat'])
            ->whereIn('status', ['menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'])
            ->latest()
            ->paginate(10); // <-- Diubah dari get() menjadi paginate(10)

        return view('layouts.pimpinan.permohonan', compact('surats'));
    }


    /**
     * Approve/Reject Pimpinan beserta Catatan Disposisi
     */
    public function approvePimpinan(Request $request, $suratId)
    {
        $surat = Surat::findOrFail($suratId);

        if ($surat->status !== 'menunggu_persetujuan' && $surat->status !== 'menunggu_persetujuan_pimpinan') {
            return back()->with('error', 'Surat tidak membutuhkan persetujuan pimpinan saat ini.');
        }

        // Jika Pimpinan memilih TOLAK
        if ($request->action === 'tolak') {
            $request->validate([
                'catatan_pimpinan' => 'required|string|min:5'
            ]);

            $surat->update([
                'status' => 'ditolak',
                'catatan_pimpinan' => $request->catatan_pimpinan
            ]);

            return redirect()->route('pimpinan.permohonan')->with('success', 'Surat berhasil ditolak oleh Pimpinan.');
        }

        // Jika Pimpinan memilih SETUJU / ACC
        $request->validate([
            'disposisi_pimpinan' => 'nullable|string'
        ]);

        // Pimpinan setuju, isi disposisi, status ganti jadi 'proses' agar balik ke Admin
        $surat->update([
            'status' => 'proses',
            'approved_by' => auth()->id(),
            'tanggal_disetujui' => now(),
            'disposisi_pimpinan' => $request->disposisi_pimpinan // Sekarang aman karena sudah ada di $fillable
        ]);

        return redirect()->route('pimpinan.permohonan')->with('success', 'Surat disetujui Pimpinan dan diteruskan kembali ke Admin.');
    }

    /**
     * Menampilkan log riwayat berkas yang sudah disetujui / ditolak oleh Pimpinan
     */
    public function riwayatSuratPimpinan()
    {
        // Mengambil data dengan batasan 10 data per halaman
        $surats = Surat::with(['user', 'jenisSurat'])
            ->whereIn('status', ['proses', 'diproses', 'selesai', 'ditolak'])
            ->latest()
            ->paginate(10); // <-- Diubah dari get() menjadi paginate(10)

        return view('layouts.pimpinan.riwayat', compact('surats'));
    }

    /**
     * Menampilkan detail permohonan spesifik untuk Pimpinan
     */
    public function detailPermohonanPimpinan($suratId)
    {
        $surat = Surat::with(['user', 'jenisSurat'])->findOrFail($suratId);

        if ($surat->status !== 'menunggu_persetujuan' && $surat->status !== 'menunggu_persetujuan_pimpinan') {
            return redirect()->route('pimpinan.permohonan')->with('error', 'Surat tidak dalam antrean persetujuan pimpinan.');
        }

        return view('layouts.pimpinan.detail', compact('surat'));
    }


    /**
     * ============================================
     * PROSES CETAK SURAT (ADMIN TAHAP AKHIR)
     * ============================================
     */
    public function downloadPdf($suratId)
    {
        // 1. Ambil data surat beserta relasi jenis surat
        $surat = Surat::with('jenisSurat')->findOrFail($suratId);

        // 2. Validasi status penolakan
        if ($surat->status === 'ditolak') {
            abort(403, 'AKSES DITOLAK: Surat telah ditolak, berkas PDF tidak dapat diunduh.');
        }

        // 3. Ambil master setting instansi kelurahan
        $pengaturan = PengaturanSurat::find(1) ?? new PengaturanSurat();
        $template = $surat->jenisSurat->template_surat ?? '';

        // 4. Pastikan data surat ter-decode dengan benar (Array data dinamis dari warga)
        $dataSuratArray = is_string($surat->data_surat) ? json_decode($surat->data_surat, true) : $surat->data_surat;

        // 5. Proses penggantian token dinamis (Case-Insensitive menggunakan str_ireplace)
        if (!empty($dataSuratArray) && is_array($dataSuratArray)) {
            foreach ($dataSuratArray as $key => $value) {
                $cleanedValue = $value ?: '-';

                // Ganti token standar, misal: {{nama_lengkap}}
                $template = str_ireplace('{{' . $key . '}}', $cleanedValue, $template);

                // Ganti token tanpa underscore jika ada, misal: {{nama lengkap}}
                $template = str_ireplace('{{' . str_replace('_', ' ', $key) . '}}', $cleanedValue, $template);

                // Antisipasi manual untuk variasi token keperluan/tujuan warga
                if (in_array(strtolower($key), ['keperluan', 'tujuan', 'maksud', 'alasan', 'tujuan_keperluan'])) {
                    $template = str_ireplace('{{keperluan}}', $cleanedValue, $template);
                    $template = str_ireplace('{{tujuan}}', $cleanedValue, $template);
                    $template = str_ireplace('{{maksud}}', $cleanedValue, $template);
                }
            }
        }

        // 6. Pola penomoran otomatis kedinasan
        $polaNomor = $pengaturan->kode_pola_surat ?? '000/{NUMBER}/ARG/' . date('Y');
        $angkaTigaDigit = str_pad($surat->id, 3, '0', STR_PAD_LEFT);
        $nomorSuratOtomatis = str_replace('{NUMBER}', $angkaTigaDigit, $polaNomor);

        // 7. Bersihkan token bawaan sistem global
        $template = str_ireplace('{{tanggal}}', \Carbon\Carbon::now()->translatedFormat('d F Y'), $template);
        $template = str_ireplace('{{nomor_surat}}', $nomorSuratOtomatis, $template);

        // 8. Compile view HTML ke DomPDF dengan nl2br agar enter baris terbaca
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.surat', [
            'template' => nl2br($template),
            'surat' => $surat,
            'pengaturan' => $pengaturan,
            'nomor_surat' => $nomorSuratOtomatis,
            'dataSuratArray' => $dataSuratArray
        ]);

        // 9. Set ukuran kertas standar surat resmi
        $pdf->setPaper('a4', 'portrait');

        // 10. Download file dengan nama yang bersih dari spasi ilegal
        $slugNamaSurat = str_replace(' ', '-', strtolower($surat->jenisSurat->nama_surat ?? 'surat'));
        return $pdf->download('surat-' . $slugNamaSurat . '-' . $surat->id . '.pdf');
    }
    public function selesaiSurat(Request $request, $suratId)
    {
        $surat = Surat::findOrFail($suratId);

        if ($surat->status !== 'proses' && $surat->status !== 'diproses') {
            return back()->with('error', 'Surat tidak dalam antrean untuk diselesaikan.');
        }

        // Validasi: File boleh kosong (jika warga ambil fisik), Pesan boleh kosong.
        $request->validate([
            'file_upload' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pemberitahuan' => 'nullable|string'
        ]);

        // KUNCI NOMOR SURAT RESMI: Mengunci nomor resmi berformat 3 digit (001) ke DB permanen
        $pengaturan = PengaturanSurat::find(1);
        $polaNomor = $pengaturan->kode_pola_surat ?? '000/{NUMBER}/ARG/' . date('Y');
        $angkaTigaDigit = str_pad($surat->id, 3, '0', STR_PAD_LEFT);
        $nomorSuratResmi = str_replace('{NUMBER}', $angkaTigaDigit, $polaNomor);

        $updateData = [
            'status' => 'selesai',
            'nomor_surat' => $nomorSuratResmi,
            'tanggal_selesai' => now(),
        ];

        // Opsi 1: Upload Scan Dokumen Bertanda Tangan Basah Lurah
        if ($request->hasFile('file_upload')) {
            $folderPath = 'surat_selesai';
            $file = $request->file('file_upload');
            $filename = 'surat-resmi-' . $surat->id . '-' . time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs($folderPath, $filename, 'public');
            $updateData['file_surat'] = $path;
        }

        // Opsi 2: Catatan pengambilan berkas fisik di Kantor
        if ($request->filled('pemberitahuan')) {
            $updateData['catatan_admin'] = $request->pemberitahuan;
        }

        $surat->update($updateData);

        return back()->with('success', 'Status surat berhasil diubah menjadi Selesai. Dokumen resmi telah diteruskan ke halaman warga.');
    }

    public function updateFileSurat(Request $request, $suratId)
    {
        $surat = Surat::findOrFail($suratId);

        // Validasi: pastikan surat memang sudah selesai dan memiliki berkas sebelumnya
        if ($surat->status !== 'selesai') {
            return back()->with('error', 'Gagal: File hanya dapat diubah pada surat yang telah berstatus Selesai.');
        }

        $request->validate([
            'file_upload' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file_upload')) {
            // 1. Hapus file lama dari storage jika ada
            if ($surat->file_surat && Storage::disk('public')->exists($surat->file_surat)) {
                Storage::disk('public')->delete($surat->file_surat);
            }

            // 2. Upload file baru
            $folderPath = 'surat_selesai';
            $file = $request->file('file_upload');
            $filename = 'surat-resmi-revisi-' . $surat->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folderPath, $filename, 'public');

            // 3. Update path di database
            $surat->update([
                'file_surat' => $path
            ]);

            return back()->with('success', 'Berhasil memperbarui file scan surat resmi.');
        }

        return back()->with('error', 'Gagal mengunggah file baru.');
    }


    /**
     * ============================================
     * USER / PENDUDUK
     * ============================================
     */
    public function indexPenggunaDashboard()
    {
        $userId = auth()->id();
        $bulanIni = date('m');
        $tahunIni = date('Y');

        // 1. Hitung Statistik Pengajuan Khusus User Ini
        $statistikUser = [
            'total_pengajuan' => Surat::where('user_id', $userId)
                ->whereMonth('created_at', $bulanIni)
                ->whereYear('created_at', $tahunIni)
                ->count(),

            'sedang_diproses' => Surat::where('user_id', $userId)
                ->whereIn('status', ['proses', 'diproses', 'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'])
                ->count(),

            'menunggu_verifikasi' => Surat::where('user_id', $userId)
                ->whereIn('status', ['pending', 'diajukan'])
                ->count(),

            'surat_selesai' => Surat::where('user_id', $userId)
                ->where('status', 'selesai')
                ->count(),
        ];

        // 2. Ambil 5 Pengajuan Terakhir untuk Ringkasan Tabel Riwayat
        $riwayatTerakhir = Surat::with(['jenisSurat'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('components.pengguna.dashboard.dashboard', [
            'title' => 'Dashboard Pengguna',
            'statistik' => $statistikUser,
            'riwayatTerakhir' => $riwayatTerakhir
        ]);
    }
    /**
     * Pilih jenis surat
     */
    public function pilihSurat()
    {
        $jenisSuratList = JenisSurat::whereNotNull('template_surat')
            ->whereNotNull('fields')
            ->where('is_active', true)
            ->orderBy('nama_surat', 'asc')
            ->get();

        return view(
            'components.pengguna.ajukansurat.ajukan-surat',
            [
                'jenisSuratList' => $jenisSuratList,
                'title' => 'Ajukan Surat'
            ]
        );
    }

    /**
     * Form pengajuan
     */
    public function formAjukan($id)
    {
        $jenisSurat = JenisSurat::findOrFail($id);

        if (
            !$jenisSurat->template_surat ||
            !$jenisSurat->fields
        ) {
            return redirect()
                ->route('pengajuan.surat')
                ->with(
                    'error',
                    'Template surat belum tersedia.'
                );
        }

        return view(
            'components.pengguna.ajukansurat.form_template',
            [
                'jenisSurat' => $jenisSurat,
                'title' => 'Ajukan ' . $jenisSurat->nama_surat
            ]
        );
    }


    /**
     * Preview pengajuan sebelum submit
     */
    public function previewAjukan(Request $request)
    {
        // 1. Safety Check
        if ($request->isMethod('get') || !$request->has('data_surat')) {
            return redirect()->route('ajukan-surat.form', $request->jenis_surat_id ?? 1);
        }

        $jenisSurat = JenisSurat::findOrFail($request->jenis_surat_id);

        /**
         * Validasi dinamis (Termasuk Proteksi Aturan NIK 16 Digit)
         */
        $rules = [
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'catatan' => 'nullable|string|max:500'
        ];

        foreach ($jenisSurat->fields as $field) {
            $fieldRules = [];

            if (!empty($field['required'])) {
                $fieldRules[] = 'required';
            }

            // Cek spesifik jika field name database bernama 'nik'
            if (strtolower($field['name']) === 'nik') {
                $fieldRules[] = 'numeric';
                $fieldRules[] = 'digits:16'; // Wajib angka berseri tepat 16 digit
            } else {
                switch ($field['type']) {
                    case 'email':
                        $fieldRules[] = 'email';
                        break;
                    case 'number':
                        $fieldRules[] = 'numeric';
                        break;
                    case 'date':
                        $fieldRules[] = 'date';
                        break;
                    default:
                        $fieldRules[] = 'string';
                        break;
                }
            }

            $rules["data_surat.{$field['name']}"] = implode('|', $fieldRules);
        }

        // Jalankan Validasi bawaan Laravel dengan Pesan Kustom Indonesia
        $request->validate($rules, [
            'data_surat.nik.digits'  => 'Format pengisian gagal, NIK Anda harus tepat berisikan 16 digit angka.',
            'data_surat.nik.numeric' => 'Kolom data NIK hanya boleh diisi oleh karakter angka.',
        ]);

        // 2. Ambil data input setelah lolos validasi aman
        $dataSurat = $request->data_surat ?? [];
        $previewTemplate = $jenisSurat->template_surat;

        // 3. Proses Replacement (Gunakan Regex agar lebih kuat)
        foreach ($dataSurat as $key => $value) {
            $pattern = '/\{\{\s*' . preg_quote($key, '/') . '\s*\}\}/';
            $replacement = !empty($value) ? $value : '-';
            $previewTemplate = preg_replace($pattern, $replacement, $previewTemplate);
        }

        // 4. Fallback: Hapus sisa variabel yang tidak terisi agar tidak muncul {{variabel}} di preview
        $previewTemplate = preg_replace('/\{\{\s*.*?\s*\}\}/', '-', $previewTemplate);

        return view('components.pengguna.ajukansurat.preview_pengajuan', [
            'jenisSurat' => $jenisSurat,
            'dataSurat' => $dataSurat,
            'catatan' => $request->catatan,
            'previewTemplate' => $previewTemplate,
            'title' => 'Preview Pengajuan Surat'
        ]);
    }

    /**
     * Submit final pengajuan ke database setelah deal di preview
     */
    public function submitAjukan(Request $request)
    {
        $jenisSurat = JenisSurat::findOrFail($request->jenis_surat_id);

        /**
         * Validasi dinamis (Wajib diulang demi keamanan lapis kedua database)
         */
        $rules = [
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'catatan' => 'nullable|string|max:500'
        ];

        foreach ($jenisSurat->fields as $field) {
            $fieldRules = [];

            if (!empty($field['required'])) {
                $fieldRules[] = 'required';
            }

            if (strtolower($field['name']) === 'nik') {
                $fieldRules[] = 'numeric';
                $fieldRules[] = 'digits:16';
            } else {
                switch ($field['type']) {
                    case 'email':
                        $fieldRules[] = 'email';
                        break;
                    case 'number':
                        $fieldRules[] = 'numeric';
                        break;
                    case 'date':
                        $fieldRules[] = 'date';
                        break;
                    default:
                        $fieldRules[] = 'string';
                        break;
                }
            }

            $rules["data_surat.{$field['name']}"] = implode('|', $fieldRules);
        }

        $request->validate($rules, [
            'data_surat.nik.digits'  => 'Format pengisian gagal, NIK Anda harus tepat berisikan 16 digit angka.',
            'data_surat.nik.numeric' => 'Kolom data NIK hanya boleh diisi oleh karakter angka.',
        ]);

        /**
         * Simpan pengajuan final aman
         */
        $surat = Surat::create([
            'user_id' => auth()->id(),
            'jenis_surat_id' => $request->jenis_surat_id,
            'data_surat' => $request->data_surat,
            'catatan' => $request->catatan,
            'status' => Surat::STATUS_PENDING,
        ]);

        return redirect()
            ->route('riwayat-pengajuan.detail', $surat->id)
            ->with('success', 'Pengajuan surat berhasil dikirim.');
    }
    /**
     * ============================================
     * DETAIL PENGAJUAN
     * ============================================
     */
    public function detailSurat($id)
    {
        /**
         * Query surat
         */
        $query = Surat::with([
            'jenisSurat',
            'user'
        ]);

        /**
         * ============================================
         * USER HANYA BISA LIHAT MILIKNYA
         * ============================================
         */
        if (
            auth()->user()->role === 'penduduk'
        ) {

            $query->where(
                'user_id',
                auth()->id()
            );
        }

        /**
         * Ambil surat
         */
        $surat = $query->findOrFail($id);

        /**
         * ============================================
         * GENERATE PREVIEW TEMPLATE
         * ============================================
         */
        $previewTemplate =
            $surat->jenisSurat->template_surat;

        /**
         * Replace variable template
         */
        foreach (
            $surat->data_surat as $key => $value
        ) {

            $pattern =
                '/\{\{\s*' .
                preg_quote($key, '/') .
                '\s*\}\}/';

            $previewTemplate = preg_replace(
                $pattern,
                $value ?? '-',
                $previewTemplate
            );
        }

        /**
         * Hapus sisa variable kosong
         */
        $previewTemplate = preg_replace(
            '/\{\{\s*.*?\s*\}\}/',
            '-',
            $previewTemplate
        );

        /**
         * ============================================
         * VIEW DETAIL
         * ============================================
         */
        return view(

            auth()->user()->role === 'penduduk'
                ? 'components.pengguna.riwayatpengajuan.detail_pengajuan'
                : 'components.admin.surat.detail_pengajuan',

            [
                'surat' => $surat,
                'previewTemplate' => $previewTemplate,
                'title' => 'Detail Pengajuan Surat'
            ]
        );
    }


    /**
     * ============================================
     * DOWNLOAD SURAT RESMI (UNTUK WARGA / USER)
     * ============================================
     */
    public function downloadSurat($id)
    {
        /**
         * Pastikan surat yang dicari adalah milik user yang sedang login
         */
        $surat = Surat::where('user_id', auth()->id())->findOrFail($id);

        /**
         * Validasi status harus sudah selesai
         */
        if ($surat->status !== 'selesai') {
            return back()->with('error', 'Surat Anda belum selesai diproses.');
        }

        /**
         * Jika admin tidak mengunggah file (hanya memberikan pemberitahuan fisik)
         */
        if (!$surat->file_surat) {
            return back()->with('error', 'Dokumen cetak fisik langsung. Silakan ikuti petunjuk pemberitahuan untuk mengambilnya di Kantor Kelurahan.');
        }

        /**
         * Path file yang mengarah ke storage/app/public/
         */
        $path = storage_path('app/public/' . $surat->file_surat);

        /**
         * Proteksi jika file tidak sengaja terhapus di server
         */
        if (!file_exists($path)) {
            return back()->with('error', 'Berkas dokumen gagal dimuat dari server. Silakan hubungi operator kelurahan.');
        }

        /**
         * Mengunduh dengan format nama file dokumen yang rapi
         */
        $namaDownload = 'Surat-Resmi-' . \Illuminate\Support\Str::slug($surat->jenisSurat->nama_surat ?? 'Dokumen') . '-' . $surat->id . '.' . pathinfo($path, PATHINFO_EXTENSION);

        return response()->download($path, $namaDownload);
    }
    /**
     * ============================================
     * RIWAYAT PENGAJUAN USER
     * ============================================
     */
    public function riwayatSurat(\Illuminate\Http\Request $request)
    {
        /**
         * Mulai Query untuk mengambil pengajuan milik user yang login
         */
        $query = Surat::with(['jenisSurat'])
            ->where('user_id', auth()->id());

        /**
         * 1. Filter Fitur Pencarian (Berdasarkan Kode Pengajuan)
         */
        if ($request->filled('search')) {
            $query->where('kode_pengajuan', 'like', '%' . $request->search . '%');
        }

        /**
         * 2. Filter Fitur Jenis Surat
         */
        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat_id', $request->jenis_surat);
        }

        /**
         * 3. Filter Fitur Status Surat (Menggunakan penyesuaian value database)
         */
        if ($request->filled('status')) {
            $status = $request->status;

            if ($status === 'diajukan') {
                $query->whereIn('status', ['pending', 'diajukan']);
            } elseif ($status === 'persetujuan') {
                $query->whereIn('status', ['menunggu_persetujuan', 'menunggu_persetujuan_pimpinan', 'persetujuan']);
            } elseif ($status === 'diproses') {
                $query->whereIn('status', ['proses', 'diproses']);
            } else {
                $query->where('status', $status);
            }
        }

        /**
         * Eksekusi data urut terbaru dengan pagination 
         */
        $suratList = $query->latest()->paginate(10)->withQueryString();

        /**
         * Ambil semua data Jenis Surat untuk mengisi dropdown "Semua Jenis" di view
         */
        $jenisSuratList = \App\Models\JenisSurat::all();

        /**
         * Tampilkan riwayat dengan membawa data tambahan
         */
        return view(
            'components.pengguna.riwayatpengajuan.riwayat',
            [
                'suratList' => $suratList,
                'jenisSuratList' => $jenisSuratList,
                'title' => 'Riwayat Pengajuan Surat'
            ]
        );
    }
}