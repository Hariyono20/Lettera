<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * ============================================
     * 👤 DASHBOARD PENDUDUK / USER
     * ============================================
     */
    public function penduduk()
    {
        $userId = auth()->id();
        $bulanIni = date('m');
        $tahunIni = date('Y');

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

        $riwayatTerakhir = Surat::with(['jenisSurat'])
                                ->where('user_id', $userId)
                                ->latest()
                                ->take(5)
                                ->get();

        return view('layouts.app', [
            'title' => 'Dashboard Pengguna',
            'statistik' => $statistikUser,
            'riwayatTerakhir' => $riwayatTerakhir
        ]);
    }

    /**
     * ============================================
     * 🛠 DASHBOARD ADMIN / PEGAWAI
     * ============================================
     */
    public function admin()
    {
        return view('components.admin.dashboard');
    }

    /**
     * ============================================
     * 👑 DASHBOARD PIMPINAN
     * ============================================
     */
    public function pimpinan()
    {
        $bulanIni = date('m');
        $tahunIni = date('Y');

        // 📊 Hitung statistik untuk menyuplai komponen admin yang di-include
        $dashboardStatistik = [
            'total_pengajuan' => Surat::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count(),
            'menunggu_verifikasi' => Surat::whereIn('status', ['pending', 'diajukan'])->count(),
            'sedang_diproses' => Surat::whereIn('status', ['proses', 'diproses', 'menunggu_persetujuan', 'menunggu_persetujuan_pimpinan'])->count(),
            'surat_selesai' => Surat::where('status', 'selesai')->whereMonth('tanggal_selesai', $bulanIni)->whereYear('tanggal_selesai', $tahunIni)->count(),
        ];

        // -------------------------------------------------------------
        // CHART 1: Grafik Bulanan (Surat Masuk Berdasarkan Bulan)
        // -------------------------------------------------------------
        $suratPerBulan = Surat::select(
            \DB::raw("strftime('%m', created_at) as bulan"),
            \DB::raw('count(*) as total')
        )
            ->whereYear('created_at', $tahunIni)
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->pluck('total', 'bulan')
            ->toArray();

        $namaBulanIndo = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        $chartBulananLabels = [];
        $chartBulananData = [];

        foreach ($namaBulanIndo as $key => $namaBulan) {
            $chartBulananLabels[] = $namaBulan;
            $chartBulananData[] = $suratPerBulan[$key] ?? 0;
        }

        // -------------------------------------------------------------
        // CHART 2: Grafik Jenis Surat
        // -------------------------------------------------------------
        $semuaSurat = Surat::with(['jenisSurat'])->get();

        $grupJenis = $semuaSurat->groupBy(function ($surat) {
            if ($surat->jenisSurat) {
                return $surat->jenisSurat->nama
                    ?? $surat->jenisSurat->nama_jenis
                    ?? $surat->jenisSurat->jenis_surat
                    ?? 'Jenis Tidak Diketahui';
            }
            return 'Tanpa Kategori';
        });

        $chartJenisLabels = [];
        $chartJenisData = [];

        foreach ($grupJenis as $namaJenis => $item) {
            $chartJenisLabels[] = $namaJenis;
            $chartJenisData[] = $item->count();
        }

        if (empty($chartJenisLabels)) {
            $chartJenisLabels = ['Belum Ada Data'];
            $chartJenisData = [0];
        }

        // 3. Kirim semua variabel ke view dashboard pimpinan (Tanpa data permohonan)
        return view('layouts.pimpinan.app', [
            'title'              => 'Dashboard Pimpinan',
            'statistik'          => $dashboardStatistik,
            'chartBulananLabels' => $chartBulananLabels,
            'chartBulananData'   => $chartBulananData,
            'chartJenisLabels'   => $chartJenisLabels,
            'chartJenisData'     => $chartJenisData,
        ]);
    }
}