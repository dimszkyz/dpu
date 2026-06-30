<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tugas;
use App\Models\Penugasan;
use App\Models\Laporan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan Dashboard Utama Sisi Administrator
     */
    public function index()
    {
        // 1. STATISTIK UTAMA ADMIN
        $totalUser = User::where('role', '!=', 'superadmin')->count();
        $tugasAktif = Penugasan::count(); 
        $menungguReview = Laporan::where('status', 'menunggu')->count();
        $selesaiBulanIni = Laporan::whereMonth('created_at', Carbon::now()->month)->count();

        // 2. RADAR DEADLINE & TIMELINE
        $urgentTugas = Penugasan::with(['tugas'])
                            ->where('batas_waktu_lapor', '<=', Carbon::now()->addDays(3))
                            ->orderBy('batas_waktu_lapor', 'asc')
                            ->take(5)->get();

        $timelineTugas = Penugasan::with(['tugas'])
                              ->orderBy('batas_waktu_lapor', 'asc')
                              ->take(5)->get();

        $laporanBaru = Laporan::with(['penugasan.tugas', 'penugasan.anggota.user'])
                      ->orderBy('created_at', 'desc')
                      ->take(5)->get();

        // 3. GRAFIK DONAT (Status Penugasan)
        $totalSelesai = Laporan::count(); 
        $totalTerlambat = Penugasan::where('batas_waktu_lapor', '<', Carbon::now()->format('Y-m-d'))->count();
        
        // Menghitung Tugas yang Belum Ditugaskan
        $tugasDitugaskan = Penugasan::pluck('kodetugas')->toArray();
        $belumDitugaskan = Tugas::whereNotIn('kodetugas', $tugasDitugaskan)->count();

        // 4. GRAFIK TREN KINERJA (6 Bulan Terakhir)
        $trendLabels = [];
        $trendMasuk = [];
        $trendSelesai = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trendLabels[] = $date->translatedFormat('M Y'); 

            // Hitung Tugas Masuk berdasarkan tanggal_mulai di tabel Tugas
            $trendMasuk[] = Tugas::whereMonth('tanggal_mulai', $date->month)
                                 ->whereYear('tanggal_mulai', $date->year)
                                 ->count();

            // Hitung Tugas Selesai berdasarkan Laporan yang dibuat
            $trendSelesai[] = Laporan::whereMonth('created_at', $date->month)
                                     ->whereYear('created_at', $date->year)
                                     ->count();
        }

        return view('admin.dashboardadmin', compact(
            'totalUser', 'tugasAktif', 'menungguReview', 'selesaiBulanIni', 
            'urgentTugas', 'laporanBaru', 'timelineTugas',
            'totalSelesai', 'totalTerlambat', 'belumDitugaskan',
            'trendLabels', 'trendMasuk', 'trendSelesai'
        ));
    }

    /**
     * Menampilkan Dashboard Utama Sisi User / Agen (Mekanisme Engine DSS)
     */
    public function indexUser()
    {
        $userId = Auth::id();

        // 1. Ambil semua data penugasan yang melibatkan Agen yang sedang login
        $penugasanUser = Penugasan::whereHas('anggota', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->with(['tugas', 'laporan'])->get();

        // 2. Perhitungan KPI Counter Berbasis Real-Data Agen
        $totalTugasDikerjakan = $penugasanUser->count();
        
        // Tugas lapor segera (Deadline kurang dari atau sama dengan 24 jam dan belum disetujui)
        $totalLaporSegera = $penugasanUser->filter(function ($p) {
            $isSelesai = $p->laporan && $p->laporan->status === 'disetujui';
            // PERBAIKAN: Menggunakan ->lte() sebagai ganti ->isLessThanOrEqualTo()
            $isMendesak = Carbon::parse($p->batas_waktu_lapor)->lte(Carbon::now()->addDay());
            return !$isSelesai && $isMendesak;
        })->count();

        // Tugas perlu unggah laporan (Belum ada laporan sama sekali atau statusnya disuruh revisi)
        $totalPerluUnggah = $penugasanUser->filter(function ($p) {
            return !$p->laporan || $p->laporan->status === 'revisi';
        })->count();

        // Tugas selesai (Laporan sudah berstatus disetujui oleh admin)
        $totalSelesai = $penugasanUser->filter(function ($p) {
            return $p->laporan && $p->laporan->status === 'disetujui';
        })->count();

        // 3. ENGINE MECHANISM DSS: Mengurutkan rekomendasi tugas aktif dari batas waktu terdekat (prioritas atas)
        $rekomendasiPrioritas = $penugasanUser->filter(function ($p) {
            return !$p->laporan || $p->laporan->status !== 'disetujui';
        })->sortBy('batas_waktu_lapor')->take(5);

        // 4. DAFTAR STATUS PELAPORAN TUGAS SIKLUS VERIFIKASI
        $statusPelaporan = $penugasanUser->map(function ($p) {
            $statusText = 'belum diajukan';
            if ($p->laporan) {
                $statusText = $p->laporan->status; // diajukan, revisi, atau disetujui
            }
            return [
                'kodetugas' => $p->kodetugas,
                'nama_tugas' => $p->tugas->nama_tugas ?? '-',
                'batas_waktu' => Carbon::parse($p->batas_waktu_lapor)->translatedFormat('d M Y'),
                'status' => $statusText
            ];
        });

        // 5. Perhitungan Akurasi Waktu untuk Rekomendasi Karir/Performa Agen
        $tepatWaktu = $penugasanUser->filter(function ($p) {
            if ($p->laporan && $p->laporan->status === 'disetujui') {
                // PERBAIKAN: Menggunakan ->lte() sebagai ganti ->isLessThanOrEqualTo()
                return Carbon::parse($p->laporan->updated_at)->lte(Carbon::parse($p->batas_waktu_lapor));
            }
            return false;
        })->count();
        
        $rasioAkurasi = $totalSelesai > 0 ? round(($tepatWaktu / $totalSelesai) * 100, 1) : 100;

        // Kirim seluruh variabel terhitung ke view 'dashboard.blade.php'
        return view('dashboard', compact(
            'totalTugasDikerjakan', 'totalLaporSegera', 'totalPerluUnggah', 'totalSelesai',
            'rekomendasiPrioritas', 'statusPelaporan', 'rasioAkurasi'
        ));
    }
}