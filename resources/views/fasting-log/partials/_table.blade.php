<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                <th class="py-4 px-6">Pasien</th>
                <th class="py-4 px-6">Protokol Puasa</th>
                <th class="py-4 px-6">Tanggal & Jadwal</th>
                <th class="py-4 px-6">Durasi Aktual</th>
                <th class="py-4 px-6">Status & Mood</th>
                <th class="py-4 px-6 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs font-body">
            <!-- Loading State -->
            <template x-if="loading">
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400">
                        <span class="material-symbols-outlined animate-spin text-2xl mb-1">sync</span>
                        <p class="text-xs">Memuat data fasting logs...</p>
                    </td>
                </tr>
            </template>

            <!-- Empty State -->
            <template x-if="!loading && logs.length === 0">
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400">
                        <span class="material-symbols-outlined text-3xl mb-1">inbox</span>
                        <p class="text-xs font-medium">Tidak ada catatan puasa yang ditemukan.</p>
                    </td>
                </tr>
            </template>

            <!-- Data Rows -->
            <template x-for="(log, idx) in logs" :key="log.id || idx">
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <!-- Pasien -->
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <img :src="log.patient_photo" :alt="log.patient_name" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                            <div>
                                <p class="font-bold text-slate-900 text-sm font-headline" x-text="log.patient_name"></p>
                                <p class="text-xs text-slate-400" x-text="log.patient_email"></p>
                            </div>
                        </div>
                    </td>

                    <!-- Protokol -->
                    <td class="py-4 px-6">
                        <div class="flex flex-col items-start gap-1">
                            <span :class="log.protocol_type === 'sunnah' ? 'bg-amber-100 text-amber-900' : (log.protocol_type === 'intermittent' ? 'bg-blue-100 text-blue-900' : 'bg-purple-100 text-purple-900')"
                                  class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"
                                  x-text="log.protocol_type"></span>
                            <span class="font-bold text-slate-900 text-xs font-headline" x-text="log.protocol_name"></span>
                        </div>
                    </td>

                    <!-- Tanggal & Jadwal -->
                    <td class="py-4 px-6">
                        <p class="font-bold text-slate-900 text-xs" x-text="log.planned_date_formatted"></p>
                        <p class="text-[11px] text-slate-400" x-text="'Target: ' + log.target_duration_hours + ' Jam'"></p>
                    </td>

                    <!-- Durasi Aktual -->
                    <td class="py-4 px-6">
                        <span class="font-bold text-blue-600 text-xs" x-text="log.actual_duration_hours ? log.actual_duration_hours + ' Jam' : '-'"></span>
                    </td>

                    <!-- Status & Mood -->
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-2">
                            <span :class="{
                                'bg-emerald-100 text-emerald-800': log.status === 'completed',
                                'bg-blue-100 text-blue-800': log.status === 'planned',
                                'bg-amber-100 text-amber-800': log.status === 'skipped',
                                'bg-rose-100 text-rose-800': log.status === 'missed'
                            }" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                            x-text="log.status"></span>

                            <span x-show="log.mood === 'good'" title="Mood: Good" class="material-symbols-outlined text-emerald-500 text-base">sentiment_very_satisfied</span>
                            <span x-show="log.mood === 'neutral'" title="Mood: Neutral" class="material-symbols-outlined text-amber-500 text-base">sentiment_neutral</span>
                            <span x-show="log.mood === 'bad'" title="Mood: Bad" class="material-symbols-outlined text-rose-500 text-base">sentiment_very_dissatisfied</span>
                        </div>
                    </td>

                    <!-- Action -->
                    <td class="py-4 px-6 text-right">
                        <button type="button"
                                @click="openDetail(log)"
                                class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 text-xs font-semibold transition-all">
                            Detail
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>
