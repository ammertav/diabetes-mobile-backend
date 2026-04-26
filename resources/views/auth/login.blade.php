<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries">
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-surface": "#191c1e",
                        "inverse-surface": "#2d3133",
                        "surface-bright": "#f7f9fb",
                        "surface-variant": "#e0e3e5",
                        "surface-container-low": "#f2f4f6",
                        "error-container": "#ffdad6",
                        "secondary-fixed-dim": "#4edea3",
                        "surface-dim": "#d8dadc",
                        "on-error-container": "#93000a",
                        "secondary": "#006c49",
                        "on-surface-variant": "#434655",
                        "on-error": "#ffffff",
                        "surface-container": "#eceef0",
                        "inverse-primary": "#b4c5ff",
                        "background": "#f7f9fb",
                        "surface-container-highest": "#e0e3e5",
                        "primary-container": "#2563eb",
                        "tertiary-fixed": "#ffdad7",
                        "on-secondary-container": "#00714d",
                        "secondary-fixed": "#6ffbbe",
                        "on-background": "#191c1e",
                        "on-tertiary-container": "#ffecea",
                        "on-secondary-fixed-variant": "#005236",
                        "tertiary": "#ab0b1c",
                        "on-primary-container": "#eeefff",
                        "tertiary-fixed-dim": "#ffb3ad",
                        "surface": "#f7f9fb",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary": "#ffffff",
                        "on-tertiary-fixed": "#410004",
                        "primary-fixed": "#dbe1ff",
                        "primary-fixed-dim": "#b4c5ff",
                        "on-secondary-fixed": "#002113",
                        "surface-container-high": "#e6e8ea",
                        "surface-tint": "#0053db",
                        "on-primary": "#ffffff",
                        "on-tertiary-fixed-variant": "#930013",
                        "on-tertiary": "#ffffff",
                        "secondary-container": "#6cf8bb",
                        "error": "#ba1a1a",
                        "outline": "#737686",
                        "primary": "#004ac6",
                        "inverse-on-surface": "#eff1f3",
                        "on-primary-fixed-variant": "#003ea8",
                        "on-primary-fixed": "#00174b",
                        "outline-variant": "#c3c6d7",
                        "tertiary-container": "#cf2c30"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                        "label": ["Inter", "sans-serif"]
                    }
                },
            },
        }
    </script>
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
    </style>
</head>

