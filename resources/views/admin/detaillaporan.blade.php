@extends('layout.layoutadmin')

@section('title', 'Detail Laporan Penugasan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">
    
    <div class="space-y-3 animate-slide-up">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <li>
                    <a href="{{ route('admin.laporan.index') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                        </svg>
                        Manajemen Laporan
                    </a>
                </li>
                <li>
                    <svg class="w-3 h-3 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                </li>
                <li class="text-slate-800 font-bold">Detail Laporan #LPR-{{ $laporan->id }}</li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-0.5 rounded-md uppercase tracking-wider font-mono">
                    ID PENUGASAN: #{{ $laporan->penugasan->kodetugas ?? $laporan->id_penugasan }}
                </span>
                <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight mt-2 leading-tight">
                    {{ $laporan->penugasan->tugas->nama_tugas ?? 'Laporan Penugasan' }}
                </h1>
                <p class="text-xs font-medium text-slate-400 mt-1">Dikirim oleh pengguna pada {{ \Carbon\Carbon::parse($laporan->created_at)->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
            
            <div class="self-start md:self-auto shrink-0">
                @if($laporan->status === 'disetujui')
                    <span class="px-4 py-2 text-xs font-black text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-xl uppercase tracking-wider shadow-sm">VALID / DISETUJUI</span>
                @elseif($laporan->status === 'revisi')
                    <span class="px-4 py-2 text-xs font-black text-amber-700 bg-amber-50 border border-amber-100 rounded-xl uppercase tracking-wider shadow-sm animate-pulse">STATUS: REVISI</span>
                @elseif($laporan->status === 'diajukan' || $laporan->status === 'menunggu')
                    <span class="px-4 py-2 text-xs font-black text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-xl uppercase tracking-wider shadow-sm">MENUNGGU REVIEW</span>
                @else
                    <span class="px-4 py-2 text-xs font-black text-slate-600 bg-slate-50 border border-slate-200 rounded-xl uppercase tracking-wider shadow-sm">{{ strtoupper($laporan->status) }}</span>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-start gap-3 animate-slide-up">
        <div class="p-2 bg-emerald-500/10 rounded-xl">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <div>
            <h3 class="text-sm font-bold text-emerald-800">Tindakan Berhasil!</h3>
            <p class="text-sm font-medium text-emerald-600 mt-0.5">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
        
        <div class="xl:col-span-2 space-y-6 animate-slide-up" style="animation-delay: 100ms;">
            
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Uraian Laporan Pekerjaan
                </h3>
                <div class="text-sm font-medium text-slate-700 leading-relaxed whitespace-pre-line bg-slate-50/50 p-6 rounded-xl border border-slate-100 shadow-inner">
                    {!! nl2br(e($laporan->teks_laporan ?? $laporan->deskripsi)) !!}
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Lampiran Berkas Anggota
                </h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($laporan->files ?? [] as $file)
                        <div class="flex items-center justify-between p-3.5 bg-white border border-slate-200/80 rounded-xl hover:border-indigo-400 hover:shadow-sm transition-all group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg shrink-0 font-mono text-[10px] font-black uppercase tracking-wider">
                                    {{ pathinfo($file->file_path, PATHINFO_EXTENSION) ?: 'FILE' }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate group-hover:text-indigo-600 transition-colors">
                                        {{ basename($file->file_path) }}
                                    </p>
                                    <p class="text-[9px] font-mono text-slate-400 uppercase mt-0.5">Ukuran: Tersedia</p>
                                </div>
                            </div>
                            
                            <a href="{{ asset('storage/' . $file->file_path) }}" download
                               class="p-2 bg-slate-50 group-hover:bg-slate-900 group-hover:text-white rounded-lg text-slate-400 transition-colors shadow-sm"
                               title="Unduh Berkas">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                        </div>
                    @empty
                        <div class="col-span-1 sm:col-span-2 text-center py-6 text-slate-400 italic text-xs">
                            Pengguna tidak melampirkan berkas fisik dalam laporan ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-slate-100 pb-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Ruang Diskusi & Catatan Revisi
                </h3>
                
                <div class="space-y-4 max-h-96 overflow-y-auto pr-2 pb-2">
                    @forelse($laporan->chats ?? [] as $chat)
                        @if($chat->is_from_admin_panel)
                            <div class="flex flex-col items-end gap-1 w-full">
                                <span class="text-[10px] font-bold text-slate-400 mr-2 uppercase tracking-wider">Anda (Admin) • {{ \Carbon\Carbon::parse($chat->created_at)->format('d M, H:i') }}</span>
                                <div class="bg-indigo-600 text-white px-4 py-3 rounded-2xl rounded-tr-sm text-xs font-medium shadow-sm shadow-indigo-500/20 max-w-[85%] leading-relaxed">
                                    {{ $chat->pesan }}
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-start gap-1 w-full">
                                <span class="text-[10px] font-bold text-slate-400 ml-2 uppercase tracking-wider">Anggota • {{ \Carbon\Carbon::parse($chat->created_at)->format('d M, H:i') }}</span>
                                <div class="bg-slate-100 text-slate-700 px-4 py-3 rounded-2xl rounded-tl-sm text-xs font-medium border border-slate-200/60 max-w-[85%] leading-relaxed">
                                    {{ $chat->pesan }}
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-8">
                            <div class="w-12 h-12 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                            </div>
                            <p class="text-slate-400 text-xs font-medium">Belum ada aktivitas obrolan atau catatan revisi.</p>
                        </div>
                    @endforelse
                </div>

                <form action="{{ route('admin.laporan.chat.store') }}" method="POST" class="mt-4 flex gap-2 border-t border-slate-100 pt-4">
                    @csrf
                    <input type="hidden" name="id_laporan" value="{{ $laporan->id }}">
                    <input type="text" name="pesan" placeholder="Ketik pesan evaluasi atau balasan revisi untuk anggota..." required
                           class="w-full px-4 py-2.5 text-xs font-medium text-slate-800 bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none">
                    <button type="submit" class="shrink-0 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-sm shadow-indigo-500/20 text-xs font-black uppercase tracking-wider transition-all hover:-translate-y-0.5">
                        Kirim
                    </button>
                </form>
            </div>

        </div>

        <div class="space-y-6 animate-slide-up" style="animation-delay: 150ms;">
            
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Keputusan Audit Admin
                </h3>
                
                <p class="text-xs text-slate-500 mb-2 leading-relaxed">Pilih tindakan untuk menilai kelayakan hasil laporan dari anggota ini.</p>

                <form action="{{ route('admin.laporan.updateStatus', $laporan->id) }}" method="POST" class="space-y-4" id="formUpdateStatus">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <select name="status" id="statusSelect" required class="w-full px-4 py-3 text-sm font-bold text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all cursor-pointer">
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="disetujui" {{ $laporan->status === 'disetujui' ? 'selected' : '' }}>Setujui Laporan (Valid)</option>
                            <option value="revisi" {{ $laporan->status === 'revisi' ? 'selected' : '' }}>Minta Revisi (Kembalikan)</option>
                        </select>
                    </div>

                    <div id="revisiContainer" class="hidden space-y-2">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pesan Revisi (Wajib)</label>
                        <textarea name="pesan_revisi" id="pesan_revisi" rows="3" class="w-full px-4 py-3 text-xs font-medium text-slate-700 bg-slate-50 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all resize-none" placeholder="Jelaskan bagian mana yang salah dan harus diperbaiki..."></textarea>
                    </div>

                    <button type="submit" class="w-full px-4 py-3 text-xs font-black text-white bg-slate-900 hover:bg-black rounded-xl uppercase tracking-widest transition-all shadow-md shadow-slate-900/20 hover:-translate-y-0.5">
                        Simpan Keputusan
                    </button>
                </form>
            </div>

            <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 shadow-inner space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Pengirim
                </h3>
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-200 border-2 border-white shadow-sm flex items-center justify-center text-slate-500 font-bold text-xs">
                        ID
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $laporan->user_id }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Anggota Penugasan</p>
                    </div>
                </div>
            </div>
            
        </div>
        
    </div>
</div>

<script>
    document.getElementById('statusSelect').addEventListener('change', function() {
        var revisiContainer = document.getElementById('revisiContainer');
        var inputRevisi = document.getElementById('pesan_revisi');
        
        if(this.value === 'revisi') {
            revisiContainer.classList.remove('hidden');
            inputRevisi.setAttribute('required', 'required');
        } else {
            revisiContainer.classList.add('hidden');
            inputRevisi.removeAttribute('required');
        }
    });

    if(document.getElementById('statusSelect').value === 'revisi') {
        document.getElementById('revisiContainer').classList.remove('hidden');
    }
</script>

<style>
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endsection