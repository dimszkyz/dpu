<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - POINTIFY</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 antialiased overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-500/25 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-32 -left-20 w-96 h-96 bg-indigo-500/25 rounded-full blur-3xl"></div>

    <main class="relative min-h-screen flex items-center justify-center px-4 py-10">
        <section class="w-full max-w-md bg-white/15 backdrop-blur-2xl border border-white/25 rounded-[2rem] shadow-2xl shadow-blue-950/40 overflow-hidden">
            <div class="p-8 space-y-6">
                <div class="text-center space-y-2">
                    <div class="text-3xl font-black text-white">POINT<span class="text-blue-300">IFY</span></div>
                    <h1 class="text-xl font-black text-white">Lupa Kata Sandi</h1>
                    <p class="text-xs font-medium text-slate-200/75">Masukkan email akun Anda untuk menerima tautan pemulihan.</p>
                </div>

                @if (session('status'))
                    <div class="p-4 bg-emerald-400/15 border border-emerald-200/30 rounded-2xl text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="p-4 bg-red-400/15 border border-red-200/30 rounded-2xl text-sm font-semibold text-red-100">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                    @csrf
                    <input name="email" type="email" value="{{ old('email') }}" required autofocus class="block w-full px-4 py-3 bg-white/15 border border-white/25 text-sm font-medium text-white placeholder-slate-300/60 rounded-xl outline-none" placeholder="nama@email.com">
                    <button type="submit" class="w-full py-3 rounded-xl text-sm font-black text-white bg-gradient-to-r from-blue-500 to-indigo-500 uppercase tracking-wider">Kirim Tautan</button>
                </form>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-xs font-bold text-blue-200 hover:text-white hover:underline">Kembali ke Login</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
