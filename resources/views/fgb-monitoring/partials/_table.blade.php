<div
    class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden mb-12">
    <div
        class="p-8 flex flex-col md:flex-row md:items-center justify-between border-b border-surface-container gap-4">
        <h3 class="text-lg font-bold">Recent Readings</h3>
        <div class="flex flex-wrap items-center gap-3">
            <select
                x-model="status"
                @change="loadLogs(1)"
                class="bg-surface-container-low border-none rounded-xl text-sm font-semibold px-4 py-2.5 focus:ring-primary/20 text-on-surface-variant">
                <option value="all">All Statuses</option>
                <option value="normal">Normal (70-100)</option>
                <option value="elevated">Elevated (100-140)</option>
                <option value="high">High Reading (>140)</option>
                <option value="low">Low/Hypo (<70)</option>
            </select>
            <div class="relative">
                <div
                    class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-on-surface-variant">
                    <span class="material-symbols-outlined">search</span>
                </div>
                <input
                    x-model="search"
                    @input.debounce.300ms="loadLogs(1)"
                    class="pl-10 pr-4 py-2.5 bg-surface-container-low border-none rounded-xl text-sm w-64 focus:ring-primary/20"
                    placeholder="Search patients..." type="text" />
            </div>
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
                {{-- Loading --}}
                <template x-if="loading">
                    <tr>
                        <td colspan="5" class="p-10 text-center text-on-surface-variant font-medium">
                            <div class="flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined animate-spin text-primary">sync</span>
                                Loading blood glucose records...
                            </div>
                        </td>
                    </tr>
                </template>

                {{-- Empty State --}}
                <template x-if="!loading && logs.length === 0">
                    <tr>
                        <td colspan="5" class="p-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-outline mb-2">bloodtype</span>
                            <p class="font-medium text-sm">No blood glucose records found matching the criteria.</p>
                        </td>
                    </tr>
                </template>

                {{-- Data --}}
                <template x-for="log in logs" :key="log.id">
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <img :src="log.patient_photo" class="w-9 h-9 rounded-full object-cover shadow-sm bg-surface-container-high border border-surface-variant/30">
                                <div>
                                    <p class="font-bold text-sm text-on-surface" x-text="log.patient_name"></p>
                                    <p class="text-[10px] text-on-surface-variant font-medium" x-text="'ID: #' + log.patient_id"></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm text-on-surface-variant font-medium" x-text="log.timestamp"></td>
                        <td class="px-8 py-5">
                            <span class="font-bold text-on-surface" x-text="log.value_mg_dl"></span>
                            <span class="text-[10px] text-on-surface-variant ml-1 uppercase">mg/dL</span>
                        </td>
                        <td class="px-8 py-5">
                            <span :class="log.badge_class" class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide" x-text="log.status"></span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <button
                                @click="openPatientDetail(log.user_id)"
                                class="text-primary font-bold text-xs hover:underline flex items-center justify-end gap-1 ml-auto">
                                Details
                                <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div
        class="p-6 bg-surface-container-lowest flex items-center justify-between border-t border-surface-container">
        <p class="text-xs text-on-surface-variant font-medium" x-show="pagination.total > 0">
            Showing <span class="font-bold" x-text="(pagination.current_page - 1) * 10 + 1"></span> to
            <span class="font-bold" x-text="Math.min(pagination.current_page * 10, pagination.total)"></span> of
            <span class="font-bold" x-text="pagination.total"></span> recent readings
        </p>
        <p class="text-xs text-on-surface-variant font-medium" x-show="pagination.total === 0">
            Showing 0 recent readings
        </p>
        <div class="flex gap-2" x-show="pagination.last_page > 1">
            <button
                @click="loadLogs(pagination.current_page - 1)"
                :disabled="pagination.current_page === 1"
                class="p-2 bg-white rounded-lg border border-surface-container text-on-surface-variant hover:bg-slate-50 transition-colors disabled:opacity-50">
                <span class="material-symbols-outlined text-lg">chevron_left</span>
            </button>
            <span class="text-xs font-semibold text-on-surface-variant self-center px-2">
                Page <span x-text="pagination.current_page"></span> of <span x-text="pagination.last_page"></span>
            </span>
            <button
                @click="loadLogs(pagination.current_page + 1)"
                :disabled="pagination.current_page === pagination.last_page"
                class="p-2 bg-white rounded-lg border border-surface-container text-on-surface-variant hover:bg-slate-50 transition-colors disabled:opacity-50">
                <span class="material-symbols-outlined text-lg">chevron_right</span>
            </button>
        </div>
    </div>
</div>
