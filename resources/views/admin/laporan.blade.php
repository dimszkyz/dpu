@extends('layout.layoutadmin')

@section('title', 'Manajemen Laporan')

@section('content')
<style>
    @keyframes fadeInUp { 
        from { opacity: 0; transform: translateY(20px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .hud-panel { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
</style>

<div class="container mx-auto px-4 py-6 font-sans space-y-6">
    
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end border-b border-gray-300 pb-4 hud-panel">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                <svg class="w-7 h-7 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                </svg>
                Manajemen Berkas Laporan Kerja
            </h2>
            <p class="text-xs text-gray-500 mt-1">Gunakan panel ini untuk mengaudit kelayakan laporan pengerjaan tugas dari anggota.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hud-panel delay-100">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari berdasarkan nama tugas atau kode tugas..."
                           class="w-full pl-10 pr-4 py-2 text-xs font-medium bg-white border border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl transition uppercase tracking-wider">
                    Cari Data
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">ID Laporan</th>
                        <th class="py-3 px-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Nama Tugas</th>
                        <th class="py-3 px-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">NIP Pengirim</th>
                        <th class="py-3 px-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">Judul Laporan</th>
                        <th class="py-3 px-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">Status Validasi</th>
                        <th class="py-3 px-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($laporans as $l)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-4 text-xs font-semibold text-gray-500">
                                #LPR-{{ $l->id }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-xs font-bold text-gray-800 block">{{ $l->penugasan->tugas->nama_tugas ?? '-' }}</span>
                                <span class="text-[10px] font-mono text-gray-400 block mt-0.5">KODE: {{ $l->penugasan->kodetugas ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-4 text-xs font-medium text-gray-600">
                                {{ $l->user_id }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-xs font-bold text-gray-700 block">{{ $l->judul }}</span>
                                <span class="text-[10px] text-gray-400 block mt-0.5 truncate max-w-xs">{{ Str::limit($l->deskripsi, 50) }}</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($l->status === 'disetujui')
                                    <span class="px-2.5 py-1 text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-md uppercase tracking-wider">DISETUJUI</span>
                                @elseif($l->status === 'revisi')
                                    <span class="px-2.5 py-1 text-[10px] font-black text-amber-700 bg-amber-50 border border-amber-100 rounded-md uppercase tracking-wider">REVISI</span>
                                @elseif($l->status === 'menunggu')
                                    <span class="px-2.5 py-1 text-[10px] font-black text-blue-700 bg-blue-50 border border-blue-100 rounded-md uppercase tracking-wider">MENUNGGU</span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-black text-gray-600 bg-gray-50 border border-gray-200 rounded-md uppercase tracking-wider">{{ strtoupper($l->status) }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.laporan.show', $l->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold rounded-lg shadow-sm transition uppercase tracking-wider transform hover:scale-105">
                                    REVIEW
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-gray-400 font-medium italic text-xs">
                                Belum ada berkas data laporan kemajuan proyek masuk yang tersedia saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($laporans, 'links'))
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                {{ $laporans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection