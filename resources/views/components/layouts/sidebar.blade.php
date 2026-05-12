@php
    $user = auth()->user();
    $menus = [
        [
            'label' => 'Dashboard',
            'icon' => 'dashboard',
            'route' => '/',
            'isActive' => request()->routeIs('dashboard'),
        ],
        [
            'label' => 'Patient Management',
            'icon' => 'group',
            'route' => 'patient-management',
            'isActive' => request()->routeIs('patient-management'),
        ],
        [
            'label' => 'Content CMS',
            'icon' => 'library_books',
            'route' => 'cms',
            'isActive' => request()->routeIs('cms'),
        ],
        [
            'label' => 'Fasting Protocols',
            'icon' => 'timer',
            'route' => 'fasting-protocols',
            'isActive' => request()->routeIs('fasting-protocols'),
        ],
        [
            'label' => 'FGB Monitoring',
            'icon' => 'health_metrics',
            'route' => 'fgb-monitoring',
            'isActive' => request()->routeIs('fgb-monitoring'),
        ],
        [
            'label' => 'Fasting Logs',
            'icon' => 'list_alt',
            'route' => 'logs',
            'isActive' => request()->routeIs('logs'),
        ],
        [
            'label' => 'Audit Trail',
            'icon' => 'history_edu',
            'route' => 'audit-trail',
            'isActive' => request()->routeIs('audit-trail'),
        ],
    ];
@endphp

<aside
    class="h-screen w-64 fixed left-0 top-0 bg-slate-50 dark:bg-slate-900 flex flex-col py-6 px-4 space-y-2 overflow-y-auto z-50">
    <div class="mb-8 px-2">
        <h1
            class="font-bold text-blue-700 dark:text-blue-400 font-headline text-xl">
            Clinician Portal</h1>
        <p
            class="text-xs text-on-surface-variant tracking-wide font-medium">
            Diabetes Care Admin</p>
    </div>
    <nav class="flex-1 space-y-1">
        @foreach ($menus as $menu)
            <a class="flex items-center justify-between py-3 px-4 {{ $menu['isActive'] ? 'text-blue-700 dark:text-blue-400 font-bold bg-blue-50/50 dark:bg-blue-900/20 relative' : 'text-slate-600 dark:text-slate-400 hover:text-blue-600 hover:bg-blue-50/30 dark:hover:bg-slate-800 transition-all' }}  group rounded-lg"
                href="{{ $menu['route'] }}">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined"
                        data-icon="{{ $menu['icon'] }}">{{ $menu['icon'] }}</span>
                    <span
                        class="font-label tracking-wide">{{ $menu['label'] }}</span>
                </div>
                @if ($menu['isActive'])
                    <div
                        class="sidebar-indicator absolute right-0 top-2 bottom-2 bg-blue-700">
                    </div>
                @endif
            </a>
        @endforeach

    </nav>
    <div
        class="mt-auto p-4 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-blue-200 overflow-hidden">
            <img alt="Administrator Profile"
                class="w-full h-full object-cover"
                data-alt="professional portrait of a female clinician in a white lab coat with a stethoscope against a neutral medical background"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuAk_7Sa4MZkqraZWMzwL66nkySYucK0wMe4RO0ium8yCtKd9taOd7Q6qEhxgn14DK_97l0hy7dKaNGSYTddKfLory12Iur_qKIPntgBds9OJmapSyYVmtmfMaNVSMOXtCWxlyH6mW1h7Q3fBv0iuHkWR_nTpeUCmHkgpDvYMRulLiXhs-J1P6mG0RCXO1Fgp-oEsK-aq5bmJmLJJd5pGepxO0aYnolLelsjsZjvfVHf0ttvAO9KwFBnkvUXlJ-fC7PWQ-6Gc_NsKYk" />
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-bold text-on-surface truncate">
                {{ $user->adminProfile->name ?? 'User Admin' }}</p>
            <p class="text-xs text-on-surface-variant truncate">Chief Admin
            </p>

        </div>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button
                class="p-1.5 text-slate-500 hover:text-error hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-colors flex items-center justify-center"
                title="Logout">
                <span class="material-symbols-outlined text-xl">logout</span>
            </button>
        </form>
    </div>
</aside>
