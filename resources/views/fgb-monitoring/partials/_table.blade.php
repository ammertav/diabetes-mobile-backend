<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-12">
    <!-- Header & Filter Bar -->
    <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
        <h3 class="text-base font-headline font-bold text-slate-900">Recent Readings</h3>
        <div class="flex flex-wrap items-center gap-3">
            <select
                x-model="status"
                @change="loadLogs(1)"
                class="bg-surface-container-low border-none rounded-xl pl-4 pr-10 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
                <option value="all">All Statuses</option>
                <option value="normal">Normal (70-100)</option>
                <option value="elevated">Elevated (100-140)</option>
                <option value="high">High Reading (>140)</option>
                <option value="low">Low/Hypo (<70)</option>
            </select>
            <div class="relative flex-1 min-w-[240px]">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-outline">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input
                    x-model="search"
                    @input.debounce.300ms="loadLogs(1)"
                    class="w-full bg-surface-container-low border-none rounded-xl pl-10 pr-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                    placeholder="Search patients..." type="text" />
            </div>
            @if(request('search') || request('status'))
                <button type="button" @click="search = ''; status = 'all'; loadLogs(1);"
                        class="bg-surface-container-high p-3 rounded-xl text-on-surface-variant hover:bg-surface-variant transition-colors" title="Reset Filters">
                    <span class="material-symbols-outlined">restart_alt</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                    <th class="py-4 px-6">Patient Name</th>
                    <th class="py-4 px-6">Date/Time</th>
                    <th class="py-4 px-6">FGB Value</th>
                    <th class="py-4 px-6">Status</th>
                    <th class="py-4 px-6 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-body">
                <!-- Loading -->
                <template x-if="loading">
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined animate-spin text-2xl mb-1">sync</span>
                            <p class="text-xs font-medium">Loading blood glucose records...</p>
                        </td>
                    </tr>
                </template>

                <!-- Empty -->
                <template x-if="!loading && logs.length === 0">
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-3xl mb-1">bloodtype</span>
                            <p class="text-xs font-medium">No blood glucose records found matching the criteria.</p>
                        </td>
                    </tr>
                </template>

                <!-- Data Rows -->
                <template x-for="log in logs" :key="log.id">
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <img :src="log.patient_photo" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                <div>
                                    <p class="font-bold text-slate-900 text-sm font-headline" x-text="log.patient_name"></p>
                                    <p class="text-xs text-slate-400" x-text="'ID: #' + log.patient_id"></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-xs text-slate-500 font-medium" x-text="log.timestamp"></td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-slate-900 text-sm font-headline" x-text="log.value_mg_dl"></span>
                            <span class="text-[10px] text-slate-400 font-bold ml-1 uppercase">mg/dL</span>
                        </td>
                        <td class="py-4 px-6">
                            <span :class="log.badge_class" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider" x-text="log.status"></span>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button
                                @click="openPatientDetail(log.user_id)"
                                class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 text-xs font-semibold transition-all">
                                Detail
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="p-6 flex items-center justify-between bg-white border-t border-slate-100">
        <p class="text-sm text-slate-500 font-medium" x-show="pagination.total > 0">
            Showing <span class="font-bold text-slate-900" x-text="(pagination.current_page - 1) * 10 + 1"></span> to
            <span class="font-bold text-slate-900" x-text="Math.min(pagination.current_page * 10, pagination.total)"></span> of
            <span class="font-bold text-slate-900" x-text="pagination.total"></span> recent readings
        </p>
        <p class="text-sm text-slate-500 font-medium" x-show="pagination.total === 0">
            Showing 0 recent readings
        </p>
        <div class="flex items-center gap-3" x-show="pagination.last_page > 1">
            <button
                @click="loadLogs(pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors disabled:opacity-50">
                <span class="material-symbols-outlined text-sm">chevron_left</span>
            </button>
            <span class="text-xs font-semibold text-slate-600">
                Page <span x-text="pagination.current_page"></span> of <span x-text="pagination.last_page"></span>
            </span>
            <button
                @click="loadLogs(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="p-2 rounded-lg bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors disabled:opacity-50">
                <span class="material-symbols-outlined text-sm">chevron_right</span>
            </button>
        </div>
    </div>
</div>
