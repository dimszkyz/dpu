<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - Sistem Penugasan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Kustomisasi scrollbar halus untuk sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #000000;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #1f2937;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }
    </style>
</head>

<body class="bg-slate-100 flex h-screen antialiased overflow-hidden">

    <aside class="w-66 bg-zinc-950 text-white flex flex-col shadow-2xl flex-shrink-0 border-r border-zinc-800/50">
        
        <div class="p-6 flex items-center space-x-3 border-b border-zinc-900 bg-black/40">
            <div class="bg-gradient-to-tr from-blue-500 to-indigo-600 p-2 rounded-xl shadow-lg shadow-blue-500/20 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-black tracking-widest text-zinc-200 uppercase leading-none">Sistem</span>
                <span class="text-base font-extrabold tracking-wider bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent mt-0.5">PENUGASAN</span>
            </div>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1 custom-scrollbar overflow-y-auto">
            
            <span class="px-3 block text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-2">Utama</span>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/10 font-semibold' : 'text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100' }}">
                <svg class="w-5 h-5 mr-3.5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/>
                </svg>
                Dashboard
            </a>

            <div class="pt-4 pb-2">
                <span class="px-3 block text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Data & Kontrol</span>
            </div>

            <a href="{{ route('admin.user.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.user.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/10 font-semibold' : 'text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100' }}">
                <svg class="w-5 h-5 mr-3.5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Manajemen User
            </a>

            <a href="{{ route('admin.tugas.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.tugas.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/10 font-semibold' : 'text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100' }}">
                <svg class="w-5 h-5 mr-3.5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Manajemen Tugas
            </a>

            <a href="{{ route('admin.penugasan.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.penugasan.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/10 font-semibold' : 'text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100' }}">
                <svg class="w-5 h-5 mr-3.5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Manajemen Penugasan
            </a>

            <a href="{{ route('admin.laporan.index') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.laporan.*') ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-600/10 font-semibold' : 'text-zinc-400 hover:bg-zinc-900 hover:text-zinc-100' }}">
                <svg class="w-5 h-5 mr-3.5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H3a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2z"/>
                </svg>
                Manajemen Laporan
            </a>
        </nav>

        <div class="p-4 border-t border-zinc-900 bg-black/20 space-y-2">
            <a href="{{ route('dashboard') }}" 
                class="flex items-center justify-center w-full px-4 py-2.5 text-xs font-bold text-zinc-300 bg-zinc-900/60 hover:bg-zinc-900 hover:text-white rounded-xl border border-zinc-800/60 transition-all duration-200 shadow-inner">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                LIHAT WEB USER
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center w-full px-4 py-2.5 text-xs font-black text-white bg-red-600/90 hover:bg-red-600 rounded-xl transition-all duration-200 shadow-md shadow-red-900/20 uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    LOG OUT
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden">
        
        <header class="bg-white border-b border-slate-200/80 h-16 flex items-center justify-between px-8 z-10 flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sesi Admin Aktif:</span>
                <span class="text-xs font-bold text-slate-700 bg-slate-100 border border-slate-200 px-3 py-1 rounded-lg shadow-sm">
                    {{ Auth::user()->name }}
                </span>
                <span class="text-[10px] font-extrabold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase tracking-widest">
                    {{ Auth::user()->role }}
                </span>
            </div>

            <div class="flex items-center">
                <div class="h-9 w-9 rounded-xl bg-gradient-to-tr from-slate-800 to-zinc-950 flex items-center justify-center text-white font-bold text-xs shadow-md border border-zinc-700">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-slate-200 p-4 text-center text-xs font-medium text-slate-400 flex-shrink-0 shadow-inner">
            &copy; {{ date('Y') }} Sistem Penugasan. Built with Laravel 12. &bull; Developer Console Panel.
        </footer>

    </div>

</body>
</html>