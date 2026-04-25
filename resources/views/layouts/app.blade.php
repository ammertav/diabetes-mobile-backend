<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>The Clinical Sanctuary | Diabetes Care Admin</title>
    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&amp;family=Inter:wght@300;400;500;600&amp;display=swap"
        rel="stylesheet" />
    <!-- Icons -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <!-- Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries">
    </script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary": "#ffffff",
                        "tertiary": "#ab0b1c",
                        "surface-variant": "#e0e3e5",
                        "on-background": "#191c1e",
                        "on-secondary-container": "#00714d",
                        "outline-variant": "#c3c6d7",
                        "primary": "#004ac6",
                        "on-primary-container": "#eeefff",
                        "inverse-on-surface": "#eff1f3",
                        "surface-tint": "#0053db",
                        "tertiary-fixed-dim": "#ffb3ad",
                        "outline": "#737686",
                        "on-secondary-fixed": "#002113",
                        "error-container": "#ffdad6",
                        "primary-fixed-dim": "#b4c5ff",
                        "surface-container-high": "#e6e8ea",
                        "primary-fixed": "#dbe1ff",
                        "surface-container-low": "#f2f4f6",
                        "background": "#f7f9fb",
                        "secondary-fixed": "#6ffbbe",
                        "surface-container-lowest": "#ffffff",
                        "inverse-primary": "#b4c5ff",
                        "tertiary-container": "#cf2c30",
                        "secondary": "#006c49",
                        "inverse-surface": "#2d3133",
                        "on-tertiary": "#ffffff",
                        "surface": "#f7f9fb",
                        "surface-bright": "#f7f9fb",
                        "on-primary-fixed-variant": "#003ea8",
                        "secondary-container": "#6cf8bb",
                        "error": "#ba1a1a",
                        "on-tertiary-fixed-variant": "#930013",
                        "on-surface": "#191c1e",
                        "surface-container-highest": "#e0e3e5",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",
                        "on-tertiary-fixed": "#410004",
                        "on-tertiary-container": "#ffecea",
                        "secondary-fixed-dim": "#4edea3",
                        "on-primary": "#ffffff",
                        "primary-container": "#2563eb",
                        "on-primary-fixed": "#00174b",
                        "surface-container": "#eceef0",
                        "tertiary-fixed": "#ffdad7",
                        "on-surface-variant": "#434655",
                        "on-secondary-fixed-variant": "#005236",
                        "surface-dim": "#d8dadc"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fb;
        }

        .font-headline {
            font-family: 'Manrope', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .sidebar-indicator {
            width: 4px;
            border-radius: 9999px;
        }

        .glass-header {
            backdrop-filter: blur(20px);
        }

        .primary-gradient {
            background: linear-gradient(135deg, #004ac6 0%, #2563eb 100%);
        }
    </style>
    @stack('styles')
</head>

<body class="text-on-surface">
    <!-- SideNavBar (The Blade) -->
    <x-layouts.sidebar />
    <!-- Main Content Area -->
    <main class="ml-64 min-h-screen">
        <!-- TopNavBar -->
        <header
            class="flex justify-between items-center px-8 w-full bg-white/80 backdrop-blur-xl sticky top-0 z-40 h-16 shadow-none">
            <div class="flex items-center gap-8">
                <div
                    class="text-xl font-bold text-blue-700 dark:text-blue-400 tracking-tight font-headline">
                    The Clinical Sanctuary</div>
                <div class="relative w-64">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant scale-75">search</span>
                    <input
                        class="w-full pl-10 pr-4 py-1.5 bg-surface-container-highest border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all"
                        placeholder="Search patients..." type="text" />
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button
                    class="p-2 text-slate-500 hover:text-blue-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors rounded-full relative">
                    <span class="material-symbols-outlined"
                        data-icon="notifications">notifications</span>
                    <span
                        class="absolute top-2 right-2 w-2 h-2 bg-tertiary rounded-full"></span>
                </button>
                <button
                    class="p-2 text-slate-500 hover:text-blue-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors rounded-full">
                    <span class="material-symbols-outlined"
                        data-icon="settings">settings</span>
                </button>
            </div>
        </header>

        <!-- Canvas -->
        <div class="p-8 space-y-8">
            @yield('content')
        </div>
    </main>
    <!-- FAB for Global Quick Actions -->
    <button
        class="fixed bottom-8 right-8 primary-gradient text-white w-14 h-14 rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-transform z-50">
        <span class="material-symbols-outlined text-3xl">add</span>
    </button>
    @stack('scripts')
</body>

</html>
