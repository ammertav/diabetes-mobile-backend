<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Metric 1: Avg FGB -->
    <div
        class="bg-surface-container-lowest p-8 rounded-xl shadow-sm flex flex-col justify-between h-48">
        <div class="flex justify-between items-start">
            <span
                class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Weekly
                Avg FGB</span>
            <div class="p-2 bg-primary/10 text-primary rounded-lg">
                <span class="material-symbols-outlined text-xl">speed</span>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span
                    class="text-5xl font-black text-on-surface tracking-tighter" x-text="stats.avg_fgb || '104'"></span>
                <span
                    class="text-on-surface-variant font-medium">mg/dL</span>
            </div>
            <p
                class="text-secondary text-xs font-bold mt-2 flex items-center gap-1">
                <span
                    class="material-symbols-outlined text-sm">trending_down</span>
                -4.2% from last week
            </p>
        </div>
    </div>
    <!-- Metric 2: Target Range -->
    <div
        class="bg-surface-container-lowest p-8 rounded-xl shadow-sm flex flex-col justify-between h-48 relative overflow-hidden">
        <div class="flex justify-between items-start z-10">
            <span
                class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">In
                Target Range</span>
            <div class="p-2 bg-secondary/10 text-secondary rounded-lg">
                <span
                    class="material-symbols-outlined text-xl">task_alt</span>
            </div>
        </div>
        <div class="mt-4 z-10">
            <div class="flex items-baseline gap-2">
                <span
                    class="text-5xl font-black text-on-surface tracking-tighter" x-text="stats.target_range_percent || '78.4'"></span>
                <span class="text-on-surface-variant font-medium">%</span>
            </div>
            <div
                class="w-full bg-surface-container-high h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="h-full signature-gradient" :style="'width: ' + (stats.target_range_percent || 78.4) + '%'"></div>
            </div>
        </div>
        <!-- Subtle background abstract -->
        <div class="absolute -right-4 -bottom-4 opacity-5">
            <span
                class="material-symbols-outlined text-9xl">analytics</span>
        </div>
    </div>
    <!-- Metric 3: Abnormal Readings -->
    <div
        class="bg-surface-container-lowest p-8 rounded-xl shadow-sm flex flex-col justify-between h-48 border border-tertiary/10">
        <div class="flex justify-between items-start">
            <span
                class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Abnormal
                Events</span>
            <div class="p-2 bg-tertiary/10 text-tertiary rounded-lg">
                <span
                    class="material-symbols-outlined text-xl">warning</span>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex items-baseline gap-2">
                <span
                    class="text-5xl font-black text-tertiary tracking-tighter" x-text="stats.abnormal_alerts || '0'"></span>
                <span
                    class="text-on-surface-variant font-medium">alerts</span>
            </div>
            <p class="text-on-surface-variant text-xs font-medium mt-2">
                Requires immediate clinical review</p>
        </div>
    </div>
</div>
