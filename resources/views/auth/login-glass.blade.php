<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POINTIFY</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body{font-family:'Plus Jakarta Sans',sans-serif}</style>
</head>
<body class="min-h-screen bg-slate-950 antialiased overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/25 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-20 w-96 h-96 bg-indigo-500/25 rounded-full blur-3xl"></div>
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <main class="relative min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            <section class="hidden lg:flex bg-white/15 backdrop-blur-2xl border border-white/25 rounded-[2rem] shadow-2xl shadow-blue-950/40 overflow-hidden relative p-10 flex-col justify-center">
                <div class="absolute top-0 right-0 w-80 h-80 bg-blue-400/15 rounded-full blur-3xl"></div>
                <div class="relative space-y-10 z-10">
                    <div class="flex items-center gap-3">
                        <div class="bg-white/15 backdrop-blur-xl p-3 rounded-2xl border border-white/25">
                            <span class="text-yellow-300 text-xl font-black">⚡</span>
                        </div>
                        <div>
                            <div class="text-2xl font-black tracking-wider text-white">POINT<span class="text-blue-300">IFY</span></div>
                            <div class="text-[10px] font-bold text-slate-200/70 tracking-widest uppercase">Sistem Penugasan</div>
                        </div>
                    </div>

                    <div class="space-y-4 max-w-xl">
                        <div class="text-[10px] font-black text-blue-200 uppercase tracking-widest">Konsol Utama Agen</div>
                        <h1 class="text-4xl xl:text-5xl font-black text-white tracking-tight leading-tight">Masuk ke Sistem Informasi Penugasan DPU.</h1>
                        <p class="text-sm font-medium text-slate-200/80 leading-relaxed">Pantau distribusi tugas, perkembangan laporan, dan pekerjaan lapangan secara transparan melalui dashboard POINTIFY.</p>
                    </div>
                </div>
            </section>

            <section class="bg-white/15 backdrop-blur-2xl border border-white/25 rounded-[2rem] shadow-2xl shadow-blue-950/40 overflow-hidden">
                <div class="h-px bg-gradient-to-r from-transparent via-white/60 to-transparent"></div>
                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="lg:hidden flex items-center justify-center gap-3 mb-8">
                        <div class="bg-white/15 backdrop-blur-xl px-3 py-2 rounded-xl border border-white/25 text-yellow-300 font-black">⚡</div>
                        <div>
                            <div class="text-xl font-black tracking-wider text-white">POINT<span class="text-blue-300">IFY</span></div>
                            <div class="text-[9px] font-bold text-slate-200/70 tracking-widest uppercase">Sistem Penugasan</div>
                        </div>
                    </div>

                    <div class="text-center lg:text-left space-y-2 mb-8">
                        <p class="text-[10px] font-black text-blue-200 uppercase tracking-[0.25em]">Secure Login</p>
                        <h2 class="text-2xl md:text-3xl font-black text-white tracking-tight">Masuk ke Akun Anda</h2>
                        <p class="text-xs font-medium text-slate-200/75 leading-relaxed">Gunakan email dan kata sandi yang telah terdaftar pada sistem.</p>
                    </div>

                    @if (session('status'))
                        <div class="mb-6 p-4 bg-emerald-400/15 border border-emerald-200/30 rounded-2xl text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-400/15 border border-red-200/30 rounded-2xl text-sm font-semibold text-red-100">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ url('login') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="space-y-2">
                            <label for="email" class="block text-xs font-bold text-slate-100 uppercase tracking-wider">Alamat Email</label>
                            <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required class="block w-full px-4 py-3 bg-white/15 backdrop-blur-xl border border-white/25 text-sm font-medium text-white placeholder-slate-300/60 rounded-xl focus:bg-white/20 focus:ring-4 focus:ring-blue-300/20 focus:border-blue-200 transition-all outline-none" placeholder="nama@email.com">
                        </div>

                        <div class="space-y-2">
                            <label for="password" class="block text-xs font-bold text-slate-100 uppercase tracking-wider">Kata Sandi</label>
                            <input id="password" name="password" type="password" autocomplete="current-password" required class="block w-full px-4 py-3 bg-white/15 backdrop-blur-xl border border-white/25 text-sm font-medium text-white placeholder-slate-300/60 rounded-xl focus:bg-white/20 focus:ring-4 focus:ring-blue-300/20 focus:border-blue-200 transition-all outline-none" placeholder="••••••••">
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="flex items-center gap-2 text-xs font-semibold text-slate-200/80 cursor-pointer">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-500 focus:ring-blue-300 border-white/30 rounded cursor-pointer bg-white/20">
                                Ingat saya
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-200 hover:text-white hover:underline">Lupa kata sandi?</a>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-xl shadow-lg shadow-blue-950/30 text-sm font-black text-white bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-400 hover:to-indigo-400 focus:outline-none focus:ring-4 focus:ring-blue-300/25 transition-all duration-200 hover:-translate-y-0.5 uppercase tracking-wider">Masuk Sekarang</button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-white/15 text-center">
                        <p class="text-[10px] font-bold text-slate-200/60 uppercase tracking-widest">&copy; {{ date('Y') }} POINTIFY. Sistem Penugasan DPU.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
