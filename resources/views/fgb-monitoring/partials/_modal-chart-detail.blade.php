<div
    x-show="showChartDetailModal"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    style="display: none;"
    @keydown.escape.window="closeChartDetail()"
>
    <!-- Modal container -->
    <div
        class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full max-h-[85vh] overflow-y-auto p-8 border border-slate-100 flex flex-col gap-6"
        @click.away="closeChartDetail()"
    >
        <!-- Modal Header -->
        <div class="flex justify-between items-start border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-xl font-extrabold text-on-surface font-headline">FGB Trend Details</h3>
                <p class="text-xs text-on-surface-variant font-medium mt-1" x-text="selectedChartTitle"></p>
            </div>
            <button
                @click="closeChartDetail()"
                class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-50 transition-all"
            >
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Detail Loading state -->
        <div x-show="chartDetailLoading" class="py-12 flex flex-col items-center justify-center gap-2">
            <span class="material-symbols-outlined animate-spin text-primary text-3xl">sync</span>
            <p class="text-sm text-on-surface-variant font-medium">Loading period details...</p>
        </div>

        <!-- Detail Content -->
        <div x-show="!chartDetailLoading" class="flex flex-col gap-6">
            <!-- Stats overview cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-50/50 rounded-2xl p-5 border border-blue-100/30 flex items-center gap-4">
                    <div class="p-3 bg-blue-100 text-blue-700 rounded-xl">
                        <span class="material-symbols-outlined text-2xl">monitoring</span>
                    </div>
                    <div>
                        <p class="text-xs text-blue-600 font-bold uppercase tracking-wider">Average FGB</p>
                        <p class="text-2xl font-black text-blue-900 mt-1">
                            <span x-text="selectedChartAvg"></span>
                            <span class="text-xs font-semibold text-blue-700">mg/dL</span>
                        </p>
                    </div>
                </div>
                <div class="bg-emerald-50/50 rounded-2xl p-5 border border-emerald-100/30 flex items-center gap-4">
                    <div class="p-3 bg-emerald-100 text-emerald-700 rounded-xl">
                        <span class="material-symbols-outlined text-2xl">database</span>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-600 font-bold uppercase tracking-wider">Total Readings</p>
                        <p class="text-2xl font-black text-emerald-900 mt-1">
                            <span x-text="selectedChartCount"></span>
                            <span class="text-xs font-semibold text-emerald-700">records</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Readings Table inside Modal -->
            <div>
                <h4 class="text-sm font-bold text-on-surface-variant uppercase tracking-wider mb-3">Readings Log</h4>
                <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 text-on-surface-variant text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                                <th class="px-6 py-4">Patient</th>
                                <th class="px-6 py-4">FGB Value</th>
                                <th class="px-6 py-4">Context</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Date/Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <template x-if="chartDetailRecords.length === 0">
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-on-surface-variant">
                                        No records found for this period.
                                    </td>
                                </tr>
                            </template>
                            <template x-for="rec in chartDetailRecords" :key="rec.id">
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <img :src="rec.patient_photo" class="w-8 h-8 rounded-full object-cover shadow-sm bg-slate-100 border border-slate-200">
                                            <div>
                                                <p class="font-bold text-xs text-on-surface" x-text="rec.patient_name"></p>
                                                <p class="text-[9px] text-on-surface-variant font-medium" x-text="rec.patient_id"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-on-surface" x-text="rec.value_mg_dl"></span>
                                        <span class="text-[10px] text-on-surface-variant uppercase ml-0.5">mg/dL</span>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-semibold text-slate-500 capitalize" x-text="rec.context_tag || '-'"></td>
                                    <td class="px-6 py-4">
                                        <span :class="rec.badge_class" class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold tracking-wide uppercase" x-text="rec.status"></span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-on-surface-variant font-medium" x-text="rec.timestamp"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
