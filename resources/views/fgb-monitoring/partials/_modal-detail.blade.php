<div
    x-show="showDetailModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    style="display: none;"
    @keydown.escape.window="closePatientDetail()"
>
    <!-- Modal container -->
    <div
        class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[85vh] overflow-y-auto p-8 border border-slate-100 flex flex-col gap-6"
        @click.away="closePatientDetail()"
    >
        <!-- Modal Header -->
        <div class="flex justify-between items-start border-b border-slate-100 pb-4">
            <div class="flex items-center gap-4">
                <img :src="detailPatient.photo" class="w-16 h-16 rounded-full object-cover shadow-sm bg-slate-100 border border-slate-200">
                <div>
                    <h3 class="text-xl font-extrabold text-on-surface font-headline" x-text="detailPatient.name"></h3>
                    <p class="text-xs text-on-surface-variant font-medium" x-text="detailPatient.email"></p>
                    <div class="flex gap-2 items-center mt-1.5">
                        <span class="text-[10px] text-on-surface-variant/80 font-bold uppercase tracking-wider" x-text="'ID: ' + detailPatient.id"></span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide bg-blue-50 text-blue-700" x-text="detailPatient.diabetes_status"></span>
                    </div>
                </div>
            </div>
            <button
                @click="closePatientDetail()"
                class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50 transition-all"
            >
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Detail Loading state -->
        <div x-show="detailLoading" class="py-12 flex flex-col items-center justify-center gap-2">
            <span class="material-symbols-outlined animate-spin text-primary text-3xl">sync</span>
            <p class="text-sm text-on-surface-variant font-medium">Loading patient details...</p>
        </div>

        <!-- Detail Content -->
        <div x-show="!detailLoading" class="flex flex-col gap-6">
            <!-- Chart Card -->
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <h4 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider">FGB Trend (Last 10 readings)</h4>
                
                <div class="h-48 w-full relative flex items-end justify-between gap-1 px-4 mt-6">
                    <!-- Y-axis markers -->
                    <div class="absolute left-0 top-0 bottom-0 flex flex-col justify-between text-[9px] font-bold text-on-surface-variant/30 pt-1 pb-6">
                        <span>250</span>
                        <span>150</span>
                        <span>70</span>
                    </div>
                    <div class="w-8"></div>
                    <template x-for="item in detailChartData" :key="item.label + '-' + item.value">
                        <div class="flex-1 h-full flex items-end relative group px-1">
                            <!-- Left border separator for day transition or first item -->
                            <div x-show="item.is_first || item.show_separator" :class="item.show_separator ? 'border-l border-dashed border-slate-300' : ''" class="absolute -left-1 top-0 bottom-0 z-10 flex flex-col justify-start">
                                <span class="text-[8px] text-slate-500 font-bold bg-slate-100 px-1 py-0.5 rounded -translate-x-1/2 -mt-6 shadow-sm border border-slate-200" x-text="item.date_label"></span>
                            </div>

                            <!-- The actual bar -->
                            <div
                                :class="item.bar_class || 'bg-secondary/20 hover:bg-secondary/40'"
                                class="w-full rounded-t-md relative transition-all"
                                :style="'height: ' + Math.min(Math.max((item.value / 250) * 100, 10), 100) + '%'">
                                <div
                                    class="absolute -top-8 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[9px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10"
                                    x-text="Math.round(item.value)"></div>
                                <div class="absolute -bottom-5 left-1/2 -translate-x-1/2 text-[9px] text-on-surface-variant/70 font-semibold" x-text="item.label"></div>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="h-6"></div>
            </div>

            <!-- Readings Table inside Modal -->
            <div>
                <h4 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-3">Reading History</h4>
                <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-on-surface-variant text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                                <th class="px-6 py-4">Date/Time</th>
                                <th class="px-6 py-4">FGB Value</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <template x-for="rec in detailRecords" :key="rec.id">
                                <tr>
                                    <td class="px-6 py-4 text-on-surface-variant font-medium" x-text="rec.timestamp"></td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-on-surface" x-text="rec.value"></span>
                                        <span class="text-[10px] text-on-surface-variant uppercase ml-0.5">mg/dL</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span :class="rec.badge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wide" x-text="rec.status"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
