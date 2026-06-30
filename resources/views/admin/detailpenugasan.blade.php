@extends('layout.layoutadmin')

@section('title', 'Detail Penugasan')

@section('content')
<style>
    /* CSS Animasi dan HUD Panel */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .hud-panel {
        opacity: 0;
        animation: fadeInUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
    
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="container mx-auto px-4 py-6 font-sans">
    
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end border-b border-gray-300 pb-4 hud-panel">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center uppercase tracking-wide">
                <svg class="w-7 h-7 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                Detail Distribusi Penugasan
            </h2>
            <p class="text-sm text-gray-500 mt-1 font-mono">>_ SYSTEM_PROMPT: Menampilkan relasi tugas dan entitas pengguna.</p>
        </div>
        <div class="text-right mt-4 md:mt-0 flex items-center space-x-3">
            <a href="{{ route('admin.penugasan.index') }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-bold text-xs flex items-center shadow-sm uppercase tracking-wider">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-100 flex items-center hidden sm:flex">
                <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse mr-2"></div>
                <p id="liveTime" class="font-semibold text-gray-800 text-sm"></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="hud-panel delay-100 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
                <div class="bg-black px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <h3 class="text-white text-sm font-bold tracking-wide uppercase">Referensi Tugas Utama</h3>
                    </div>
                    <a href="{{ route('admin.tugas.show', $penugasan->kodetugas) }}" class="text-[10px] bg-gray-800 text-yellow-400 hover:text-white px-3 py-1 rounded border border-gray-700 transition tracking-wider font-bold">
                        CEK MASTER TUGAS
                    </a>
                </div>

                <div class="p-6 bg-gray-50/50">
                    <h3 class="text-2xl font-black text-gray-900 mb-1">{{ $penugasan->tugas->nama_tugas ?? 'Tugas Telah Dihapus' }}</h3>
                    <div class="mb-6 flex items-center">
                        <span class="text-xs font-mono font-bold text-blue-700 bg-blue-100 px-3 py-1.5 rounded border border-blue-200 shadow-inner">
                            ID: {{ $penugasan->kodetugas }}
                        </span>
                    </div>

                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-200 pb-2 mb-4">Deskripsi / Instruksi</h4>
                    <div class="bg-white p-5 rounded-xl border border-gray-100 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap font-medium shadow-sm min-h-[100px]">
                        {{ $penugasan->tugas->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}
                    </div>
                </div>
            </div>

            <div class="hud-panel delay-200 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
                <div class="bg-black px-6 py-4 flex items-center">
                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <h3 class="text-white text-sm font-bold tracking-wide uppercase">Jaringan Entitas Terlibat</h3>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <div>
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-200 pb-2 mb-4">Otoritas Pendelegasi</h4>
                            <div class="flex items-center bg-gray-50 border border-gray-200 p-4 rounded-xl shadow-sm">
                                <div class="w-10 h-10 bg-gray-900 rounded-full flex items-center justify-center mr-4 shadow-inner">
                                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $penugasan->admin->name ?? 'Admin / Sistem' }}</p>
                                    <p class="text-[10px] text-gray-500 font-mono mt-0.5">ROLE: PEMBERI TUGAS</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest border-b border-gray-200 pb-2 mb-4">Penerima Penugasan</h4>
                            <div class="space-y-3 max-h-[250px] overflow-y-auto pr-2 scrollbar-hide">
                                @forelse($penugasan->anggota ?? [] as $anggota)
                                    <div class="flex items-center bg-blue-50 border border-blue-100 p-3 rounded-xl shadow-sm hover:bg-blue-100 transition">
                                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3 shadow">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $anggota->user->name ?? 'User Tidak Diketahui' }}</p>
                                            <p class="text-[10px] text-blue-600 font-mono font-bold mt-0.5">NIP: {{ $anggota->user->nip ?? '-' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="bg-gray-50 border border-dashed border-gray-300 p-4 rounded-xl text-center">
                                        <p class="text-xs text-gray-500 font-bold italic">Belum ada penerima tugas.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="hud-panel delay-200 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
                <div class="bg-black px-5 py-3 flex items-center">
                    <svg class="w-4 h-4 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-white text-xs font-bold tracking-wide uppercase">Status & Tenggat Waktu</h3>
                </div>
                
                @php
                    $today = \Carbon\Carbon::today();
                    $batasLapor = \Carbon\Carbon::parse($penugasan->batas_waktu_lapor);
                    $sisaHari = $batasLapor->diffInDays($today, false) * -1;
                @endphp

                <div class="p-6 border-b border-gray-100">
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-2 text-center">Kondisi Penugasan Saat Ini</p>
                    @if ($sisaHari < 0)
                        <div class="bg-red-50 border-2 border-red-500 text-red-600 rounded-xl p-4 text-center shadow-sm">
                            <h4 class="text-xl font-black uppercase tracking-widest">TERLEWAT</h4>
                            <p class="text-xs font-bold mt-1">Melewati Batas {{ abs($sisaHari) }} Hari</p>
                        </div>
                    @else
                        <div class="bg-blue-50 border-2 border-blue-500 text-blue-700 rounded-xl p-4 text-center shadow-sm">
                            <h4 class="text-xl font-black uppercase tracking-widest">AKTIF</h4>
                            <p class="text-xs font-bold mt-1">{{ $sisaHari == 0 ? 'Batas Waktu Hari Ini' : 'Sisa Waktu: ' . floor($sisaHari) . ' Hari' }}</p>
                        </div>
                    @endif
                </div>

                <div class="p-5 space-y-5 bg-gray-50/50">
                    <div class="flex items-center">
                        <div class="w-1.5 h-10 bg-gray-300 rounded-full mr-4"></div>
                        <div>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-0.5">Didelegasikan Pada</p>
                            <p class="text-sm font-bold text-gray-900 font-mono">{{ $penugasan->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="w-1.5 h-10 {{ $sisaHari < 0 ? 'bg-red-500' : 'bg-yellow-400' }} rounded-full mr-4"></div>
                        <div>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-0.5">Batas Pengumpulan Laporan</p>
                            <p class="text-sm font-black {{ $sisaHari < 0 ? 'text-red-600' : 'text-gray-900' }} font-mono">{{ $batasLapor->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hud-panel delay-300 space-y-3">
                <a href="{{ route('admin.penugasan.create', ['kodetugas' => $penugasan->kodetugas]) }}" class="w-full py-3.5 bg-yellow-400 text-gray-900 rounded-xl hover:bg-yellow-500 transition-transform transform hover:-translate-y-1 shadow-md flex items-center justify-center font-black tracking-widest uppercase text-xs border border-yellow-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Revisi Parameter Tugas
                </a>

                <form action="{{ route('admin.penugasan.destroy', $penugasan->id) }}" method="POST" onsubmit="confirmDelete(event, this)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3.5 bg-white text-red-600 border-2 border-red-200 rounded-xl hover:bg-red-50 hover:border-red-400 transition-colors shadow-sm flex items-center justify-center font-black tracking-widest uppercase text-xs">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Cabut Penugasan Ini
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Live Time Update
    function updateClock() {
        const opt = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        document.getElementById('liveTime').innerText = new Date().toLocaleDateString('id-ID', opt) + ' WIB';
    }
    setInterval(updateClock, 1000); updateClock();

    // SweetAlert untuk Cabut Penugasan
    function confirmDelete(event, form) {
        event.preventDefault();
        Swal.fire({
            title: 'CABUT PENUGASAN?',
            text: "Akses user terhadap tugas ini akan diputus permanen dan laporan tidak dapat diterima lagi.",
            icon: 'warning',
            background: '#ffffff',
            color: '#111827',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#4b5563',
            confirmButtonText: 'Ya, Cabut Tugas!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-xl border border-gray-200 shadow-2xl',
                title: 'font-black tracking-wide uppercase',
                confirmButton: 'rounded-lg px-5 py-2.5 text-sm font-bold shadow-md',
                cancelButton: 'rounded-lg px-5 py-2.5 text-sm font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) { form.submit(); }
        });
    }
</script>
@endsection