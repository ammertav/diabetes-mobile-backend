<div x-show="showDetailModal"
     class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div @click.away="showDetailModal = false"
         class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 dark:border-slate-800 space-y-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4">

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
            <div class="flex items-center gap-3">
                <img :src="selectedLog?.patient_photo" :alt="selectedLog?.patient_name" class="w-10 h-10 rounded-full object-cover border">
                <div>
                    <h3 class="font-bold text-on-surface text-sm font-headline" x-text="selectedLog?.patient_name"></h3>
                    <p class="text-xs text-slate-400" x-text="selectedLog?.patient_email"></p>
                </div>
            </div>
            <button type="button" @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <!-- Details Grid -->
        <div class="space-y-4 text-xs">
            <div class="grid grid-cols-2 gap-4 bg-surface-container-low p-4 rounded-xl">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Protokol Puasa</p>
                    <p class="font-bold text-primary" x-text="selectedLog?.protocol_name"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Sesi</p>
                    <span :class="{
                        'bg-emerald-100 text-emerald-800': selectedLog?.status === 'completed',
                        'bg-blue-100 text-blue-800': selectedLog?.status === 'planned',
                        'bg-amber-100 text-amber-800': selectedLog?.status === 'skipped',
                        'bg-rose-100 text-rose-800': selectedLog?.status === 'missed'
                    }" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                    x-text="selectedLog?.status"></span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 bg-surface-container-low p-4 rounded-xl text-center">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Target</p>
                    <p class="font-bold text-on-surface" x-text="selectedLog?.target_duration_hours + ' Jam'"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Aktual</p>
                    <p class="font-bold text-primary" x-text="selectedLog?.actual_duration_hours ? selectedLog.actual_duration_hours + ' Jam' : '-'"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mood Pasien</p>
                    <span class="font-bold uppercase text-on-surface" x-text="selectedLog?.mood || '-'"></span>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="space-y-2 pt-2">
                <div class="flex justify-between border-b border-dashed border-slate-200 pb-2">
                    <span class="text-slate-500">Tanggal Direncanakan:</span>
                    <span class="font-semibold text-on-surface" x-text="selectedLog?.planned_date_formatted"></span>
                </div>
                <div class="flex justify-between border-b border-dashed border-slate-200 pb-2">
                    <span class="text-slate-500">Waktu Mulai:</span>
                    <span class="font-semibold text-on-surface" x-text="selectedLog?.started_at"></span>
                </div>
                <div class="flex justify-between border-b border-dashed border-slate-200 pb-2">
                    <span class="text-slate-500">Waktu Selesai:</span>
                    <span class="font-semibold text-on-surface" x-text="selectedLog?.ended_at"></span>
                </div>
                <div class="flex justify-between border-b border-dashed border-slate-200 pb-2">
                    <span class="text-slate-500">Dikonfirmasi Pada:</span>
                    <span class="font-semibold text-on-surface" x-text="selectedLog?.confirmed_at"></span>
                </div>
            </div>

            <!-- Skip Reason / Notes -->
            <div x-show="selectedLog?.skip_reason && selectedLog?.skip_reason !== '-'" class="bg-amber-50 p-3 rounded-xl border border-amber-200">
                <p class="text-[10px] font-bold text-amber-800 uppercase tracking-wider mb-1">Alasan Melewati Puasa</p>
                <p class="text-amber-900 leading-relaxed" x-text="selectedLog?.skip_reason"></p>
            </div>

            <div x-show="selectedLog?.notes && selectedLog?.notes !== '-'" class="bg-blue-50/50 p-3 rounded-xl border border-blue-100">
                <p class="text-[10px] font-bold text-blue-800 uppercase tracking-wider mb-1">Catatan Pasien</p>
                <p class="text-blue-900 leading-relaxed italic" x-text="selectedLog?.notes"></p>
            </div>
        </div>

        <!-- Footer Button -->
        <div class="pt-2 flex justify-end">
            <button type="button" @click="showDetailModal = false"
                    class="px-5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
