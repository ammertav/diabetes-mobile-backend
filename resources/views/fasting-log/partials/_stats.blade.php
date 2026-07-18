<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Total Logs -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Sesi Puasa</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">event_available</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-slate-900 font-headline tracking-tight" x-text="stats.total_logs || 0">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Sesi tercatat dalam sistem</p>
        </div>
    </div>

    <!-- Card 2: Completed Logs -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Puasa Selesai</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">task_alt</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-emerald-600 font-headline tracking-tight" x-text="stats.completed_logs || 0">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Target puasa tercapai</p>
        </div>
    </div>

    <!-- Card 3: Adherence Rate -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tingkat Kepatuhan</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">analytics</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-blue-600 font-headline tracking-tight" x-text="(stats.adherence_rate || 0) + '%'">0%</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Rata-rata penyelesaian</p>
        </div>
    </div>

    <!-- Card 4: Skipped & Missed -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Dilewati / Terlewat</span>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">free_cancellation</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-rose-600 font-headline tracking-tight" x-text="(stats.skipped_logs || 0) + (stats.missed_logs || 0)">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Sesi tidak selesai</p>
        </div>
    </div>
</div>
