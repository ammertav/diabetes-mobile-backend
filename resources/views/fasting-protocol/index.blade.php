@extends('layouts.app')

@section('content')
    <!-- Content Area -->
    <div class="p-2 w-full mx-auto space-y-8">
        <!-- Header & Action Row -->
        <div class="flex justify-between items-end">
            <div>
                <p class="text-on-surface-variant font-inter text-sm mb-1">Clinical
                    Administration</p>
                <h3
                    class="text-3xl font-extrabold text-blue-900 tracking-tight font-manrope">
                    Management Console</h3>
            </div>
            <button
                onclick="openModal('create-protocol-modal')"
                class="bg-primary from-primary to-primary-container text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-blue-200/40 active:scale-95 transition-all">
                <span class="material-symbols-outlined">add_circle</span>
                Create New Protocol
            </button>
        </div>
        <!-- Dashboard Layout: Asymmetric Bento Grid -->
        <div class="grid grid-cols-12 gap-8">
            <!-- Main Protocols Grid (Left Side) -->
            <div class="col-span-12 lg:col-span-8 space-y-6">
                <!-- Filter Bar -->
                <div
                    class="flex items-center justify-between bg-surface-container-lowest p-4 rounded-2xl shadow-sm">
                    <div class="flex gap-4">
                        <button
                            class="px-4 py-2 bg-primary text-white rounded-full text-sm font-bold">All
                            Protocols</button>
                        <button
                            class="px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-full text-sm font-medium transition-colors">Active</button>
                        <button
                            class="px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-full text-sm font-medium transition-colors">Draft</button>
                        <button
                            class="px-4 py-2 text-on-surface-variant hover:bg-surface-container-high rounded-full text-sm font-medium transition-colors">Archived</button>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="p-2 text-slate-400 hover:text-primary transition-colors"><span
                                class="material-symbols-outlined">grid_view</span></button>
                        <button
                            class="p-2 text-slate-400 hover:text-primary transition-colors"><span
                                class="material-symbols-outlined">format_list_bulleted</span></button>
                    </div>
                </div>
                <!-- Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Protocol Card 1 -->
                    <div
                        class="bg-surface-container-lowest p-8 rounded-full border border-transparent hover:border-primary/10 hover:shadow-xl transition-all group">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                Active</div>
                            <span
                                class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors cursor-pointer">more_vert</span>
                        </div>
                        <h4
                            class="text-xl font-bold text-on-surface mb-2 font-manrope">
                            Intermittent 16:8</h4>
                        <p class="text-on-surface-variant text-sm mb-6 font-inter">
                            Time-Restricted Feeding</p>
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-surface-container-low p-4 rounded-xl">
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    Duration</p>
                                <p class="text-lg font-bold text-blue-800">16 Hours
                                </p>
                            </div>
                            <div class="bg-surface-container-low p-4 rounded-xl">
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    Patients</p>
                                <p class="text-lg font-bold text-blue-800">1,248</p>
                            </div>
                        </div>
                        <button
                            class="w-full py-3 bg-surface-container-high text-primary font-bold text-sm rounded-xl hover:bg-primary hover:text-white transition-all">View
                            Analytics</button>
                    </div>
                    <!-- Protocol Card 2 -->
                    <div
                        class="bg-surface-container-lowest p-8 rounded-full border border-transparent hover:border-primary/10 hover:shadow-xl transition-all group">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                Active</div>
                            <span
                                class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors cursor-pointer">more_vert</span>
                        </div>
                        <h4
                            class="text-xl font-bold text-on-surface mb-2 font-manrope">
                            OMAD - Intensive</h4>
                        <p class="text-on-surface-variant text-sm mb-6 font-inter">
                            24-Hour Cycle</p>
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-surface-container-low p-4 rounded-xl">
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    Duration</p>
                                <p class="text-lg font-bold text-blue-800">23 Hours
                                </p>
                            </div>
                            <div class="bg-surface-container-low p-4 rounded-xl">
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    Patients</p>
                                <p class="text-lg font-bold text-blue-800">412</p>
                            </div>
                        </div>
                        <button
                            class="w-full py-3 bg-surface-container-high text-primary font-bold text-sm rounded-xl hover:bg-primary hover:text-white transition-all">View
                            Analytics</button>
                    </div>
                    <!-- Protocol Card 3 -->
                    <div
                        class="bg-surface-container-lowest p-8 rounded-full border border-transparent hover:border-primary/10 hover:shadow-xl transition-all group">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="bg-surface-container-highest text-on-surface-variant px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                Draft</div>
                            <span
                                class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors cursor-pointer">more_vert</span>
                        </div>
                        <h4
                            class="text-xl font-bold text-on-surface mb-2 font-manrope">
                            Extended Clinical 48</h4>
                        <p class="text-on-surface-variant text-sm mb-6 font-inter">
                            Multi-Day Supervised</p>
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-surface-container-low p-4 rounded-xl">
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    Duration</p>
                                <p class="text-lg font-bold text-blue-800">48 Hours
                                </p>
                            </div>
                            <div class="bg-surface-container-low p-4 rounded-xl">
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    Patients</p>
                                <p class="text-lg font-bold text-blue-800">0</p>
                            </div>
                        </div>
                        <button
                            class="w-full py-3 bg-surface-container-high text-primary font-bold text-sm rounded-xl hover:bg-primary hover:text-white transition-all">Complete
                            Setup</button>
                    </div>
                    <!-- Protocol Card 4 -->
                    <div
                        class="bg-surface-container-lowest p-8 rounded-full border border-transparent hover:border-primary/10 hover:shadow-xl transition-all group">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                Active</div>
                            <span
                                class="material-symbols-outlined text-slate-300 group-hover:text-primary transition-colors cursor-pointer">more_vert</span>
                        </div>
                        <h4
                            class="text-xl font-bold text-on-surface mb-2 font-manrope">
                            Circadian Rhythm</h4>
                        <p class="text-on-surface-variant text-sm mb-6 font-inter">
                            Early-Window Alignment</p>
                        <div class="grid grid-cols-2 gap-4 mb-8">
                            <div class="bg-surface-container-low p-4 rounded-xl">
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    Duration</p>
                                <p class="text-lg font-bold text-blue-800">14 Hours
                                </p>
                            </div>
                            <div class="bg-surface-container-low p-4 rounded-xl">
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                                    Patients</p>
                                <p class="text-lg font-bold text-blue-800">890</p>
                            </div>
                        </div>
                        <button
                            class="w-full py-3 bg-surface-container-high text-primary font-bold text-sm rounded-xl hover:bg-primary hover:text-white transition-all">View
                            Analytics</button>
                    </div>
                </div>
            </div>
            <!-- Preview Section (Right Side) -->
            <div class="col-span-12 lg:col-span-4">
                <div
                    class="bg-surface-container-lowest rounded-full p-8 shadow-2xl shadow-blue-900/5 sticky top-28">
                    <div class="flex items-center justify-between mb-8">
                        <h5 class="text-lg font-bold font-manrope">Protocol Preview
                        </h5>
                        <span
                            class="bg-secondary-fixed text-on-secondary-fixed-variant text-[10px] px-2 py-1 rounded font-bold">LIVE
                            PREVIEW</span>
                    </div>
                    <div class="mb-8">
                        <h4
                            class="text-2xl font-extrabold text-primary font-manrope">
                            Intermittent 16:8</h4>
                        <div class="flex items-center gap-2 mt-2">
                            <span
                                class="material-symbols-outlined text-sm text-secondary">verified</span>
                            <span
                                class="text-xs font-semibold text-secondary font-inter">Clinically
                                Validated</span>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <section>
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Fasting
                                Window</label>
                            <div
                                class="flex items-center justify-between bg-surface-container-low p-4 rounded-xl">
                                <div class="text-center">
                                    <p class="text-xs text-slate-500 mb-1">Start
                                    </p>
                                    <p class="font-bold text-on-surface">20:00</p>
                                </div>
                                <div class="h-8 w-px bg-slate-300"></div>
                                <div class="text-center">
                                    <p class="text-xs text-slate-500 mb-1">End</p>
                                    <p class="font-bold text-on-surface">12:00</p>
                                </div>
                                <div class="h-8 w-px bg-slate-300"></div>
                                <div class="text-center">
                                    <p class="text-xs text-slate-500 mb-1">Total
                                    </p>
                                    <p class="font-bold text-primary">16h</p>
                                </div>
                            </div>
                        </section>
                        <section>
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Intake
                                Guidelines</label>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="material-symbols-outlined text-secondary text-lg">check_circle</span>
                                    <div>
                                        <p
                                            class="text-sm font-bold text-on-surface leading-tight">
                                            Allowed Intake</p>
                                        <p class="text-xs text-on-surface-variant">
                                            Water, black coffee, herbal tea
                                            (unsweetened), electrolytes.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span
                                        class="material-symbols-outlined text-tertiary text-lg">cancel</span>
                                    <div>
                                        <p
                                            class="text-sm font-bold text-on-surface leading-tight">
                                            Prohibited Intake</p>
                                        <p class="text-xs text-on-surface-variant">
                                            Caloric sweeteners, milk, cream, bone
                                            broth, solid foods.</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section>
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Clinical
                                Notes</label>
                            <div
                                class="bg-blue-50/50 p-4 rounded-xl border-l-4 border-primary">
                                <p
                                    class="text-xs text-blue-900 leading-relaxed font-inter italic">
                                    "Ideal for Type-2 diabetic patients
                                    transitioning to metabolic flexibility. Monitor
                                    glucose levels during the final 2 hours of the
                                    window. Adjust for nighttime medication."
                                </p>
                            </div>
                        </section>
                        <div class="pt-4 flex gap-3">
                            <button
                                class="flex-1 py-3 border border-outline-variant rounded-xl text-sm font-bold hover:bg-slate-50 transition-colors">Edit
                                Protocol</button>
                            <button
                                class="p-3 border border-outline-variant rounded-xl text-slate-400 hover:text-error hover:border-error transition-colors"><span
                                    class="material-symbols-outlined">delete</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('fasting-protocol.create-protocol-modal')
@endsection

@push('scripts')
    <script>
        function openModal(id) {
            const modal = document.getElementById(id);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endpush)
