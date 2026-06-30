@extends('layout.layoutadmin')

@section('title', 'Dashboard Admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@php
    $statusItems = collect($statusPenugasan ?? [
        'Selesai' => 0,
        'Proses Review' => 0,
        'Ditugaskan' => 0,
        'Belum Ditugaskan' => 0,
    ]);

    $statusTotal = $statusPenugasanTotal ?? $statusItems->sum();
    $isStatusSynced = (int) $statusTotal === (int) ($totalTugas ?? 0);
@endphp

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .hud-panel { animation: fadeInUp .45s ease-out both; }
</style>

<div class="max-w-7xl mx-auto space-y-6 hud-panel">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dashboard Admin</h1>
            <p class="text-sm text-slate-500 mt-1">
                Ringkasan tugas, penugasan, laporan akhir, laporan harian, dan permohonan perpanjangan waktu.
            </p>
        </div>
        <div class="text-xs font-bold text-slate-600 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2">
            Sinkron status: 
            <span class="{{ $isStatusSynced ? 'text-emerald-600' : 'text-red-600' }}">
                {{ $statusTotal }} / {{ $totalTugas ?? 0 }} tugas
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <a href="{{ route('admin.user.index') }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total User</p>
            <p class="text-3xl font-black text-slate-900 mt-2">{{ $totalUser ?? 0 }}</p>
            <p class="text-xs text-slate-400 mt-1">Selain superadmin</p>
        </a>

        <a href="{{ route('admin.tugas.index') }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all">
            <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Total Tugas</p>
            <p class="text-3xl font-black text-blue-700 mt-2">{{ $totalTugas ?? 0 }}</p>
            <p class="text-xs text-slate-400 mt-1">Master tugas yang terdaftar</p>
        </a>

        <a href="{{ route('admin.laporan.index') }}" class="bg-blue-600 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-white/10 rounded-full"></div>
            <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest relative z-10">Cek Laporan</p>
            <p class="text-3xl font-black text-white mt-2 relative z-10">{{ $menungguReview ?? 0 }}</p>
            <p class="text-xs text-blue-100 mt-1 relative z-10">Laporan akhir berstatus diajukan</p>
        </a>

        <a href="{{ route('admin.laporan.index') }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all">
            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Selesai Bulan Ini</p>
            <p class="text-3xl font-black text-emerald-700 mt-2">{{ $selesaiBulanIni ?? 0 }}</p>
            <p class="text-xs text-slate-400 mt-1">Laporan akhir disetujui</p>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-950 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-black text-white uppercase tracking-widest">Status Penugasan</h2>
                    <p class="text-xs text-slate-400 mt-1">Kategori dihitung dari master tugas, sehingga total diagram sama dengan total tugas.</p>
                </div>
                <span class="px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $isStatusSynced ? 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20' : 'bg-red-500/10 text-red-300 border border-red-500/20' }}">
                    {{ $isStatusSynced ? 'Sinkron' : 'Cek Data' }}
                </span>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div class="h-72 flex items-center justify-center">
                    @if(($totalTugas ?? 0) > 0)
                        <canvas id="statusChart"></canvas>
                    @else
                        <div class="text-center text-slate-400 text-sm font-semibold italic">Belum ada tugas yang terdaftar.</div>
                    @endif
                </div>

                <div class="space-y-3">
                    @foreach($statusItems as $label => $value)
                        @php
                            $percent = ($totalTugas ?? 0) > 0 ? round(($value / $totalTugas) * 100, 1) : 0;
                            $colorClass = match($label) {
                                'Selesai' => 'bg-slate-900',
                                'Proses Review' => 'bg-yellow-400',
                                'Ditugaskan' => 'bg-blue-600',
                                default => 'bg-slate-300',
                            };
                        @endphp
                        <div class="flex items-center justify-between gap-4 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full {{ $colorClass }}"></span>
                                <div>
                                    <p class="text-xs font-black text-slate-800 uppercase tracking-wider">{{ $label }}</p>
                                    <p class="text-[10px] font-semibold text-slate-400">{{ $percent }}% dari total tugas</p>
                                </div>
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $value }}</p>
                        </div>
                    @endforeach

                    <div class="mt-4 p-4 rounded-xl {{ $isStatusSynced ? 'bg-emerald-50 border-emerald-100 text-emerald-700' : 'bg-red-50 border-red-100 text-red-700' }} border text-xs font-bold leading-relaxed">
                        Total status: {{ $statusTotal }} tugas. Total master tugas: {{ $totalTugas ?? 0 }} tugas.
                        @if($isStatusSynced)
                            Data diagram sudah sinkron.
                        @else
                            Ada selisih data yang perlu dicek pada relasi tugas/penugasan.
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 bg-red-50 border-b border-red-100">
                    <h2 class="text-sm font-black text-red-700 uppercase tracking-widest">Perlu Perhatian</h2>
                    <p class="text-xs text-red-500 mt-1">Penugasan yang belum selesai dan mendekati/melewati batas lapor.</p>
                </div>
                <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                    @forelse($urgentTugas ?? [] as $penugasan)
                        @php
                            $deadline = \Carbon\Carbon::parse($penugasan->batas_waktu_lapor);
                            $isLate = now()->greaterThan($deadline);
                        @endphp
                        <a href="{{ route('admin.penugasan.show', $penugasan->id) }}" class="block p-4 hover:bg-slate-50 transition-all">
                            <p class="text-sm font-black text-slate-900 truncate">{{ $penugasan->tugas->nama_tugas ?? 'Tugas tidak ditemukan' }}</p>
                            <p class="text-[10px] font-mono text-slate-400 mt-1">{{ $penugasan->kodetugas }} • {{ $deadline->locale('id')->translatedFormat('d M Y, H:i') }}</p>
                            <p class="text-[10px] font-black uppercase mt-2 {{ $isLate ? 'text-red-600' : 'text-yellow-600' }}">
                                {{ $isLate ? 'Terlambat' : 'Mendekati batas' }}
                            </p>
                        </a>
                    @empty
                        <div class="p-6 text-center text-xs text-slate-400 italic">Semua penugasan aman.</div>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.laporan.index') }}" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest">Laporan Harian</p>
                    <p class="text-2xl font-black text-slate-900 mt-2">{{ $totalLaporanHarian ?? 0 }}</p>
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="bg-white border border-red-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all">
                    <p class="text-[10px] font-black text-red-500 uppercase tracking-widest">Perpanjangan</p>
                    <p class="text-2xl font-black text-red-700 mt-2">{{ $permohonanPerpanjangan ?? 0 }}</p>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-950">
                <h2 class="text-sm font-black text-white uppercase tracking-widest">Tren Kinerja 6 Bulan</h2>
                <p class="text-xs text-slate-400 mt-1">Perbandingan tugas masuk dan laporan akhir disetujui.</p>
            </div>
            <div class="p-6 h-80">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-slate-950">
                <h2 class="text-sm font-black text-white uppercase tracking-widest">Laporan Akhir Baru</h2>
                <p class="text-xs text-slate-400 mt-1">Aktivitas laporan terbaru.</p>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($laporanBaru ?? [] as $laporan)
                    <a href="{{ route('admin.laporan.show', $laporan->id) }}" class="block p-4 hover:bg-slate-50 transition-all">
                        <p class="text-sm font-black text-blue-700 truncate">{{ $laporan->penugasan->tugas->nama_tugas ?? 'Laporan Masuk' }}</p>
                        <p class="text-[10px] font-mono text-slate-400 mt-1">#LPR-{{ $laporan->id }} • {{ $laporan->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</p>
                        <span class="inline-flex mt-2 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider {{ $laporan->status === 'diajukan' ? 'bg-blue-50 text-blue-700 border border-blue-100' : ($laporan->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100') }}">
                            {{ $laporan->status }}
                        </span>
                    </a>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400 italic">Belum ada laporan akhir terbaru.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-950 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-black text-white uppercase tracking-widest">Timeline Penugasan Terdekat</h2>
                <p class="text-xs text-slate-400 mt-1">Urutan berdasarkan batas waktu lapor terdekat.</p>
            </div>
            <a href="{{ route('admin.penugasan.index') }}" class="text-[10px] font-black text-white bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-xl uppercase tracking-wider">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-wider">Nama Tugas</th>
                        <th class="px-4 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-wider">Batas Lapor</th>
                        <th class="px-4 py-3 text-center text-[10px] font-black text-slate-400 uppercase tracking-wider">Status Laporan</th>
                        <th class="px-4 py-3 text-center text-[10px] font-black text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    @forelse($timelineTugas ?? [] as $penugasan)
                        @php
                            $laporanStatus = $penugasan->laporan->status ?? 'belum_lapor';
                        @endphp
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="px-4 py-4 text-xs font-mono font-bold text-blue-600">{{ $penugasan->kodetugas }}</td>
                            <td class="px-4 py-4 text-xs font-bold text-slate-800">{{ $penugasan->tugas->nama_tugas ?? '-' }}</td>
                            <td class="px-4 py-4 text-xs text-slate-500">{{ \Carbon\Carbon::parse($penugasan->batas_waktu_lapor)->locale('id')->translatedFormat('d M Y, H:i') }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $laporanStatus === 'disetujui' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($laporanStatus === 'diajukan' ? 'bg-blue-50 text-blue-700 border border-blue-100' : ($laporanStatus === 'revisi' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-slate-50 text-slate-500 border border-slate-100')) }}">
                                    {{ str_replace('_', ' ', $laporanStatus) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('admin.penugasan.show', $penugasan->id) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-black text-white text-[10px] font-black uppercase tracking-wider">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-xs text-slate-400 italic">Belum ada penugasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const statusLabels = @json($statusItems->keys()->values());
    const statusValues = @json($statusItems->values()->map(fn($value) => (int) $value)->values());
    const totalTugas = {{ (int) ($totalTugas ?? 0) }};

    if (document.getElementById('statusChart') && totalTugas > 0) {
        new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: ['#111827', '#FACC15', '#2563EB', '#CBD5E1'],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed || 0;
                                const percent = totalTugas > 0 ? ((value / totalTugas) * 100).toFixed(1) : 0;
                                return context.label + ': ' + value + ' tugas (' + percent + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    if (document.getElementById('trendChart')) {
        new Chart(document.getElementById('trendChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: @json($trendLabels ?? []),
                datasets: [
                    {
                        label: 'Tugas masuk',
                        data: @json($trendMasuk ?? []),
                        backgroundColor: '#111827',
                        borderRadius: 8,
                    },
                    {
                        label: 'Laporan disetujui',
                        data: @json($trendSelesai ?? []),
                        backgroundColor: '#2563EB',
                        borderRadius: 8,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#F1F5F9' } }
                },
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
</script>
@endsection
