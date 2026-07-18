<div class="p-6 bg-surface-container-lowest border-b border-surface-container flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-4 flex-1 min-w-[280px]">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-outline">
                <span class="material-symbols-outlined">search</span>
            </div>
            <input type="text"
                   x-model="search"
                   @input.debounce.300ms="loadLogs(1)"
                   placeholder="Cari pengguna, ID event, atau deskripsi..."
                   class="w-full bg-surface-container-low border-none rounded-xl pl-10 pr-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 transition-all outline-none">
        </div>
        <button type="button"
                @click="search = ''; action = 'all'; loadLogs(1);"
                class="bg-surface-container-high p-3 rounded-xl text-on-surface-variant hover:bg-surface-variant transition-colors"
                title="Reset Filters">
            <span class="material-symbols-outlined">restart_alt</span>
        </button>
    </div>

    <div class="flex items-center gap-3">
        <!-- Action Dropdown -->
        <select x-model="action"
                @change="loadLogs(1)"
                class="bg-surface-container-low border-none rounded-xl pl-4 pr-10 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
            <option value="all">Semua Aktivitas</option>
            <option value="protocol_updated">Protocol Update</option>
            <option value="patient_reviewed">Patient Review</option>
            <option value="fasting_confirmed">Konfirmasi Puasa</option>
            <option value="cms_published">Publikasi CMS</option>
            <option value="fgb_recorded">Catat FGB</option>
        </select>
    </div>
</div>
