<div class="p-6 bg-surface-container-lowest border-b border-surface-container flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-4 flex-1 min-w-[280px]">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-outline">
                <span class="material-symbols-outlined">search</span>
            </div>
            <input type="text"
                   x-model="search"
                   @input.debounce.300ms="loadLogs(1)"
                   placeholder="Cari pasien atau protokol..."
                   class="w-full bg-surface-container-low border-none rounded-xl pl-10 pr-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 transition-all outline-none">
        </div>
        <button type="button"
                @click="search = ''; status = 'all'; date = ''; loadLogs(1);"
                class="bg-surface-container-high p-3 rounded-xl text-on-surface-variant hover:bg-surface-variant transition-colors"
                title="Reset Filters">
            <span class="material-symbols-outlined">restart_alt</span>
        </button>
    </div>

    <div class="flex items-center gap-3">
        <!-- Status Dropdown -->
        <select x-model="status"
                @change="loadLogs(1)"
                class="bg-surface-container-low border-none rounded-xl pl-4 pr-10 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
            <option value="all">Semua Status</option>
            <option value="completed">Completed</option>
            <option value="planned">Planned</option>
            <option value="skipped">Skipped</option>
            <option value="missed">Missed</option>
        </select>

        <!-- Date Filter -->
        <input type="date"
               x-model="date"
               @change="loadLogs(1)"
               class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
    </div>
</div>
