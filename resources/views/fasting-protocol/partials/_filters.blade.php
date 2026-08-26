<!-- Full Width Filter Bar -->
<div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-wrap items-center justify-between gap-4 mb-8">
    <!-- Search Input -->
    <div class="relative flex-1 min-w-[280px]">
        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-outline">
            <span class="material-symbols-outlined">search</span>
        </div>
        <input type="text"
               x-model="searchQuery"
               placeholder="Cari protokol puasa..."
               class="w-full bg-surface-container-low border-none rounded-xl pl-10 pr-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 transition-all outline-none">
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <!-- Type Dropdown Select -->
        <select x-model="selectedType"
                class="bg-surface-container-low border-none rounded-xl pl-4 pr-10 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
            <option value="all">Semua Tipe</option>
            <option value="sunnah">Sunnah</option>
            <option value="intermittent">Intermittent</option>
            <option value="custom">Custom</option>
        </select>

        <!-- Reset Button -->
        <button type="button"
                x-show="searchQuery || selectedType !== 'all'"
                @click="searchQuery = ''; selectedType = 'all';"
                class="bg-surface-container-high p-3 rounded-xl text-on-surface-variant hover:bg-surface-variant transition-colors"
                title="Reset Filters">
            <span class="material-symbols-outlined">restart_alt</span>
        </button>

        <!-- Create Protocol Button -->
        <a href="{{ route('fasting-protocols-create') }}"
           class="bg-blue-600 text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-xl shadow-md shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-sm">add</span>
            Buat Protokol Baru
        </a>
    </div>
</div>
