<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Metric 1: Avg FGB -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest"
                  x-text="chartPeriod === 'daily' ? 'Daily Avg FGB' : (chartPeriod === 'weekly' ? 'Weekly Avg FGB' : 'Monthly Avg FGB')"></span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">speed</span>
            </div>
        </div>
        <div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-extrabold text-slate-900 font-headline tracking-tight" x-text="stats.avg_fgb || '104'">104</h3>
                <span class="text-xs font-medium text-slate-400">mg/dL</span>
            </div>
            <p :class="stats.avg_fgb_diff < 0 ? 'text-emerald-600' : (stats.avg_fgb_diff > 0 ? 'text-rose-600' : 'text-slate-500')"
               class="text-xs font-bold mt-1 flex items-center gap-1">
                <span class="material-symbols-outlined text-xs"
                      x-text="stats.avg_fgb_diff < 0 ? 'arrow_downward' : (stats.avg_fgb_diff > 0 ? 'arrow_upward' : 'remove')"></span>
                <span x-text="stats.avg_fgb_diff > 0 ? '+' + stats.avg_fgb_diff + '% from last period' : (stats.avg_fgb_diff < 0 ? stats.avg_fgb_diff + '% from last period' : 'No change')"></span>
            </p>
        </div>
    </div>

    <!-- Metric 2: Target Range -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">In Target Range</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">task_alt</span>
            </div>
        </div>
        <div>
            <div class="flex items-baseline gap-1">
                <h3 class="text-4xl font-extrabold text-emerald-600 font-headline tracking-tight" x-text="stats.target_range_percent || '78.4'">78.4</h3>
                <span class="text-xs font-bold text-emerald-600">%</span>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                <div class="bg-emerald-500 h-full" :style="'width: ' + (stats.target_range_percent || 78.4) + '%'"></div>
            </div>
        </div>
    </div>

    <!-- Metric 3: Abnormal Readings -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Abnormal Events</span>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">warning</span>
            </div>
        </div>
        <div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-extrabold text-rose-600 font-headline tracking-tight" x-text="stats.abnormal_alerts || '0'">0</h3>
                <span class="text-xs font-medium text-slate-400">alerts</span>
            </div>
            <p class="text-xs font-medium text-slate-500 mt-1">Requires immediate clinical review</p>
        </div>
    </div>
</div>
