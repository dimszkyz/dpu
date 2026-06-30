<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POINTIFY - Memuat Sistem</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-950 min-h-screen flex items-center justify-center antialiased overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/25 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-20 w-96 h-96 bg-indigo-500/25 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <main class="relative w-full max-w-md px-6">
        <div class="bg-white/15 backdrop-blur-2xl border border-white/25 rounded-[2rem] shadow-2xl shadow-blue-950/40 overflow-hidden text-center animate-fade-in">
            <div class="h-px bg-gradient-to-r from-transparent via-white/60 to-transparent"></div>

            <div class="p-8 sm:p-10 space-y-7">
                <div class="flex justify-center">
                    <div class="w-28 h-28 rounded-3xl bg-white/20 backdrop-blur-xl border border-white/30 shadow-inner shadow-white/10 flex items-center justify-center p-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo POINTIFY" class="max-w-full max-h-full object-contain">
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-[10px] font-black text-blue-200 uppercase tracking-[0.3em]">Sistem Penugasan DPU</p>
                    <h1 class="text-3xl font-black text-white tracking-tight">
                        POINT<span class="text-blue-300">IFY</span>
                    </h1>
                    <p class="text-xs font-medium text-slate-200/80 leading-relaxed max-w-xs mx-auto">
                        Menyiapkan konsol penugasan dan pelaporan proyek infrastruktur daerah.
                    </p>
                </div>

                <div class="flex flex-col items-center gap-3">
                    <div class="w-12 h-12 border-4 border-white/15 border-t-blue-300 rounded-full animate-spin"></div>
                    <p class="text-[10px] font-black text-slate-200/70 uppercase tracking-widest">Memuat halaman login</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        setTimeout(function() {
            window.location.href = "{{ route('login') }}";
        }, 1800);
    </script>

    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.45s ease-out both; }
    </style>
</body>
</html>
