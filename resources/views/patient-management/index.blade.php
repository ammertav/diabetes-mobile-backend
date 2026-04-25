@extends('layouts.app')
@section('content')
    <header
        class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1
                class="font-headline font-extrabold text-4xl text-on-surface tracking-tight mb-2">
                Patient Management</h1>
            <p class="text-on-surface-variant font-medium">Monitoring 1,284
                patients across the Diabetes Division</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="bg-surface-container-highest px-5 py-2.5 rounded-xl font-bold text-sm text-on-surface-variant flex items-center gap-2 hover:bg-surface-container-high transition-colors">
                <span
                    class="material-symbols-outlined text-xl">file_download</span>
                Export Registry
            </button>
            <button
                class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-primary/20 hover:shadow-xl transition-all scale-98-active">
                <span class="material-symbols-outlined text-xl">person_add</span>
                Add New Patient
            </button>
        </div>
    </header>
    <!-- Summary Cards Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div
            class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-primary-fixed rounded-full">
                    <span
                        class="material-symbols-outlined text-primary text-2xl"
                        style="font-variation-settings: 'FILL' 1;">group</span>
                </div>
                <span
                    class="text-secondary font-bold text-xs bg-secondary-container px-2 py-1 rounded-full">+12.5%</span>
            </div>
            <div>
                <p
                    class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                    Total Patients</p>
                <h3 class="font-headline font-bold text-4xl text-on-surface">
                    1,284</h3>
            </div>
        </div>
        <div
            class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-secondary-container rounded-full">
                    <span
                        class="material-symbols-outlined text-secondary text-2xl"
                        style="font-variation-settings: 'FILL' 1;">timer</span>
                </div>
                <span
                    class="text-on-surface-variant font-bold text-xs bg-surface-container-high px-2 py-1 rounded-full">Stable</span>
            </div>
            <div>
                <p
                    class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                    Fasting Protocol</p>
                <h3 class="font-headline font-bold text-4xl text-on-surface">412
                </h3>
            </div>
        </div>
        <div
            class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
            <div class="flex items-center justify-between">
                <div class="p-3 bg-tertiary-container/10 rounded-full">
                    <span
                        class="material-symbols-outlined text-tertiary text-2xl"
                        style="font-variation-settings: 'FILL' 1;">warning</span>
                </div>
                <span
                    class="text-tertiary font-bold text-xs bg-tertiary-container/20 px-2 py-1 rounded-full">Critical</span>
            </div>
            <div>
                <p
                    class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                    High Risk Patients</p>
                <h3 class="font-headline font-bold text-4xl text-tertiary">28
                </h3>
            </div>
        </div>
    </div>
    <!-- Content Area: Registry Table -->
    <div
        class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
        <!-- Filter Bar -->
        <div
            class="p-6 bg-surface-container-lowest border-b border-surface-container flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4 flex-1 min-w-[300px]">
                <div class="relative flex-1">
                    <input
                        class="w-full bg-surface-container-low border-none rounded-xl px-10 py-3 text-sm focus:ring-2 focus:ring-primary/20"
                        placeholder="Search by name, ID or protocol..."
                        type="text" />
                    <span
                        class="material-symbols-outlined absolute left-3 top-3 text-outline">search</span>
                </div>
                <button
                    class="bg-surface-container-high p-3 rounded-xl text-on-surface-variant hover:bg-surface-variant transition-colors">
                    <span class="material-symbols-outlined">tune</span>
                </button>
            </div>
            <div class="flex items-center gap-3">
                <select
                    class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20">
                    <option>All Risk Levels</option>
                    <option>High Risk</option>
                    <option>Medium Risk</option>
                    <option>Low Risk</option>
                </select>
                <select
                    class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20">
                    <option>All Protocols</option>
                    <option>Fasting</option>
                    <option>Intensive Insulin</option>
                    <option>Maintenance</option>
                </select>
                <input
                    class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20"
                    type="date" />
            </div>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th
                            class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                            Patient Name</th>
                        <th
                            class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                            ID</th>
                        <th
                            class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                            Diabetes Type</th>
                        <th
                            class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                            Protocol</th>
                        <th
                            class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase text-center">
                            Risk Status</th>
                        <th
                            class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                            Last Check-in</th>
                        <th
                            class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    <!-- Patient Row 1 -->
                    <tr
                        class="hover:bg-surface-container-low/50 transition-colors cursor-pointer group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <img alt="Patient Sarah Miller"
                                    class="w-10 h-10 rounded-full object-cover"
                                    data-alt="Soft-lit professional studio portrait of a woman in her 40s looking at the camera"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuC812RwVhtcZj8720Y4tMFkAY2nggJnY1aoGeeLBuCSwPOQcR41IS0VG0iHGHPdLbkFAGEfGyXFyqPy2WD1-7KvqFLHxMy9snBHeNYr5UCgO4OEi9llLDE4_aoNLgbqMDyK1iwmmnS137nae-9DUl9rTUM2pbFM5oinNUXj_W3AJYR57Ev2_8pOZykkKDPSWS7tMw28S4xa5xsNWr9sZv7Fj7qHWxS9z8geGk0yYK1EWRDUK2QXFDXQXG38Fo9xCuYhLzzJ_0kO7c8" />
                                <div>
                                    <p
                                        class="font-bold text-on-surface text-sm">
                                        Sarah Miller</p>
                                    <p class="text-on-surface-variant text-xs">
                                        miller.s@email.com</p>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-6 py-5 font-medium text-sm text-on-surface-variant">
                            #DB-9421</td>
                        <td class="px-6 py-5">
                            <span
                                class="text-xs font-bold px-2 py-1 bg-primary-fixed text-on-primary-fixed rounded-lg">Type
                                1</span>
                        </td>
                        <td
                            class="px-6 py-5 text-sm font-medium text-on-surface">
                            Intensive Insulin</td>
                        <td class="px-6 py-5 text-center">
                            <span
                                class="px-3 py-1 bg-tertiary-container text-white text-[10px] font-bold rounded-full uppercase tracking-wider">High
                                Risk</span>
                        </td>
                        <td class="px-6 py-5 text-sm text-on-surface-variant">
                            Today, 09:12 AM</td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button
                                    class="p-2 hover:bg-surface-container-high rounded-lg text-primary transition-colors">
                                    <span
                                        class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                                <button
                                    class="p-2 hover:bg-surface-container-high rounded-lg text-outline transition-colors">
                                    <span
                                        class="material-symbols-outlined text-xl">edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Patient Row 2 -->
                    <tr
                        class="hover:bg-surface-container-low/50 transition-colors cursor-pointer group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <img alt="Patient David Chen"
                                    class="w-10 h-10 rounded-full object-cover"
                                    data-alt="Confident man with dark hair and glasses in a casual office setting with blurred background"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAsoHtJ7hdq2P22-q063wLjuQkqPmydTStZou10wRPqyHyml8UpvjZOapZ-AfShd_vBhgB0rH1gYshVkwmWskdEVF-vh9ESekQDzJ4RNSJMdc125H6R5qCl1B50H1TCpIJzKxAO7vuAXzXzkY0BTBf-H4aFPT9VsnVpuT_0IYk1O5ffVUAx7IhqhRFzxjy6sme0bGfmDJesTMXS0YZm5DAaD202GLr5BZoMVT1pvHvrN7MOn6TudR-F7NRds3UW_GPve1U611f-REc" />
                                <div>
                                    <p
                                        class="font-bold text-on-surface text-sm">
                                        David Chen</p>
                                    <p class="text-on-surface-variant text-xs">
                                        d.chen@corporate.com</p>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-6 py-5 font-medium text-sm text-on-surface-variant">
                            #DB-8812</td>
                        <td class="px-6 py-5">
                            <span
                                class="text-xs font-bold px-2 py-1 bg-secondary-container text-on-secondary-container rounded-lg">Type
                                2</span>
                        </td>
                        <td
                            class="px-6 py-5 text-sm font-medium text-on-surface">
                            Fasting Protocol</td>
                        <td class="px-6 py-5 text-center">
                            <span
                                class="px-3 py-1 bg-secondary-fixed-dim text-on-secondary-fixed-variant text-[10px] font-bold rounded-full uppercase tracking-wider">Low
                                Risk</span>
                        </td>
                        <td class="px-6 py-5 text-sm text-on-surface-variant">
                            Yesterday, 04:30 PM</td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button
                                    class="p-2 hover:bg-surface-container-high rounded-lg text-primary transition-colors">
                                    <span
                                        class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                                <button
                                    class="p-2 hover:bg-surface-container-high rounded-lg text-outline transition-colors">
                                    <span
                                        class="material-symbols-outlined text-xl">edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Patient Row 3 -->
                    <tr
                        class="hover:bg-surface-container-low/50 transition-colors cursor-pointer group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <img alt="Patient Elena Rodriguez"
                                    class="w-10 h-10 rounded-full object-cover"
                                    data-alt="Vibrant portrait of a woman smiling with warm, sun-drenched lighting and colorful background"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfbwQphrcYsyazbRYpExGbMp4T2Qyh9DfeOB4wK5ycWg8KrtoAj0KoNOMNnsMJnq9Ad6uhTgA_Fc583RrqwA16G4UfRrMuy4TN8Mf-E9ZSJVFEd3myBd0mdqbdiAkhdUY2QcN93fmCZkG0uVz76381KfC0NrOREc_27LxN0ocXKLgKqvq2VpFRto-LpTg90K0XtaqCEK9Wbv-Gurl7n7G1LaY2SrIPtSMKqrWP2TLHxb_bUDNNxpEryDl6VitkH2_TaEGLVRJd1mU" />
                                <div>
                                    <p
                                        class="font-bold text-on-surface text-sm">
                                        Elena Rodriguez</p>
                                    <p class="text-on-surface-variant text-xs">
                                        elena.rod@health.org</p>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-6 py-5 font-medium text-sm text-on-surface-variant">
                            #DB-7761</td>
                        <td class="px-6 py-5">
                            <span
                                class="text-xs font-bold px-2 py-1 bg-secondary-container text-on-secondary-container rounded-lg">Type
                                2</span>
                        </td>
                        <td
                            class="px-6 py-5 text-sm font-medium text-on-surface">
                            Maintenance</td>
                        <td class="px-6 py-5 text-center">
                            <span
                                class="px-3 py-1 bg-primary-fixed text-primary text-[10px] font-bold rounded-full uppercase tracking-wider">Medium
                                Risk</span>
                        </td>
                        <td class="px-6 py-5 text-sm text-on-surface-variant">
                            Mar 12, 2024</td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button
                                    class="p-2 hover:bg-surface-container-high rounded-lg text-primary transition-colors">
                                    <span
                                        class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                                <button
                                    class="p-2 hover:bg-surface-container-high rounded-lg text-outline transition-colors">
                                    <span
                                        class="material-symbols-outlined text-xl">edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <!-- Patient Row 4 -->
                    <tr
                        class="hover:bg-surface-container-low/50 transition-colors cursor-pointer group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <img alt="Patient Marcus Thompson"
                                    class="w-10 h-10 rounded-full object-cover"
                                    data-alt="Middle-aged man with a graying beard looking thoughtful, studio lighting with dark backdrop"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBH_Kojer3qHbb8C4ww5Fb8r_1Wve80T4CGs54Er0xLNdxbrod36037M8E5SkpCzgVNwNlhSulhv6azfOvpPzlVmSrLCNjF6XAvXHdx8JjiTcKEQ9XdABdO0dvY5AfAm2RGTbNUXEOgaVcCKeGLzdzU7dFv8tNgi7N5A9n5NqzIyu2lUGoaBvRPXqZFC1ioPpI3mydCKzV633QYa6iaodVaU5-kt_6RsSFDgMs6R2AEB_qvMTk-lGNGDJmMlqRUgwHUNnrHeJVcLpw" />
                                <div>
                                    <p
                                        class="font-bold text-on-surface text-sm">
                                        Marcus Thompson</p>
                                    <p class="text-on-surface-variant text-xs">
                                        m.thompson@domain.com</p>
                                </div>
                            </div>
                        </td>
                        <td
                            class="px-6 py-5 font-medium text-sm text-on-surface-variant">
                            #DB-6234</td>
                        <td class="px-6 py-5">
                            <span
                                class="text-xs font-bold px-2 py-1 bg-primary-fixed text-on-primary-fixed rounded-lg">Type
                                1</span>
                        </td>
                        <td
                            class="px-6 py-5 text-sm font-medium text-on-surface">
                            Fasting Protocol</td>
                        <td class="px-6 py-5 text-center">
                            <span
                                class="px-3 py-1 bg-tertiary-container text-white text-[10px] font-bold rounded-full uppercase tracking-wider">High
                                Risk</span>
                        </td>
                        <td class="px-6 py-5 text-sm text-on-surface-variant">
                            Mar 11, 2024</td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button
                                    class="p-2 hover:bg-surface-container-high rounded-lg text-primary transition-colors">
                                    <span
                                        class="material-symbols-outlined text-xl">visibility</span>
                                </button>
                                <button
                                    class="p-2 hover:bg-surface-container-high rounded-lg text-outline transition-colors">
                                    <span
                                        class="material-symbols-outlined text-xl">edit</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div
            class="p-6 flex items-center justify-between bg-surface-container-lowest">
            <p class="text-sm text-on-surface-variant">Showing <span
                    class="font-bold">1-10</span> of <span
                    class="font-bold">1,284</span> patients</p>
            <div class="flex items-center gap-2">
                <button
                    class="p-2 rounded-lg bg-surface-container-high text-on-surface-variant hover:bg-surface-variant transition-colors disabled:opacity-50"
                    disabled="">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button
                    class="px-4 py-2 rounded-lg bg-primary text-white font-bold text-sm">1</button>
                <button
                    class="px-4 py-2 rounded-lg hover:bg-surface-container-high text-on-surface font-medium text-sm transition-colors">2</button>
                <button
                    class="px-4 py-2 rounded-lg hover:bg-surface-container-high text-on-surface font-medium text-sm transition-colors">3</button>
                <span class="text-on-surface-variant">...</span>
                <button
                    class="px-4 py-2 rounded-lg hover:bg-surface-container-high text-on-surface font-medium text-sm transition-colors">129</button>
                <button
                    class="p-2 rounded-lg bg-surface-container-high text-on-surface-variant hover:bg-surface-variant transition-colors">
                    <span
                        class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
@endsection
