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
            'isActive' => request()->routeIs('fasting-logs') || request()->is('fasting-logs*'),
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
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                
                <!-- Admin Notification Dropdown -->
                <div x-data="adminNotificationsDropdown()" class="relative">
                    <button @click="toggleOpen()"
                        class="p-2 text-slate-500 hover:text-blue-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors rounded-full relative focus:outline-none">
                        <span class="material-symbols-outlined"
                            data-icon="notifications">notifications</span>
                        <span x-show="unreadCount > 0"
                            class="absolute top-1 right-1 min-w-3.5 h-3.5 px-0.5 bg-rose-600 text-[8px] text-white font-extrabold rounded-full flex items-center justify-center leading-none"
                            x-text="unreadCount"
                            style="display: none;"></span>
                    </button>

                    <!-- Dropdown Content -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl z-50 overflow-hidden divide-y divide-slate-100 dark:divide-slate-800"
                        style="display: none;">
                        
                        <div class="px-4 py-2.5 flex justify-between items-center bg-slate-50 dark:bg-slate-850">
                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 font-headline">Notifikasi Medis</span>
                            <button x-show="unreadCount > 0" @click="markAllAsRead()"
                                class="text-[9px] font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400">Tandai semua dibaca</button>
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800">
                            <template x-for="item in notifications" :key="item.id">
                                <div class="p-3 hover:bg-slate-50 dark:hover:bg-slate-850 flex gap-2.5 transition-colors relative"
                                    :class="!item.read_at ? 'bg-blue-50/20 dark:bg-blue-900/10' : ''">
                                    
                                    <div class="w-1 rounded-full shrink-0 my-0.5"
                                        :class="{
                                            'bg-rose-500': item.type === 'critical',
                                            'bg-amber-500': item.type === 'warning',
                                            'bg-blue-500': item.type === 'info'
                                        }"></div>
                                    
                                    <div class="flex-1 space-y-0.5">
                                        <div class="flex justify-between items-start gap-1">
                                            <p class="text-[11px] font-bold text-slate-850 dark:text-slate-200" x-text="item.title"></p>
                                            <span class="text-[8px] text-slate-400 shrink-0" x-text="item.created_at_human"></span>
                                        </div>
                                        <p class="text-[10px] text-slate-600 dark:text-slate-400 leading-normal" x-text="item.message"></p>
                                        
                                        <div class="flex justify-between items-center pt-1">
                                            <template x-if="item.action_url">
                                                <a :href="item.action_url" @click="markAsRead(item.id)"
                                                    class="text-[9px] font-bold text-blue-600 hover:underline flex items-center gap-0.5">
                                                    Tinjau Tindakan <span class="material-symbols-outlined text-[10px]">arrow_forward</span>
                                                </a>
                                            </template>
                                            <template x-if="!item.read_at">
                                                <button @click="markAsRead(item.id)"
                                                    class="text-[8px] font-semibold text-slate-450 hover:text-slate-700 dark:hover:text-slate-350 ml-auto">Tandai dibaca</button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="notifications.length === 0" class="p-6 text-center text-[10px] text-slate-400">
                                Tidak ada notifikasi baru.
                            </div>
                        </div>

                        <div class="px-4 py-2 bg-slate-50 dark:bg-slate-850 text-center">
                            <a href="/safety-alerts" class="text-[9px] font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-355">Lihat Semua Alert</a>
                        </div>
                    </div>
                </div>

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
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminNotificationsDropdown', () => ({
                open: false,
                unreadCount: 0,
                notifications: [],
                pollInterval: null,

                init() {
                    this.fetchNotifications();
                    this.pollInterval = setInterval(() => {
                        this.fetchNotifications();
                    }, 30000);
                },

                destroy() {
                    if (this.pollInterval) clearInterval(this.pollInterval);
                },

                async fetchNotifications() {
                    try {
                        const response = await fetch('/admin-notifications');
                        const data = await response.json();
                        this.unreadCount = data.unread_count;
                        this.notifications = data.notifications;
                    } catch (error) {
                        console.error('Error fetching admin notifications:', error);
                    }
                },

                toggleOpen() {
                    this.open = !this.open;
                    if (this.open) {
                        this.fetchNotifications();
                    }
                },

                async markAsRead(id) {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        await fetch(`/admin-notifications/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        });
                        await this.fetchNotifications();
                    } catch (error) {
                        console.error('Error marking notification as read:', error);
                    }
                },

                async markAllAsRead() {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        await fetch('/admin-notifications/read-all', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        });
                        await this.fetchNotifications();
                    } catch (error) {
                        console.error('Error marking all notifications as read:', error);
                    }
                }
            }));
        });
    </script>
    @stack('scripts')
</body>

</html>
