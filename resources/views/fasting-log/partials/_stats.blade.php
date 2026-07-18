<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <!-- Card 1: Total Logs -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Sesi Puasa</p>
            <h3 class="text-3xl font-extrabold text-on-surface font-headline" x-text="stats.total_logs || 0">0</h3>
            <p class="text-xs text-slate-500 mt-1">Sesi tercatat</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">event_available</span>
        </div>
    </div>

    <!-- Card 2: Completed Logs -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Puasa Selesai</p>
            <h3 class="text-3xl font-extrabold text-emerald-600 font-headline" x-text="stats.completed_logs || 0">0</h3>
            <p class="text-xs text-slate-500 mt-1">Target tercapai</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">task_alt</span>
        </div>
    </div>

    <!-- Card 3: Adherence Rate -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tingkat Kepatuhan</p>
            <h3 class="text-3xl font-extrabold text-primary font-headline" x-text="(stats.adherence_rate || 0) + '%'">0%</h3>
            <p class="text-xs text-slate-500 mt-1">Rata-rata penyelesaian</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">analytics</span>
        </div>
    </div>

    <!-- Card 4: Skipped & Missed -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Dilewati / Terlewat</p>
            <h3 class="text-3xl font-extrabold text-amber-600 font-headline" x-text="(stats.skipped_logs || 0) + (stats.missed_logs || 0)">0</h3>
            <p class="text-xs text-slate-500 mt-1">Sesi tidak selesai</p>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">free_cancellation</span>
        </div>
    </div>
</div>
