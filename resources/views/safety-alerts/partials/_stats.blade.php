<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4">
        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
            <span class="material-symbols-outlined text-2xl">warning</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Total Alerts</p>
            <p class="text-2xl font-bold text-slate-800 dark:text-slate-200" x-text="stats.total">0</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4">
        <div class="p-3 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl">
            <span class="material-symbols-outlined text-2xl">emergency</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Unresolved Alerts</p>
            <p class="text-2xl font-bold text-rose-600 dark:text-rose-400" x-text="stats.unresolved">0</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4">
        <div class="p-3 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
            <span class="material-symbols-outlined text-2xl">report_problem</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Severe Alerts</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400" x-text="stats.severe">0</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-4">
        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl">
            <span class="material-symbols-outlined text-2xl">check_circle</span>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">Resolved Alerts</p>
            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400" x-text="stats.resolved">0</p>
        </div>
    </div>
</div>
