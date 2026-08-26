<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800 text-sm">
        <thead class="bg-slate-50 dark:bg-slate-800 text-slate-500 font-semibold">
            <tr>
                <th class="px-6 py-4 text-left">Patient Name</th>
                <th class="px-6 py-4 text-left">Alert Type</th>
                <th class="px-6 py-4 text-left">Glucose Value</th>
                <th class="px-6 py-4 text-left">Description</th>
                <th class="px-6 py-4 text-left">Action Taken</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-left">Timestamp</th>
                <th class="px-6 py-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
            <template x-for="alert in alerts" :key="alert.id">
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="px-6 py-4 font-medium" x-text="alert.patient_name"></td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold"
                            :class="{
                                'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-400': alert.type.includes('severe'),
                                'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400': !alert.type.includes('severe')
                            }"
                            x-text="alert.type.replace('_', ' ').toUpperCase()">
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold" x-text="alert.glucose_value ? alert.glucose_value + ' mg/dL' : '-'"></td>
                    <td class="px-6 py-4" x-text="alert.message"></td>
                    <td class="px-6 py-4 text-xs italic" x-text="alert.action_taken"></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded text-xs font-medium"
                            :class="{
                                'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300': alert.status === 'Resolved',
                                'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300': alert.status === 'Unresolved'
                            }"
                            x-text="alert.status">
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-400" x-text="alert.created_at"></td>
                    <td class="px-6 py-4 text-center">
                        <button
                            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold shadow-sm transition-all flex items-center gap-1 mx-auto"
                            @click="notifyPatient(alert.id)"
                            :disabled="alert.status === 'Resolved'">
                            <span class="material-symbols-outlined text-sm">notifications</span>
                            Notify FCM
                        </button>
                    </td>
                </tr>
            </template>
            <tr x-show="alerts.length === 0">
                <td colspan="8" class="text-center py-8 text-slate-400">No safety alerts matching current filter.</td>
            </tr>
        </tbody>
    </table>
</div>
