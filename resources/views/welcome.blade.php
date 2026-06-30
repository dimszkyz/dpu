<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memuat Sistem...</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen flex items-center justify-center">

    <div class="text-center p-6">
        <div class="mb-8 flex justify-center animate-bounce">
            <div class="w-32 h-32 flex items-center justify-center p-2">
                 <img src="{{ asset('images/logo.png') }}" alt="Logo" class="max-w-full max-h-full object-contain">
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-8 tracking-wide">Sistem Inventaris</h1>

        <div class="flex justify-center">
            <div class="w-14 h-14 border-4 border-gray-100 border-t-blue-600 rounded-full animate-spin"></div>
        </div>
    </div>

    <script>
        // Tunggu 2 detik (2000ms) lalu pindah ke halaman login
        setTimeout(function() {
            window.location.href = "{{ route('login') }}";
        }, 2000);
    </script>
</body>
</html>