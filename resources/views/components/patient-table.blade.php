@php

@endphp

<div class="overflow-x-auto">

    <table class="w-full text-left border-collapse">

        <thead>
            <tr class="bg-surface-container-low">
                <th
                    class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                    Patient Name</th>
                <th
                    class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                    Patient ID</th>
                <th
                    class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                    Diabetes Type</th>
                <th
                    class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                    Protocol</th>
                <th
                    class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase text-center">
                    Risk Status</th>
                <th
                    class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase">
                    Last Check-in</th>
                <th
                    class="px-6 py-4 font-headline font-bold text-xs tracking-widest text-on-surface-variant uppercase text-right">
                    Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-surface-container">

            {{-- Skeleton --}}
            <template x-if="loading">
                <tr>
                    <td colspan="7" class="p-10 text-center text-on-surface-variant font-medium">
                        <div class="flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined animate-spin text-primary">sync</span>
                            Loading patients...
                        </div>
                    </td>
                </tr>
            </template>

            {{-- Empty State --}}
            <template x-if="!loading && patients.length === 0">
                <tr>
                    <td colspan="7" class="p-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl text-outline mb-2">person_search</span>
                        <p class="font-medium text-sm">No patients found matching the criteria.</p>
                    </td>
                </tr>
            </template>

            {{-- Data --}}
            <template x-for="patient in patients" :key="patient.id">
                <tr class="hover:bg-surface-container-low/50 transition-colors cursor-pointer group">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img
                                :src="patient.photo"
                                class="w-10 h-10 rounded-full object-cover shadow-sm bg-surface-container-high border border-surface-variant/30">
                            <div>
                                <p
                                    x-text="patient.name"
                                    class="font-semibold text-on-surface text-sm"></p>
                                <p
                                    x-text="patient.email"
                                    class="text-xs text-on-surface-variant"></p>
                            </div>
                        </div>
                    </td>

                    <td
                        x-text="patient.patient_code"
                        class="px-6 py-4 font-mono text-xs font-semibold text-on-surface-variant"></td>

                    <td class="px-6 py-4">
                        <span
                            x-text="patient.diabetes_type === 't2dm' ? 'Type 2' : (patient.diabetes_type === 'prediabetes' ? 'Prediabetes' : 'Healthy')"
                            :class="{
                                'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-300': patient.diabetes_type === 't2dm',
                                'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300': patient.diabetes_type === 'prediabetes',
                                'bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-300': patient.diabetes_type === 'healthy'
                            }"
                            class="text-[11px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider"></span>
                    </td>

                    <td class="px-6 py-4 text-sm font-medium text-on-surface">
                        <span
                            x-text="patient.protocol || 'No active protocol'"
                            :class="!patient.protocol ? 'text-outline/70 font-normal italic' : 'text-on-surface font-semibold'"></span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span
                            x-text="patient.risk_status"
                            :class="{
                                'bg-red-500 text-white': patient.risk_status === 'High',
                                'bg-amber-500 text-on-surface-variant': patient.risk_status === 'Medium',
                                'bg-green-500 text-white': patient.risk_status === 'Low'
                            }"
                            class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest shadow-sm"></span>
                    </td>

                    <td
                        x-text="patient.last_checkin"
                        class="px-6 py-4 text-sm text-on-surface-variant font-medium"></td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button
                                class="p-2 hover:bg-surface-container-high rounded-lg text-primary transition-colors hover:scale-105 active:scale-95"
                                title="View Patient Details">
                                <span class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                            <button
                                class="p-2 hover:bg-surface-container-high rounded-lg text-outline transition-colors hover:scale-105 active:scale-95"
                                title="Edit Patient Details">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </button>
                        </div>
                    </td>

                </tr>
            </template>

        </tbody>

    </table>

</div>
