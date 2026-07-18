<div class="p-6 flex items-center justify-between bg-surface-container-lowest border-t border-slate-100 dark:border-slate-800">
    <p class="text-xs text-slate-500" x-show="pagination.total > 0">
        Menampilkan <span class="font-bold text-on-surface" x-text="(pagination.current_page - 1) * 10 + 1"></span> -
        <span class="font-bold text-on-surface" x-text="Math.min(pagination.current_page * 10, pagination.total)"></span> dari
        <span class="font-bold text-on-surface" x-text="pagination.total"></span> catatan puasa
    </p>
    <p class="text-xs text-slate-500" x-show="!pagination.total || pagination.total === 0">
        Menampilkan 0 catatan
    </p>
    <div class="flex items-center gap-3" x-show="pagination.last_page > 1">
        <button type="button"
            @click="loadLogs(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="p-2 rounded-lg bg-surface-container-high text-on-surface-variant hover:bg-slate-200 transition-colors disabled:opacity-50">
            <span class="material-symbols-outlined text-sm">chevron_left</span>
        </button>
        <span class="text-xs font-semibold text-slate-600">
            Halaman <span x-text="pagination.current_page"></span> dari <span x-text="pagination.last_page"></span>
        </span>
        <button type="button"
            @click="loadLogs(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="p-2 rounded-lg bg-surface-container-high text-on-surface-variant hover:bg-slate-200 transition-colors disabled:opacity-50">
            <span class="material-symbols-outlined text-sm">chevron_right</span>
        </button>
    </div>
</div>
