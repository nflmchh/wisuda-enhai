<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Absensi Wisuda Hybrid</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-950 to-blue-800 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
            <svg class="w-9 h-9 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">Absensi Wisuda Hybrid</h1>
        <p class="text-blue-200 text-sm mt-1">Sistem Absensi QR Code</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-lg font-semibold text-gray-800 mb-6">Masuk ke Panel Admin</h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              @error('email') border-red-400 @enderror"
                       placeholder="admin@wisuda.local">
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
            </div>

            <button type="submit"
                    class="w-full bg-blue-700 hover:bg-blue-800 text-white font-medium py-2.5 px-4 rounded-lg transition-colors text-sm">
                Masuk
            </button>
        </form>
    </div>

    <p class="text-center text-blue-300 text-xs mt-6">
        Absensi Wisuda Hybrid &copy; {{ date('Y') }}
    </p>
</div>

</body>
</html>
