@extends('layouts.app')

<!-- Hero Metrics: Bento Style -->
@section('content')
    <div class="grid grid-cols-12 gap-6">
        <!-- Total Patients Card -->
        <div
            class="col-span-12 md:col-span-4 bg-surface-container-lowest p-8 rounded-xl shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <span
                    class="text-sm font-label font-semibold text-on-surface-variant tracking-wider uppercase">Total
                    Patients</span>
                <div class="mt-4 flex items-end gap-2">
                    <span
                        class="text-4xl font-headline font-extrabold text-on-surface">1,284</span>
                    <span
                        class="text-secondary font-bold text-sm mb-1 flex items-center">
                        <span
                            class="material-symbols-outlined scale-75">arrow_upward</span>
                        4.2%
                    </span>
                </div>
                <p class="mt-2 text-xs text-on-surface-variant">Active
                    monitoring cases</p>
            </div>
            <div
                class="absolute -right-4 -bottom-4 opacity-5 text-blue-700">
                <span
                    class="material-symbols-outlined text-9xl">group</span>
            </div>
        </div>
        <!-- At-Risk Patients Card -->
        <div
            class="col-span-12 md:col-span-4 bg-surface-container-lowest p-8 rounded-xl shadow-sm relative overflow-hidden group border-l-4 border-tertiary">
            <div class="relative z-10">
                <span
                    class="text-sm font-label font-semibold text-on-surface-variant tracking-wider uppercase">At-Risk
                    Patients</span>
                <div class="mt-4 flex items-end gap-2">
                    <span
                        class="text-4xl font-headline font-extrabold text-tertiary">142</span>
                    <span
                        class="text-tertiary font-bold text-sm mb-1 flex items-center">
                        <span
                            class="material-symbols-outlined scale-75">warning</span>
                        12%
                    </span>
                </div>
                <p class="mt-2 text-xs text-on-surface-variant">High FGB
                    readings ( &gt;130 mg/dL )</p>
            </div>
            <div
                class="absolute -right-4 -bottom-4 opacity-5 text-tertiary">
                <span
                    class="material-symbols-outlined text-9xl">priority_high</span>
            </div>
        </div>
        <!-- Compliance Rate -->
        <div
            class="col-span-12 md:col-span-4 primary-gradient p-8 rounded-xl shadow-lg relative overflow-hidden text-white">
            <div class="relative z-10">
                <span
                    class="text-sm font-label font-semibold opacity-80 tracking-wider uppercase">Avg.
                    Fasting Compliance</span>
                <div class="mt-4 flex items-end gap-2">
                    <span
                        class="text-4xl font-headline font-extrabold">92.8%</span>
                    <span
                        class="bg-white/20 px-2 py-0.5 rounded text-xs font-bold mb-1">Target
                        90%</span>
                </div>
                <div
                    class="mt-4 w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-white h-full"
                        style="width: 92.8%;"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Trends & Critical Alerts Grid -->
    <div class="grid grid-cols-12 gap-8">
        <!-- Large Chart Area -->
        <div
            class="col-span-12 xl:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-sm">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2
                        class="text-xl font-headline font-bold text-on-surface">
                        Weekly Average FGB Trends</h2>
                    <p class="text-sm text-on-surface-variant">
                        Aggregate fasting blood glucose levels over 7
                        days</p>
                </div>
                <select
                    class="bg-surface-container-low border-none rounded-lg text-sm font-medium py-2 px-4 outline-none focus:ring-2 focus:ring-primary/20">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                </select>
            </div>
            <!-- Visualization Placeholder: Elegant Bar Chart UI -->
            <div
                class="h-64 flex items-end justify-between gap-4 px-4">
                <div
                    class="flex flex-col items-center gap-2 flex-1 group">
                    <div
                        class="w-full bg-surface-container-low rounded-t-lg relative flex items-end justify-center h-48 transition-all hover:bg-primary/10">
                        <div
                            class="w-3/4 bg-blue-400/30 rounded-t-md h-[72%] transition-all group-hover:bg-primary-container">
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-on-surface-variant">Mon</span>
                </div>
                <div
                    class="flex flex-col items-center gap-2 flex-1 group">
                    <div
                        class="w-full bg-surface-container-low rounded-t-lg relative flex items-end justify-center h-48 transition-all hover:bg-primary/10">
                        <div
                            class="w-3/4 bg-blue-400/30 rounded-t-md h-[65%] transition-all group-hover:bg-primary-container">
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-on-surface-variant">Tue</span>
                </div>
                <div
                    class="flex flex-col items-center gap-2 flex-1 group">
                    <div
                        class="w-full bg-surface-container-low rounded-t-lg relative flex items-end justify-center h-48 transition-all hover:bg-primary/10">
                        <div
                            class="w-3/4 bg-blue-400/30 rounded-t-md h-[85%] transition-all group-hover:bg-primary-container">
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-on-surface-variant">Wed</span>
                </div>
                <div
                    class="flex flex-col items-center gap-2 flex-1 group">
                    <div
                        class="w-full bg-surface-container-low rounded-t-lg relative flex items-end justify-center h-48 transition-all hover:bg-primary/10">
                        <div
                            class="w-3/4 bg-blue-400/30 rounded-t-md h-[92%] transition-all group-hover:bg-primary-container">
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-on-surface-variant">Thu</span>
                </div>
                <div
                    class="flex flex-col items-center gap-2 flex-1 group">
                    <div
                        class="w-full bg-surface-container-low rounded-t-lg relative flex items-end justify-center h-48 transition-all hover:bg-primary/10">
                        <div
                            class="w-3/4 bg-tertiary/40 rounded-t-md h-[98%] transition-all group-hover:bg-tertiary">
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-on-surface-variant">Fri</span>
                </div>
                <div
                    class="flex flex-col items-center gap-2 flex-1 group">
                    <div
                        class="w-full bg-surface-container-low rounded-t-lg relative flex items-end justify-center h-48 transition-all hover:bg-primary/10">
                        <div
                            class="w-3/4 bg-blue-400/30 rounded-t-md h-[60%] transition-all group-hover:bg-primary-container">
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-on-surface-variant">Sat</span>
                </div>
                <div
                    class="flex flex-col items-center gap-2 flex-1 group">
                    <div
                        class="w-full bg-surface-container-low rounded-t-lg relative flex items-end justify-center h-48 transition-all hover:bg-primary/10">
                        <div
                            class="w-3/4 bg-blue-400/30 rounded-t-md h-[55%] transition-all group-hover:bg-primary-container">
                        </div>
                    </div>
                    <span
                        class="text-xs font-medium text-on-surface-variant">Sun</span>
                </div>
            </div>
        </div>
        <!-- Critical Alerts Panel -->
        <div
            class="col-span-12 xl:col-span-4 bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-tertiary/10">
            <div class="flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-tertiary"
                    data-weight="fill">error</span>
                <h2
                    class="text-xl font-headline font-bold text-on-surface">
                    Critical Alerts</h2>
            </div>
            <div class="space-y-4">
                <!-- Alert Item -->
                <div
                    class="p-4 rounded-xl bg-tertiary-container/10 flex items-center justify-between group cursor-pointer hover:bg-tertiary-container/20 transition-colors">
                    <div class="flex items-center gap-3">
                        <img alt="Patient Avatar"
                            class="w-10 h-10 rounded-full object-cover"
                            data-alt="headshot of an elderly man with kind eyes and graying hair, medical portrait style"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAwFP6s1vE1gmtTG0VjX8ewhg7ZYZb4JQevHGv8gEr1xyP921nbEoYyJhKC59PYnIAH1ih132Oh3PCKXIozwQWM7ro4bfA3fOHFrcn8lgwv1t8i8cSeszM2aLTcHsq6To5EY5adTXGD7Cd9psjwZ4b9NeQ7RDv2oKWKcINRja5eI9jBBHnFOP7oT-EBaM68DswTzFWHTMQUKXMEKSOiE8joaKE1EW0qxDhzs3PvraIBp1U5BeWm5F7qeksotryhaVkSxN8NXkxTnRs" />
                        <div>
                            <p
                                class="text-sm font-bold text-on-surface">
                                James Wilson</p>
                            <p
                                class="text-xs text-tertiary font-medium">
                                168 mg/dL (Critical High)</p>
                        </div>
                    </div>
                    <span
                        class="material-symbols-outlined text-on-surface-variant group-hover:translate-x-1 transition-transform">chevron_right</span>
                </div>
                <div
                    class="p-4 rounded-xl bg-tertiary-container/10 flex items-center justify-between group cursor-pointer hover:bg-tertiary-container/20 transition-colors">
                    <div class="flex items-center gap-3">
                        <img alt="Patient Avatar"
                            class="w-10 h-10 rounded-full object-cover"
                            data-alt="portrait of a middle-aged woman looking professional and serious, clinical lighting"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDyA5hgt7xGO1kh2rxIb96ZMsGZ9yZUQc4cfWTQNT-ZGvo4IOd8ou4eNrWnTiw7PjfMt5Ny4vCu8WttKS3tLE0BShOBlTr3VjTPcDM8dvTfmhTLjFrCWO6Rd99QPTxQQJZGgM2QvQSqdFu9pFyGgH7Ift8OKK8tLzEOyZxRwC0XzlSVj6I1jxKCAy1KHfTtc9jgNFR8J_M5CobrOg9teVxFfCziU-Tfd1IOHgh8p9WLPKnEMXn5aLwbL1NhmoKEYMiCJwasrtwuGj8" />
                        <div>
                            <p
                                class="text-sm font-bold text-on-surface">
                                Maria Rodriguez</p>
                            <p
                                class="text-xs text-tertiary font-medium">
                                152 mg/dL (Rising Trend)</p>
                        </div>
                    </div>
                    <span
                        class="material-symbols-outlined text-on-surface-variant group-hover:translate-x-1 transition-transform">chevron_right</span>
                </div>
                <div
                    class="p-4 rounded-xl bg-tertiary-container/10 flex items-center justify-between group cursor-pointer hover:bg-tertiary-container/20 transition-colors">
                    <div class="flex items-center gap-3">
                        <img alt="Patient Avatar"
                            class="w-10 h-10 rounded-full object-cover"
                            data-alt="portrait of an adult male patient looking calm, neutral medical studio photography"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJci-R9ZR9ri0ivu6CMJ34qmXPATc-ecbFY9EVLiWsnlGgKvsF1dBhQqmL4ySUeu5sh4lcTtdKfqh31k6ZYZOCbs82PCeOfuLP5WkvbNHabZrl-Q4pvNwf1XLGJxYoWOVWk6w36OXUTuCAXWITlyq1yMBrCdzT9JuWjbkybIjHu1RzisM3inzvAodhOem-bN2KHPjJS0EoCCg3n_r9Zk7EBf0TxhzM9ibUUYQCitextvxE8QMfC2SW5VAIFiHlV0vpxwpc7Va-Oto" />
                        <div>
                            <p
                                class="text-sm font-bold text-on-surface">
                                Robert Taylor</p>
                            <p
                                class="text-xs text-tertiary font-medium">
                                54 mg/dL (Critical Low)</p>
                        </div>
                    </div>
                    <span
                        class="material-symbols-outlined text-on-surface-variant group-hover:translate-x-1 transition-transform">chevron_right</span>
                </div>
            </div>
            <button
                class="w-full mt-6 py-3 text-sm font-bold text-tertiary bg-tertiary-container/10 rounded-lg hover:bg-tertiary-container/20 transition-colors">
                View All Alerts (14)
            </button>
        </div>
    </div>
    <!-- Bottom Row: Quick Actions & Recent Activity -->
    <div class="grid grid-cols-12 gap-8">
        <!-- Quick Actions -->
        <div
            class="col-span-12 md:col-span-5 bg-surface-container-low p-8 rounded-xl shadow-sm">
            <h2
                class="text-lg font-headline font-bold text-on-surface mb-6">
                Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <button
                    class="flex flex-col items-center justify-center p-6 bg-surface-container-lowest rounded-xl hover:shadow-md transition-all group scale-95 duration-150 ease-in-out">
                    <span
                        class="material-symbols-outlined text-blue-700 mb-2 scale-125"
                        data-icon="person_add">person_add</span>
                    <span class="text-xs font-bold text-on-surface">Add
                        Patient</span>
                </button>
                <button
                    class="flex flex-col items-center justify-center p-6 bg-surface-container-lowest rounded-xl hover:shadow-md transition-all group scale-95 duration-150 ease-in-out">
                    <span
                        class="material-symbols-outlined text-blue-700 mb-2 scale-125"
                        data-icon="post_add">post_add</span>
                    <span class="text-xs font-bold text-on-surface">New
                        Bulletin</span>
                </button>
                <button
                    class="flex flex-col items-center justify-center p-6 bg-surface-container-lowest rounded-xl hover:shadow-md transition-all group scale-95 duration-150 ease-in-out">
                    <span
                        class="material-symbols-outlined text-blue-700 mb-2 scale-125"
                        data-icon="lab_profile">lab_profile</span>
                    <span
                        class="text-xs font-bold text-on-surface">Bulk
                        Import</span>
                </button>
                <button
                    class="flex flex-col items-center justify-center p-6 bg-surface-container-lowest rounded-xl hover:shadow-md transition-all group scale-95 duration-150 ease-in-out">
                    <span
                        class="material-symbols-outlined text-blue-700 mb-2 scale-125"
                        data-icon="mail">mail</span>
                    <span
                        class="text-xs font-bold text-on-surface">Message
                        All</span>
                </button>
            </div>
        </div>
        <!-- Recent Logs -->
        <div
            class="col-span-12 md:col-span-7 bg-surface-container-lowest p-8 rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2
                    class="text-lg font-headline font-bold text-on-surface">
                    Recent System Logs</h2>
                <a class="text-blue-700 text-xs font-bold hover:underline"
                    href="#">View All Logs</a>
            </div>
            <div class="space-y-0 relative">
                <!-- Custom Timeline Item 1 -->
                <div class="flex gap-4 pb-6 relative">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-3 h-3 rounded-full bg-secondary ring-4 ring-secondary/20">
                        </div>
                        <div
                            class="w-0.5 flex-1 bg-surface-container-high my-1">
                        </div>
                    </div>
                    <div class="flex-1 pb-2">
                        <div class="flex justify-between">
                            <p
                                class="text-sm font-bold text-on-surface">
                                Daily Report Generated</p>
                            <span
                                class="text-[10px] text-on-surface-variant font-medium">10:42
                                AM</span>
                        </div>
                        <p
                            class="text-xs text-on-surface-variant mt-1">
                            Automated aggregate patient summary
                            successfully delivered to 4 recipients.</p>
                    </div>
                </div>
                <!-- Custom Timeline Item 2 -->
                <div class="flex gap-4 pb-6 relative">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-3 h-3 rounded-full bg-primary ring-4 ring-primary/20">
                        </div>
                        <div
                            class="w-0.5 flex-1 bg-surface-container-high my-1">
                        </div>
                    </div>
                    <div class="flex-1 pb-2">
                        <div class="flex justify-between">
                            <p
                                class="text-sm font-bold text-on-surface">
                                New Patient Admitted</p>
                            <span
                                class="text-[10px] text-on-surface-variant font-medium">09:15
                                AM</span>
                        </div>
                        <p
                            class="text-xs text-on-surface-variant mt-1">
                            Sarah Jenkins was added to the monitoring
                            system by Dr. Sarah Chen.</p>
                    </div>
                </div>
                <!-- Custom Timeline Item 3 -->
                <div class="flex gap-4 relative">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-3 h-3 rounded-full bg-tertiary ring-4 ring-tertiary/20">
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <p
                                class="text-sm font-bold text-on-surface">
                                API Sync Error</p>
                            <span
                                class="text-[10px] text-on-surface-variant font-medium">08:30
                                AM</span>
                        </div>
                        <p
                            class="text-xs text-on-surface-variant mt-1">
                            External sensor data sync failed for 12
                            devices. Retrying at 09:00 AM.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
