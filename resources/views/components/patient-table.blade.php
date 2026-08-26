<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                <th class="py-4 px-6">Patient Name</th>
                <th class="py-4 px-6">Patient ID</th>
                <th class="py-4 px-6">Diabetes Type</th>
                <th class="py-4 px-6">Protocol</th>
                <th class="py-4 px-6 text-center">Risk Status</th>
                <th class="py-4 px-6">Last Check-in</th>
                <th class="py-4 px-6 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs font-body">
            <!-- Loading -->
            <template x-if="loading">
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-400">
                        <span class="material-symbols-outlined animate-spin text-2xl mb-1">sync</span>
                        <p class="text-xs font-medium">Loading patients...</p>
                    </td>
                </tr>
            </template>

            <!-- Empty -->
            <template x-if="!loading && patients.length === 0">
                <tr>
                    <td colspan="7" class="py-12 text-center text-slate-400">
                        <span class="material-symbols-outlined text-3xl mb-1">person_search</span>
                        <p class="text-xs font-medium">No patients found matching the criteria.</p>
                    </td>
                </tr>
            </template>

            <!-- Rows -->
            <template x-for="patient in patients" :key="patient.id">
                <tr class="hover:bg-slate-50/60 transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <img :src="patient.photo" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                            <div>
                                <p x-text="patient.name" class="font-bold text-slate-900 text-sm font-headline"></p>
                                <p x-text="patient.email" class="text-xs text-slate-400"></p>
                            </div>
                        </div>
                    </td>

                    <td x-text="patient.patient_code" class="py-4 px-6 font-mono text-xs font-bold text-slate-900"></td>

                    <td class="py-4 px-6">
                        <span x-text="patient.diabetes_type === 't2dm' ? 'Type 2' : (patient.diabetes_type === 'prediabetes' ? 'Prediabetes' : 'Healthy')"
                              :class="{
                                  'bg-rose-100 text-rose-800': patient.diabetes_type === 't2dm',
                                  'bg-amber-100 text-amber-800': patient.diabetes_type === 'prediabetes',
                                  'bg-emerald-100 text-emerald-800': patient.diabetes_type === 'healthy'
                              }"
                              class="text-[10px] font-bold px-2.5 py-0.5 rounded-md uppercase tracking-wider"></span>
                    </td>

                    <td class="py-4 px-6">
                        <span x-text="patient.protocol || 'No active protocol'"
                              :class="!patient.protocol ? 'text-slate-400 font-normal italic' : 'text-slate-900 font-bold font-headline'"></span>
                    </td>

                    <td class="py-4 px-6 text-center">
                        <span x-text="patient.risk_status"
                              :class="{
                                  'bg-rose-600 text-white': patient.risk_status === 'High',
                                  'bg-amber-500 text-white': patient.risk_status === 'Medium',
                                  'bg-emerald-600 text-white': patient.risk_status === 'Low'
                              }"
                              class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-sm"></span>
                    </td>

                    <td x-text="patient.last_checkin" class="py-4 px-6 text-xs text-slate-500 font-medium"></td>

                    <td class="py-4 px-6 text-right">
                        <button type="button"
                                class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-600 hover:text-white text-slate-600 text-xs font-semibold transition-all">
                            Detail
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>
