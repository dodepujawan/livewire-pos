<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
    <style>
        @keyframes blobMove {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(30px, -50px) scale(1.1); }
            66%      { transform: translate(-20px, 20px) scale(0.95); }
        }
        @keyframes floatUp {
            0%   { transform: translateY(0); }
            50%  { transform: translateY(-12px); }
            100% { transform: translateY(0); }
        }
        @keyframes scanLine {
            0%   { transform: translateY(-100%); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(2200%); opacity: 0; }
        }
        .blob {
            animation: blobMove 12s ease-in-out infinite;
            filter: blur(60px);
        }
        .blob-delay {
            animation-delay: -4s;
        }
        .blob-delay-2 {
            animation-delay: -8s;
        }
        .login-card {
            animation: floatUp 6s ease-in-out infinite;
        }
        .scan-line {
            animation: scanLine 3.5s linear infinite;
        }
        @media (prefers-reduced-motion: reduce) {
            .blob, .login-card, .scan-line {
                animation: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden bg-slate-950">

    <!-- Animated background blobs -->
    <div class="absolute inset-0">
        <div class="blob absolute top-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-emerald-500/30"></div>
        <div class="blob blob-delay absolute bottom-[-15%] right-[-5%] w-[550px] h-[550px] rounded-full bg-indigo-500/30"></div>
        <div class="blob blob-delay-2 absolute top-[30%] right-[20%] w-[350px] h-[350px] rounded-full bg-teal-400/20"></div>
    </div>

    <!-- Subtle grid overlay (receipt paper vibe) -->
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 40px 40px;">
    </div>

    <div class="login-card relative w-full max-w-md mx-4">

        <!-- Glass card -->
        <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl p-8 overflow-hidden">

            <!-- Scan line accent -->
            <div class="scan-line absolute left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-400 to-transparent"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-400/30 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18M3 3v4h18V3M5 7v13a1 1 0 001 1h12a1 1 0 001-1V7M9 12h6M9 16h6" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Masuk ke Sistem</h2>
                <p class="text-slate-400 text-sm mt-1">Silakan login untuk melanjutkan transaksi</p>
            </div>

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/30 text-red-300 text-sm px-4 py-3 mb-5 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.process') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-300">Email</label>
                    <input type="email" name="email" required
                        class="w-full bg-white/5 border border-white/15 rounded-lg px-4 py-2.5 text-white placeholder-slate-500
                               focus:outline-none focus:ring-2 focus:ring-emerald-400/60 focus:border-emerald-400/60
                               transition-all duration-200"
                        placeholder="nama@perusahaan.com">
                </div>

                <div>
                    <label class="block mb-1.5 text-sm font-medium text-slate-300">Password</label>
                    <input type="password" name="password" required
                        class="w-full bg-white/5 border border-white/15 rounded-lg px-4 py-2.5 text-white placeholder-slate-500
                               focus:outline-none focus:ring-2 focus:ring-emerald-400/60 focus:border-emerald-400/60
                               transition-all duration-200"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-300 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-white/20 bg-white/5 text-emerald-500 focus:ring-emerald-400/60 focus:ring-offset-0">
                        Ingat saya
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-semibold py-2.5 rounded-lg
                           transition-all duration-200 shadow-lg shadow-emerald-500/25
                           hover:shadow-emerald-400/40 hover:-translate-y-0.5 active:translate-y-0">
                    Login
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-xs mt-6">
            &copy; {{ date('Y') }} Sistem Point of Sale. Semua hak dilindungi.
        </p>
    </div>

</body>
</html>
<!-- <!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" name="remember" class="mr-2">
                <label>Remember me</label>
            </div>

            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Login
            </button>
        </form>
    </div>

</body>
</html> -->
