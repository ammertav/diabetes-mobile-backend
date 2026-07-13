@php
    $menus = [
        [
            'label' => 'Dashboard',
            'isActive' => request()->routeIs('dashboard'),
        ],
        [
            'label' => 'Patient Management',
            'isActive' => request()->routeIs('patients-management') || request()->is('patients*'),
        ],
        [
            'label' => 'Content CMS',
            'isActive' => request()->routeIs('cms') || request()->is('cms*'),
        ],
        [
            'label' => 'Fasting Protocols',
            'isActive' => request()->routeIs('fasting-protocols') || request()->is('fasting-protocols*'),
        ],
        [
            'label' => 'FGB Monitoring',
            'isActive' => request()->routeIs('fgb-monitoring') || request()->is('fgb-monitoring*'),
        ],
        [
            'label' => 'Fasting Logs',
            'isActive' => request()->routeIs('logs') || request()->is('logs*'),
        ],
        [
            'label' => 'Audit Trail',
            'isActive' => request()->routeIs('audit-trail') || request()->is('audit-trail*'),
        ],
    ];

    $activeMenu = collect($menus)->firstWhere('isActive', true);
    $activeTitle = $activeMenu ? $activeMenu['label'] : 'The Clinical Sanctuary';
@endphp
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $activeTitle }} | Diabetes Care Admin</title>
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
    @include('layouts.partials._styles')
    @stack('styles')
</head>

<body class="text-on-surface bg-[#f8fafc] dark:bg-slate-950">
    <!-- SideNavBar (The Blade) -->
    <x-layouts.sidebar />
    <!-- Main Content Area -->
    <main class="ml-64 min-h-screen">
        <!-- TopNavBar -->
        <header
            class="flex justify-between items-center px-8 w-full bg-white/80 backdrop-blur-xl sticky top-0 z-40 h-16 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-8">
                <div
                    class="text-xl font-bold text-blue-700 dark:text-blue-400 tracking-tight font-headline">
                    {{ $activeTitle }}</div>
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
    @stack('scripts')
</body>

</html>
