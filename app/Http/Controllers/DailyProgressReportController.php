<?php

namespace App\Http\Controllers;

use App\Models\DailyProgressReport;
use App\Models\Penugasan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyProgressReportController extends Controller
{
    public function show($id)
    {
        $penugasan = Penugasan::with([
            'tugas',
            'admin',
            'anggota.user',
            'laporan',
            'dailyProgressReports' => function ($query) {
                $query->where('id_user', Auth::id())->orderBy('tanggal_laporan');
            },
        ])->findOrFail($id);

        $this->authorizeUserAccess($penugasan);

        $startDate = Carbon::parse($penugasan->tugas->tanggal_mulai ?? $penugasan->created_at)->startOfDay();
        $endDate = Carbon::parse($penugasan->tugas->tanggal_selesai ?? $penugasan->batas_waktu_lapor)->startOfDay();
        $today = now()->startOfDay();
        $reportsByDate = $penugasan->dailyProgressReports->keyBy(fn ($report) => $report->tanggal_laporan->toDateString());
        $calendarDays = collect();
        $firstMissingDate = null;

        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $dateKey = $date->toDateString();
            $report = $reportsByDate->get($dateKey);
            $isFuture = $date->greaterThan($today);
            $status = $report ? 'sudah_lapor' : ($isFuture ? 'menunggu' : 'belum_lapor');

            if (!$firstMissingDate && $status === 'belum_lapor') {
                $firstMissingDate = $dateKey;
            }

            $calendarDays->push([
                'date' => $date->copy(),
                'date_key' => $dateKey,
                'status' => $status,
                'report' => $report,
            ]);
        }

        $selectedDate = old('tanggal_laporan', $firstMissingDate ?? min($today->toDateString(), $endDate->toDateString()));
        $todayReport = $reportsByDate->get(now()->toDateString());
        $missingCount = $calendarDays->where('status', 'belum_lapor')->count();

        return view('detailpenugasanuser-progress', compact(
            'penugasan',
            'calendarDays',
            'reportsByDate',
            'selectedDate',
            'todayReport',
            'missingCount',
            'startDate',
            'endDate'
        ));
    }

    public function store(Request $request, $id_penugasan)
    {
        $penugasan = Penugasan::with('tugas')->findOrFail($id_penugasan);

        $this->authorizeUserAccess($penugasan);

        $startDate = Carbon::parse($penugasan->tugas->tanggal_mulai ?? $penugasan->created_at)->startOfDay();
        $endDate = Carbon::parse($penugasan->tugas->tanggal_selesai ?? $penugasan->batas_waktu_lapor)->endOfDay();

        $validated = $request->validate([
            'tanggal_laporan' => ['required', 'date', 'after_or_equal:' . $startDate->toDateString(), 'before_or_equal:' . $endDate->toDateString()],
            'progres' => ['required', 'string', 'max:3000'],
            'kendala' => ['nullable', 'string', 'max:3000'],
            'rencana_lanjut' => ['nullable', 'string', 'max:3000'],
        ], [
            'tanggal_laporan.required' => 'Tanggal laporan harian wajib diisi.',
            'tanggal_laporan.after_or_equal' => 'Tanggal laporan tidak boleh sebelum tanggal mulai tugas.',
            'tanggal_laporan.before_or_equal' => 'Tanggal laporan tidak boleh melewati tanggal selesai tugas.',
            'progres.required' => 'Isi progres harian wajib diisi.',
        ]);

        DailyProgressReport::updateOrCreate(
            [
                'id_penugasan' => $penugasan->id,
                'id_user' => Auth::id(),
                'tanggal_laporan' => $validated['tanggal_laporan'],
            ],
            [
                'progres' => $validated['progres'],
                'kendala' => $validated['kendala'] ?? null,
                'rencana_lanjut' => $validated['rencana_lanjut'] ?? null,
            ]
        );

        return redirect()->route('penugasan.show', $penugasan->id)->with('success', 'Laporan progres harian berhasil disimpan.');
    }

    public function pendingSummary()
    {
        $today = now()->toDateString();

        $penugasans = Penugasan::with(['tugas', 'dailyProgressReports' => function ($query) use ($today) {
                $query->where('id_user', Auth::id())->whereDate('tanggal_laporan', $today);
            }])
            ->whereHas('anggota', function ($query) {
                $query->where('id_user', Auth::id());
            })
            ->get()
            ->filter(function ($penugasan) use ($today) {
                $start = Carbon::parse($penugasan->tugas->tanggal_mulai ?? $penugasan->created_at)->toDateString();
                $end = Carbon::parse($penugasan->tugas->tanggal_selesai ?? $penugasan->batas_waktu_lapor)->toDateString();

                return $today >= $start && $today <= $end && $penugasan->dailyProgressReports->isEmpty();
            })
            ->values();

        return response()->json([
            'count' => $penugasans->count(),
            'items' => $penugasans->map(function ($penugasan) {
                return [
                    'id' => $penugasan->id,
                    'nama_tugas' => $penugasan->tugas->nama_tugas ?? 'Penugasan',
                    'url' => route('penugasan.show', $penugasan->id),
                ];
            }),
        ]);
    }

    private function authorizeUserAccess(Penugasan $penugasan): void
    {
        $isMember = $penugasan->anggota()->where('id_user', Auth::id())->exists();

        if (!$isMember && !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Anda tidak memiliki akses ke penugasan ini.');
        }
    }
}
