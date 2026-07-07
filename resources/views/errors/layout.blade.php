<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title') | Clinical Sanctuary</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .primary-gradient {
            background: linear-gradient(135deg, #004ac6 0%, #2563eb 100%);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.3; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }
        .animate-pulse-ring {
            animation: pulse-ring 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-[#f7f9fb] font-body text-[#191c1e] selection:bg-[#dbe1ff] min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
    <!-- Background Texture -->
    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-[#dbe1ff] blur-[120px] rounded-full"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-[#6ffbbe] blur-[120px] rounded-full opacity-20"></div>
    </div>

    <!-- Error Card Container -->
    <main class="relative z-10 w-full max-w-lg text-center animate-float">
        <!-- Icon Indicator -->
        <div class="flex justify-center mb-6">
            <div class="relative w-24 h-24 flex items-center justify-center">
                <div class="absolute inset-0 bg-[#004ac6]/10 rounded-full animate-pulse-ring"></div>
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg border border-[#eceef0] relative z-10">
                    <span class="material-symbols-outlined text-4xl text-[#004ac6]">
                        @yield('icon')
                    </span>
                </div>
            </div>
        </div>

        <!-- Error Card -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-[0_32px_64px_-12px_rgba(0,0,0,0.06)] border border-[#eceef0] overflow-hidden p-8 md:p-10 mb-8">
            <div class="inline-flex px-3 py-1 bg-[#dbe1ff] text-[#003ea8] rounded-full font-label text-xs font-bold tracking-widest uppercase mb-4">
                ERROR @yield('code')
            </div>
            
            <h1 class="font-headline font-black text-3xl md:text-4xl text-[#191c1e] tracking-tight mb-4">
                @yield('message_title')
            </h1>
            
            <p class="text-[#434655] text-base leading-relaxed mb-8">
                @yield('message_body')
            </p>

            <!-- Navigation Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.history.back()" class="px-6 py-3.5 bg-white border border-[#c3c6d7] text-[#191c1e] rounded-lg font-headline font-semibold text-sm tracking-wide shadow-sm hover:bg-[#eceef0] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Go Back
                </button>
                <a href="{{ url('/') }}" class="px-6 py-3.5 primary-gradient text-white rounded-lg font-headline font-bold text-sm tracking-wide shadow-lg shadow-[#004ac6]/20 hover:shadow-xl hover:scale-[1.01] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-lg">home</span>
                    Return to Home
                </a>
            </div>
        </div>

        <!-- System Branding -->
        <div class="flex flex-col items-center gap-1 opacity-60">
            <div class="flex items-center gap-1 text-[11px] font-label font-bold uppercase tracking-widest text-[#434655]">
                <span class="material-symbols-outlined text-sm text-[#006c49]">verified_user</span>
                Clinical Sanctuary
            </div>
            <span class="text-[10px] font-label text-[#434655]">Secure Clinical Environment</span>
        </div>
    </main>
</body>
</html>
