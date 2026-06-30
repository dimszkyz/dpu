@extends('layout.layoutadmin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Tambah User Baru
            </h1>
            <p class="text-xs font-medium text-slate-400 mt-1">Daatarkan pengguna baru ke dalam sistem penugasan beserta penentuan hak aksesnya.</p>
        </div>
        <div>
            <a href="{{ route('admin.user.index') }}" 
                class="flex items-center px-4 py-2.5 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                KEMBALI
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-xs font-semibold rounded-xl space-y-1 shadow-sm">
            <div class="flex items-center gap-2 mb-1 text-red-700 font-bold">
                <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Terjadi kesalahan pengisian data:
            </div>
            <ul class="list-disc pl-6 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <form action="{{ route('admin.user.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="nip" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nomor Induk Pegawai (NIP)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0"/>
                            </svg>
                        </span>
                        <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 text-xs font-medium text-slate-800 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all outline-none" 
                            placeholder="Contoh: 19900101XXXXXXXXXX">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 text-xs font-medium text-slate-800 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all outline-none" 
                            placeholder="Masukkan nama lengkap pegawai">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Alamat Email Resmi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 text-xs font-medium text-slate-800 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all outline-none" 
                            placeholder="nama@instansi.go.id">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Hak Akses Sistem (Role)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <select name="role" id="role" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 text-xs font-semibold text-slate-700 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all outline-none appearance-none cursor-pointer">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Pilih Tingkat Hak Akses --</option>
                            <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>USER / ANGGOTA</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>ADMINISTRATOR</option>
                            <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>SUPER ADMIN</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Kata Sandi (Password)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 text-xs font-medium text-slate-800 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all outline-none" 
                            placeholder="Minimal 8 karakter unik">
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Ulangi Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </span>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="block w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 text-xs font-medium text-slate-800 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition-all outline-none" 
                            placeholder="Pastikan kata sandi sama persis">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="reset" 
                    class="px-5 py-2.5 text-xs font-bold text-slate-500 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors">
                    KOSONGKAN FORM
                </button>
                <button type="submit" 
                    class="px-6 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 rounded-xl shadow-md shadow-blue-500/10 transition-all duration-200 hover:-translate-y-0.5">
                    SIMPAN USER BARU
                </button>
            </div>
        </form>
    </div>
</div>
@endsection