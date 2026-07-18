<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                <th class="py-4 px-6 whitespace-nowrap">Event ID</th>
                <th class="py-4 px-6 whitespace-nowrap">Pengguna</th>
                <th class="py-4 px-6 whitespace-nowrap">Aktivitas</th>
                <th class="py-4 px-6">Deskripsi</th>
                <th class="py-4 px-6 whitespace-nowrap">Alamat IP</th>
                <th class="py-4 px-6 text-right whitespace-nowrap">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs font-body">
            <!-- Loading State -->
            <template x-if="loading">
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400">
                        <span class="material-symbols-outlined animate-spin text-2xl mb-1">sync</span>
                        <p class="text-xs">Memuat log aktivitas...</p>
                    </td>
                </tr>
            </template>

            <!-- Empty State -->
            <template x-if="!loading && logs.length === 0">
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400">
                        <span class="material-symbols-outlined text-3xl mb-1">inbox</span>
                        <p class="text-xs font-medium">Tidak ada log aktivitas yang ditemukan.</p>
                    </td>
                </tr>
            </template>

            <!-- Data Rows -->
            <template x-for="(log, idx) in logs" :key="log.id || idx">
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <!-- Event ID -->
                    <td class="py-4 px-6 font-mono text-slate-900 font-bold text-xs whitespace-nowrap" x-text="log.id"></td>

                    <!-- Pengguna -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <p class="font-bold text-slate-900 text-xs font-headline" x-text="log.user_name"></p>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider" x-text="log.user_role"></p>
                    </td>

                    <!-- Aktivitas -->
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span :class="{
                            'bg-blue-100 text-blue-900': log.action === 'protocol_updated',
                            'bg-emerald-100 text-emerald-900': log.action === 'patient_reviewed',
                            'bg-indigo-100 text-indigo-900': log.action === 'fasting_confirmed',
                            'bg-purple-100 text-purple-900': log.action === 'cms_published',
                            'bg-teal-100 text-teal-900': log.action === 'fgb_recorded'
                        }" class="inline-flex items-center px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider whitespace-nowrap" x-text="log.action_label"></span>
                    </td>

                    <!-- Deskripsi -->
                    <td class="py-4 px-6 text-slate-600 max-w-md text-xs leading-relaxed" x-text="log.description"></td>

                    <!-- IP -->
                    <td class="py-4 px-6 font-mono text-slate-500 text-xs whitespace-nowrap" x-text="log.ip_address"></td>

                    <!-- Waktu -->
                    <td class="py-4 px-6 text-right font-bold text-slate-900 text-xs whitespace-nowrap" x-text="log.created_at"></td>
                </tr>
            </template>
        </tbody>
    </table>
</div>
