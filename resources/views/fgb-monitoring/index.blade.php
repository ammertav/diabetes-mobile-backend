@extends('layouts.app')
@section('content')
    <!-- Page Header -->
    <div
        class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div
                class="flex items-center gap-2 text-primary font-semibold text-sm mb-2">
                <span
                    class="material-symbols-outlined text-sm">calendar_today</span>
                <span>Monday, Oct 23, 2023</span>
            </div>
            <h1
                class="text-4xl font-extrabold text-on-surface tracking-tight mb-2">
                FGB Monitoring</h1>
            <p class="text-on-surface-variant max-w-lg">Advanced fasting blood
                glucose surveillance across the Endocrinology Department
                population.</p>
        </div>
        <div class="flex gap-3">
            <button
                class="px-5 py-2.5 bg-surface-container-high text-on-surface font-semibold rounded-xl text-sm transition-all hover:bg-surface-container-highest flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">download</span>
                Export logs
            </button>
            <button
                class="px-5 py-2.5 bg-primary text-white font-semibold rounded-xl text-sm transition-all hover:opacity-90 flex items-center gap-2 shadow-lg shadow-blue-500/20">
                <span class="material-symbols-outlined text-lg">add</span>
                Manual Log
            </button>
        </div>
    </div>
    <!-- Bento Grid Summary Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Metric 1: Avg FGB -->
        <div
            class="bg-surface-container-lowest p-8 rounded-xl shadow-sm flex flex-col justify-between h-48">
            <div class="flex justify-between items-start">
                <span
                    class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Weekly
                    Avg FGB</span>
                <div class="p-2 bg-primary/10 text-primary rounded-lg">
                    <span class="material-symbols-outlined text-xl">speed</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-5xl font-black text-on-surface tracking-tighter">104</span>
                    <span
                        class="text-on-surface-variant font-medium">mg/dL</span>
                </div>
                <p
                    class="text-secondary text-xs font-bold mt-2 flex items-center gap-1">
                    <span
                        class="material-symbols-outlined text-sm">trending_down</span>
                    -4.2% from last week
                </p>
            </div>
        </div>
        <!-- Metric 2: Target Range -->
        <div
            class="bg-surface-container-lowest p-8 rounded-xl shadow-sm flex flex-col justify-between h-48 relative overflow-hidden">
            <div class="flex justify-between items-start z-10">
                <span
                    class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">In
                    Target Range</span>
                <div class="p-2 bg-secondary/10 text-secondary rounded-lg">
                    <span
                        class="material-symbols-outlined text-xl">task_alt</span>
                </div>
            </div>
            <div class="mt-4 z-10">
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-5xl font-black text-on-surface tracking-tighter">78.4</span>
                    <span class="text-on-surface-variant font-medium">%</span>
                </div>
                <div
                    class="w-full bg-surface-container-high h-1.5 rounded-full mt-4 overflow-hidden">
                    <div class="h-full signature-gradient w-[78.4%]"></div>
                </div>
            </div>
            <!-- Subtle background abstract -->
            <div class="absolute -right-4 -bottom-4 opacity-5">
                <span
                    class="material-symbols-outlined text-9xl">analytics</span>
            </div>
        </div>
        <!-- Metric 3: Abnormal Readings -->
        <div
            class="bg-surface-container-lowest p-8 rounded-xl shadow-sm flex flex-col justify-between h-48 border border-tertiary/10">
            <div class="flex justify-between items-start">
                <span
                    class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Abnormal
                    Events</span>
                <div class="p-2 bg-tertiary/10 text-tertiary rounded-lg">
                    <span
                        class="material-symbols-outlined text-xl">warning</span>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-baseline gap-2">
                    <span
                        class="text-5xl font-black text-tertiary tracking-tighter">12</span>
                    <span
                        class="text-on-surface-variant font-medium">alerts</span>
                </div>
                <p class="text-on-surface-variant text-xs font-medium mt-2">
                    Requires immediate clinical review</p>
            </div>
        </div>
    </div>
    <!-- Main Interactive Section: Chart & Filters -->
    <div
        class="bg-surface-container-lowest rounded-xl shadow-sm mb-8 overflow-hidden">
        <div class="p-8 border-b border-surface-container">
            <div
                class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <h2 class="text-xl font-bold tracking-tight">FGB Trend Analysis
                </h2>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex bg-surface-container-low p-1 rounded-lg">
                        <button
                            class="px-4 py-1.5 text-xs font-bold bg-white shadow-sm rounded-md">Daily</button>
                        <button
                            class="px-4 py-1.5 text-xs font-bold text-on-surface-variant">Weekly</button>
                        <button
                            class="px-4 py-1.5 text-xs font-bold text-on-surface-variant">Monthly</button>
                    </div>
                    <select
                        class="bg-surface-container-high border-none rounded-xl text-sm font-semibold px-4 py-2 pr-10 focus:ring-primary/20">
                        <option>Group: Type 2 Diabetes</option>
                        <option>Group: Gestational</option>
                        <option>Group: High Risk</option>
                    </select>
                    <button class="p-2 bg-surface-container-high rounded-xl">
                        <span
                            class="material-symbols-outlined text-on-surface">tune</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="p-8">
            <!-- Placeholder for Chart -->
            <div
                class="h-72 w-full relative flex items-end justify-between gap-2 px-4">
                <!-- Y-axis markers -->
                <div
                    class="absolute left-0 top-0 bottom-0 flex flex-col justify-between text-[10px] font-bold text-on-surface-variant/40 pt-2 pb-8">
                    <span>140</span>
                    <span>120</span>
                    <span>100</span>
                    <span>80</span>
                    <span>60</span>
                </div>
                <!-- Simulated Chart Bars -->
                <div
                    class="flex-1 h-32 bg-primary/20 rounded-t-lg relative group transition-all hover:bg-primary/40">
                    <div
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        112</div>
                </div>
                <div
                    class="flex-1 h-48 bg-primary/20 rounded-t-lg relative group transition-all hover:bg-primary/40">
                    <div
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        124</div>
                </div>
                <div
                    class="flex-1 h-40 bg-primary/20 rounded-t-lg relative group transition-all hover:bg-primary/40">
                    <div
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        118</div>
                </div>
                <div
                    class="flex-1 h-24 bg-primary rounded-t-lg relative group transition-all shadow-[0_0_20px_rgba(37,99,235,0.3)]">
                    <div
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-2 py-1 rounded opacity-100">
                        104</div>
                </div>
                <div
                    class="flex-1 h-36 bg-primary/20 rounded-t-lg relative group transition-all hover:bg-primary/40">
                    <div
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        115</div>
                </div>
                <div
                    class="flex-1 h-52 bg-primary/20 rounded-t-lg relative group transition-all hover:bg-primary/40">
                    <div
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        130</div>
                </div>
                <div
                    class="flex-1 h-44 bg-primary/20 rounded-t-lg relative group transition-all hover:bg-primary/40">
                    <div
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        121</div>
                </div>
            </div>
            <!-- X-axis -->
            <div
                class="flex justify-between mt-4 px-10 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">
                <span>Mon</span>
                <span>Tue</span>
                <span>Wed</span>
                <span>Thu (Today)</span>
                <span>Fri</span>
                <span>Sat</span>
                <span>Sun</span>
            </div>
        </div>
    </div>
    <!-- FGB Log Table -->
    <div
        class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden mb-12">
        <div
            class="p-8 flex items-center justify-between border-b border-surface-container">
            <h3 class="text-lg font-bold">Recent Readings</h3>
            <div class="relative">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input
                    class="pl-10 pr-4 py-2 bg-surface-container-low border-none rounded-xl text-sm w-64 focus:ring-primary/20"
                    placeholder="Search patients..." type="text" />
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="text-on-surface-variant text-[11px] font-bold uppercase tracking-widest border-b border-surface-container">
                        <th class="px-8 py-5">Patient Name</th>
                        <th class="px-8 py-5">Date/Time</th>
                        <th class="px-8 py-5">FGB Value</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    <tr
                        class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-slate-100 overflow-hidden">
                                    <img alt="Patient Sarah"
                                        class="w-full h-full object-cover"
                                        data-alt="Portrait of an older adult woman with a kind expression in soft daylight"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBADo94BdeSvQ0Hv9mWnCNGw4IBA1VKJORR-5TecdjnoTn99gGkTGU74aOL7RFM8bHxLiBEaNMcZoF0BxA53uqb9oy6L2-vPvu0M8y68VU-E-jU1JCuaV8husVOafyziCkqX7u3W2mKC8nzl2P6VdCRAMAZ8rMwesQSB0U3IhuqLOSnNxa1oB8aGF15LkY12cLMkUgx3gFuhZP05nfao7o5oov5ivSpjIXYyqiIeUyr4Q29mQ67cWqxhDMfeZEz3X84qv99aCEEI44" />
                                </div>
                                <div>
                                    <p
                                        class="font-bold text-sm text-on-surface">
                                        Sarah J. Miller</p>
                                    <p
                                        class="text-[10px] text-on-surface-variant font-medium">
                                        ID: #PX-9923</p>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-8 py-5 text-sm text-on-surface-variant font-medium">
                            Oct 23, 07:15 AM</td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-on-surface">94</span>
                            <span
                                class="text-[10px] text-on-surface-variant ml-1 uppercase">mg/dL</span>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-secondary-container text-on-secondary-container uppercase tracking-wide">
                                Normal
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a class="text-primary font-bold text-xs hover:underline flex items-center justify-end gap-1"
                                href="#">
                                View Profile
                                <span
                                    class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </td>
                    </tr>
                    <tr
                        class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-slate-100 overflow-hidden">
                                    <img alt="Patient David"
                                        class="w-full h-full object-cover"
                                        data-alt="Close-up portrait of a middle-aged man with focused expression and professional attire"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuARspmXw6Jej6BPFpk-Bp7jR3WUmZf4-K_RdJ2KGQt8kC5yWuOprx3Pp_S29drMWRR5G5AwkumevKe4eyDLmhC0oLZue4ENiDfWebzaiD0DCrYkD2lRHR8cjxpWn1swVpYyKTa_SZlt0F50DYtoZwQPEQ4dcVDQ8GRiFaaijYtXFVwiTQKHGGe5Svg0TRdpeoralV-IbpVMYBXo9CqyiJUG0MM9SJgTIb_JUFPjcIMHPzqhE2zT9WossmyffRqJWWlTtq-P-sWtfFM" />
                                </div>
                                <div>
                                    <p
                                        class="font-bold text-sm text-on-surface">
                                        David Chen</p>
                                    <p
                                        class="text-[10px] text-on-surface-variant font-medium">
                                        ID: #PX-8104</p>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-8 py-5 text-sm text-on-surface-variant font-medium">
                            Oct 23, 06:45 AM</td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-tertiary">142</span>
                            <span
                                class="text-[10px] text-on-surface-variant ml-1 uppercase">mg/dL</span>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-tertiary-container text-on-tertiary-container uppercase tracking-wide">
                                High Reading
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a class="text-primary font-bold text-xs hover:underline flex items-center justify-end gap-1"
                                href="#">
                                View Profile
                                <span
                                    class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </td>
                    </tr>
                    <tr
                        class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-slate-100 overflow-hidden">
                                    <img alt="Patient Elena"
                                        class="w-full h-full object-cover"
                                        data-alt="Modern high-resolution portrait of a woman in a clean, professional medical setting environment"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC1YSUPdFxSdBLhx5B1jZ6cSdkdSRrXCo0NbfVL39yD4ni4Q72_bRwg5D5CoIRXigM38gKACiSl7gFYCKHgXLU7YlSz4u8uRswkeAzdj5dCvcLdIyugP9G-Ku5prWS_cZJwvMrxjS18zTpWXdgfNSrNURR8CSrRZBki7kkMgn39UzFELZDoxI9OYqQnLiiXjWx7rAB-8rs5T54ASMu0KJK4nRf75PHAPNL7QXIx3Xt7kftCMk1VT0SasCzWxdPEKm8q5fCGu0zfSkY" />
                                </div>
                                <div>
                                    <p
                                        class="font-bold text-sm text-on-surface">
                                        Elena Rodriguez</p>
                                    <p
                                        class="text-[10px] text-on-surface-variant font-medium">
                                        ID: #PX-7756</p>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-8 py-5 text-sm text-on-surface-variant font-medium">
                            Oct 23, 08:02 AM</td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-on-surface">128</span>
                            <span
                                class="text-[10px] text-on-surface-variant ml-1 uppercase">mg/dL</span>
                        </td>
                        <td class="px-8 py-5">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700 uppercase tracking-wide">
                                Elevated
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a class="text-primary font-bold text-xs hover:underline flex items-center justify-end gap-1"
                                href="#">
                                View Profile
                                <span
                                    class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div
            class="p-6 bg-surface-container-low/50 flex items-center justify-between">
            <p class="text-xs text-on-surface-variant font-medium">Showing 3 of
                152 recent readings</p>
            <div class="flex gap-2">
                <button
                    class="p-2 bg-white rounded-lg border border-surface-container text-on-surface-variant hover:bg-slate-50 transition-colors">
                    <span
                        class="material-symbols-outlined text-lg">chevron_left</span>
                </button>
                <button
                    class="p-2 bg-white rounded-lg border border-surface-container text-on-surface-variant hover:bg-slate-50 transition-colors">
                    <span
                        class="material-symbols-outlined text-lg">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endsection
