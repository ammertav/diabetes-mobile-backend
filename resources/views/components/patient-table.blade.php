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
                    ID</th>
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
                    <td colspan="6" class="p-10 text-center">
                        Loading patients...
                    </td>
                </tr>
            </template>

            {{-- Data --}}
            <template x-for="patient in patients" :key="patient.id">
                <tr class="border-t border-outline-variant">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">

                            <img
                                :src="patient.photo"
                                class="w-10 h-10 rounded-full object-cover">

                            <div>
                                <p
                                    x-text="patient.name"
                                    class="font-semibold"></p>

                                <p
                                    x-text="patient.email"
                                    class="text-sm opacity-70"></p>
                            </div>

                        </div>
                    </td>

                    <td
                        x-text="patient.patient_code"
                        class="px-6 py-4"></td>

                    <td class="px-6 py-4">
                        <span
                            x-text="patient.diabetes_type"
                            :class="{
                                'bg-red-500 text-white': patient
                                    .diabetes_type === 't2dm',
                                'bg-amber-500 text-white': patient
                                    .diabetes_type === 'prediabetes',
                                'bg-green-500 text-white': patient
                                    .diabetes_type === 'healthy'
                            }"
                            class="text-xs font-bold px-2 py-1 rounded-lg"></span>
                    </td>

                    <td class="px-6 py-4">
                        <span
                            x-text="patient.protocol || 'tidak ada protocol'"
                            :class="!patient.protocol && 'text-gray-400'"></span>
                    </td>

                    <td class="px-6 py-4">

                        <span
                            x-text="patient.risk_status"
                            class="px-3 py-1 rounded-full text-xs"></span>

                    </td>

                    <td
                        x-text="patient.last_checkin"
                        class="px-6 py-4"></td>

                </tr>

                <tr
                    class="hover:bg-surface-container-low/50 transition-colors cursor-pointer group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <img alt="Patient Sarah Miller"
                                class="w-10 h-10 rounded-full object-cover"
                                data-alt="Soft-lit professional studio portrait of a woman in her 40s looking at the camera"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuC812RwVhtcZj8720Y4tMFkAY2nggJnY1aoGeeLBuCSwPOQcR41IS0VG0iHGHPdLbkFAGEfGyXFyqPy2WD1-7KvqFLHxMy9snBHeNYr5UCgO4OEi9llLDE4_aoNLgbqMDyK1iwmmnS137nae-9DUl9rTUM2pbFM5oinNUXj_W3AJYR57Ev2_8pOZykkKDPSWS7tMw28S4xa5xsNWr9sZv7Fj7qHWxS9z8geGk0yYK1EWRDUK2QXFDXQXG38Fo9xCuYhLzzJ_0kO7c8" />
                            <div>
                                <p
                                    class="font-bold text-on-surface text-sm"
                                    x-text="patient.name">
                                    Sarah Miller</p>
                                <p class="text-on-surface-variant text-xs"
                                    x-text="patient.email">
                                    miller.s@email.com</p>
                            </div>
                        </div>
                    </td>
                    <td
                        class="px-6 py-5 font-medium text-sm text-on-surface-variant"
                        x-text="patient.id">
                        #DB-9421</td>
                    <td class="px-6 py-5">
                        <span
                            class="text-xs font-bold px-2 py-1 bg-primary-fixed text-on-primary-fixed rounded-lg">Type
                            1</span>
                    </td>
                    <td
                        class="px-6 py-5 text-sm font-medium text-on-surface"
                        x-text="patient.type">
                        Intensive Insulin</td>
                    <td class="px-6 py-5 text-center">
                        <span
                            class="px-3 py-1 bg-tertiary-container text-white text-[10px] font-bold rounded-full uppercase tracking-wider"
                            x-text="patient.risk">High
                            Risk</span>
                    </td>
                    <td class="px-6 py-5 text-sm text-on-surface-variant"
                        x-text="patient.last_check_in">
                        Today, 09:12 AM</td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <button
                                class="p-2 hover:bg-surface-container-high rounded-lg text-primary transition-colors">
                                <span
                                    class="material-symbols-outlined text-xl">visibility</span>
                            </button>
                            <button
                                class="p-2 hover:bg-surface-container-high rounded-lg text-outline transition-colors">
                                <span
                                    class="material-symbols-outlined text-xl">edit</span>
                            </button>
                        </div>
                    </td>
                </tr>
            </template>

        </tbody>

    </table>

</div>
