<div class="p-6 border-b border-slate-100 dark:border-slate-800 space-y-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
            <input type="text"
                   x-model="search"
                   @input.debounce.300ms="loadLogs()"
                   placeholder="Cari pasien atau protokol..."
                   class="w-full pl-11 pr-4 py-2.5 bg-surface-container-low border-none rounded-xl text-xs font-medium text-on-surface focus:ring-2 focus:ring-primary/30 focus:bg-white transition-all outline-none">
        </div>

        <!-- Date Filter -->
        <div class="flex items-center gap-2">
            <input type="date"
                   x-model="date"
                   @change="loadLogs()"
                   class="bg-surface-container-low text-xs font-semibold text-slate-600 px-3 py-2.5 rounded-xl border-none focus:ring-2 focus:ring-primary/30 outline-none">
            <button type="button"
                    x-show="date"
                    @click="date = ''; loadLogs()"
                    class="text-xs text-slate-400 hover:text-red-500 font-bold px-2">
                Reset Date
            </button>
        </div>
    </div>

    <!-- Status Filter Pills -->
    <div class="flex flex-wrap gap-2 pt-2">
        <template x-for="st in [
            { id: 'all', label: 'Semua Status' },
            { id: 'completed', label: 'Completed' },
            { id: 'planned', label: 'Planned' },
            { id: 'skipped', label: 'Skipped' },
            { id: 'missed', label: 'Missed' }
        ]" :key="st.id">
            <button type="button"
                    @click="status = st.id; loadLogs()"
                    :class="status === st.id ? 'bg-primary text-white font-bold shadow-sm' : 'bg-surface-container-low text-slate-600 hover:bg-slate-200'"
                    class="px-3.5 py-1.5 rounded-full text-xs transition-all uppercase tracking-wider font-semibold">
                <span x-text="st.label"></span>
            </button>
        </template>
    </div>
</div>