<body
    class="bg-surface font-body text-on-surface selection:bg-primary-fixed min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
    <!-- Background Texture -->
    <div class="absolute inset-0 z-0 opacity-40 pointer-events-none">
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary-fixed-dim blur-[120px] rounded-full">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-secondary-container blur-[120px] rounded-full opacity-30">
        </div>
    </div>
    <!-- Login Container -->
    <main class="relative z-10 w-full max-w-md">
        <!-- Brand Identity -->
        <div class="flex flex-col items-center mb-10">
            <div
                class="w-16 h-16 bg-surface-container-lowest rounded-xl flex items-center justify-center shadow-xl mb-4">
                <span class="material-symbols-outlined text-primary text-4xl"
                    style="font-variation-settings: 'FILL' 1;">health_and_safety</span>
            </div>
            <h1
                class="font-headline font-black text-2xl tracking-tighter text-primary uppercase">
                Clinical Sanctuary</h1>
            <p
                class="font-label text-on-surface-variant text-sm tracking-wide mt-1">
                Diabetes Management Admin Panel</p>
        </div>
        <!-- Login Card -->
        <div
            class="bg-surface-container-lowest rounded-xl shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)] overflow-hidden">
            <div class="p-8 md:p-10">
                <div class="mb-8">
                    <h2
                        class="font-headline text-2xl font-bold text-on-surface">
                        Secure Sign In</h2>
                    <p class="text-on-surface-variant text-sm mt-2">Enter your
                        credentials to access the clinical dashboard.</p>
                </div>
                <form action="{{ route('login') }}" class="space-y-6"
                    method="POST">
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label
                            class="font-label text-xs font-semibold uppercase tracking-widest text-on-surface-variant ml-1"
                            for="email">Work Email</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-outline">
                                <span
                                    class="material-symbols-outlined text-xl">alternate_email</span>
                            </div>
                            <input
                                class="block w-full pl-11 pr-4 py-3.5 bg-surface-container-highest border-none rounded-lg font-body text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all duration-300"
                                id="email" name="email"
                                placeholder="name@clinicalsanctuary.com"
                                required="" type="email" />
                        </div>
                    </div>
                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-end ml-1">
                            <label
                                class="font-label text-xs font-semibold uppercase tracking-widest text-on-surface-variant"
                                for="password">Password</label>
                            <a class="text-primary text-xs font-semibold hover:underline"
                                href="#">Forgot Password?</a>
                        </div>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-outline">
                                <span
                                    class="material-symbols-outlined text-xl">lock</span>
                            </div>
                            <input
                                class="block w-full pl-11 pr-4 py-3.5 bg-surface-container-highest border-none rounded-lg font-body text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all duration-300"
                                id="password" name="password"
                                placeholder="••••••••••••" required=""
                                type="password" />
                        </div>
                    </div>
                    <!-- CTA Button -->
                    <button
                        class="w-full primary-gradient text-white py-4 rounded-lg font-headline font-bold text-sm tracking-wide shadow-lg shadow-primary/20 hover:shadow-xl hover:scale-[1.01] active:scale-[0.98] transition-all duration-200"
                        type="submit">
                        Sign In to System
                    </button>
                </form>
                <div
                    class="mt-8 pt-8 border-t border-surface-container flex flex-col items-center gap-4">
                    <p class="text-on-surface-variant text-sm">Need
                        institutional access?</p>
                    <button
                        class="font-label text-sm font-semibold text-primary px-6 py-2 rounded-full bg-surface-container-high hover:bg-surface-container-highest transition-colors">
                        <a href="{{ 'register-page' }}">Register new
                            account</a>
                    </button>
                </div>
            </div>
            <!-- HIPAA Compliance Banner -->
            <div
                class="bg-surface-container px-8 py-3 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-secondary text-sm"
                    style="font-variation-settings: 'FILL' 1;">verified_user</span>
                <span
                    class="text-[10px] font-label font-bold uppercase tracking-tighter text-on-surface-variant">HIPAA
                    Compliant Environment</span>
            </div>
        </div>
        <!-- System Status Mini-Card -->
        <div class="mt-8 flex justify-between items-center px-4">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-secondary-fixed-dim"></div>
                <span
                    class="text-[11px] font-label text-on-surface-variant font-medium">Server
                    Status: Optimal</span>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="text-[11px] font-label text-on-surface-variant font-medium">v2.4.0-stable</span>
            </div>
        </div>
    </main>
    <!-- Footer Component from Shared JSON -->
    <footer
        class="w-full py-6 mt-auto flex flex-row justify-between items-center px-12 border-t border-[#f1f3f5] dark:border-[#1f2225]">
        <div class="flex gap-6">
            <a class="font-['Inter'] text-[11px] tracking-wide uppercase opacity-60 text-[#44474a] hover:text-[#004ac6] transition-opacity"
                href="#">Privacy Policy</a>
            <a class="font-['Inter'] text-[11px] tracking-wide uppercase opacity-60 text-[#44474a] hover:text-[#004ac6] transition-opacity"
                href="#">Terms of Service</a>
            <a class="font-['Inter'] text-[11px] tracking-wide uppercase opacity-60 text-[#44474a] hover:text-[#004ac6] transition-opacity"
                href="#">Security Whitepaper</a>
        </div>
        <div
            class="font-['Inter'] text-[11px] tracking-wide uppercase opacity-60 text-[#44474a]">
            © 2024 Clinical Sanctuary. HIPAA Compliant Environment.
        </div>
    </footer>
    <!-- Decorative Image for Context (Off-screen/Subtle) -->
    <div
        class="fixed top-0 right-0 h-screen w-1/3 opacity-5 pointer-events-none hidden lg:block">
        <img class="h-full w-full object-cover"
            data-alt="clean medical laboratory environment with modern equipment and bright clinical lighting in a soft focus background"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDfFikCC0oRFVdv_CskthoRuWR4OxeNKnVEh_jFzZXR78yv7S_ckWa8uxL_VBTcbS2fzrOu4OJ2cFsoPAeG9dYS8RllVbszPfRyzLM9AXD6mFeSFB4qedUsVDaf4wE_jSCo_szhSXWQaWCd5lMqimF81B0kOwQJWfeA2Lcm3zqnax5iB2Edd14YwZu2ozRRV1gZJIIHfVf6IoAXb7xXlYk0zZf-HJ1y-U4tubn4bBMhlxTGpFJu_ZYau2lGhMox34ccVuBeL4ffiRw" />
    </div>
</body>

</html>
