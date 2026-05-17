@extends('layouts.app')
@section('content')
    <header
        class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1
                class="font-headline font-extrabold text-4xl text-on-surface tracking-tight mb-2">
                Patient Management</h1>
            <p class="text-on-surface-variant font-medium">Monitoring
                patients across the Diabetes Division</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                class="bg-surface-container-highest px-5 py-2.5 rounded-xl font-bold text-sm text-on-surface-variant flex items-center gap-2 hover:bg-surface-container-high transition-colors">
                <span
                    class="material-symbols-outlined text-xl">file_download</span>
                Export Registry
            </button>
            <button
                class="bg-primary text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 shadow-lg shadow-primary/20 hover:shadow-xl transition-all scale-98-active">
                <span class="material-symbols-outlined text-xl">person_add</span>
                Add New Patient
            </button>
        </div>
    </header>
    <!-- Summary Cards Bento Grid -->
    <div
        x-data="patientManagementPage()"
        x-init="init()">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div
                class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
                <div class="flex items-center justify-between">
                    <div class="p-3 bg-primary-fixed rounded-full">
                        <span
                            class="material-symbols-outlined text-primary text-2xl"
                            style="font-variation-settings: 'FILL' 1;">group</span>
                    </div>
                    <span
                        class="text-secondary font-bold text-xs bg-secondary-container px-2 py-1 rounded-full">+12.5%</span>
                </div>
                <div>
                    <p
                        class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                        Total Patients</p>
                    <h3 class="font-headline font-bold text-4xl text-on-surface"
                        x-text="total_patients">
                    </h3>
                </div>
            </div>
            <div
                class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
                <div class="flex items-center justify-between">
                    <div class="p-3 bg-secondary-container rounded-full">
                        <span
                            class="material-symbols-outlined text-secondary text-2xl"
                            style="font-variation-settings: 'FILL' 1;">timer</span>
                    </div>
                    <span
                        class="text-on-surface-variant font-bold text-xs bg-surface-container-high px-2 py-1 rounded-full">Stable</span>
                </div>
                <div>
                    <p
                        class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                        Fasting Protocol</p>
                    <h3 class="font-headline font-bold text-4xl text-on-surface"
                        x-text="protocol_patients">
                    </h3>
                </div>
            </div>
            <div
                class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
                <div class="flex items-center justify-between">
                    <div class="p-3 bg-tertiary-container/10 rounded-full">
                        <span
                            class="material-symbols-outlined text-tertiary text-2xl"
                            style="font-variation-settings: 'FILL' 1;">warning</span>
                    </div>
                    <span
                        class="text-tertiary font-bold text-xs bg-tertiary-container/20 px-2 py-1 rounded-full">Critical</span>
                </div>
                <div>
                    <p
                        class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                        High Risk Patients</p>
                    <h3 class="font-headline font-bold text-4xl text-tertiary"
                        x-text="high_risk_patients">
                    </h3>
                </div>
            </div>
        </div>
        <!-- Content Area: Registry Table -->
        <div
            class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
            <!-- Filter Bar -->
            <div
                class="p-6 bg-surface-container-lowest border-b border-surface-container flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4 flex-1 min-w-75">
                    <div class="relative flex-1">
                        <input
                            class="w-full bg-surface-container-low border-none rounded-xl px-10 py-3 text-sm focus:ring-2 focus:ring-primary/20"
                            placeholder="Search by name, ID or protocol..."
                            type="text" />
                        <span
                            class="material-symbols-outlined absolute left-3 top-3 text-outline">search</span>
                    </div>
                    <button
                        class="bg-surface-container-high p-3 rounded-xl text-on-surface-variant hover:bg-surface-variant transition-colors">
                        <span class="material-symbols-outlined">tune</span>
                    </button>
                </div>
                <div class="flex items-center gap-3">
                    <select
                        class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20">
                        <option>All Risk Levels</option>
                        <option>High Risk</option>
                        <option>Medium Risk</option>
                        <option>Low Risk</option>
                    </select>
                    <select
                        class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20">
                        <option>All Protocols</option>
                        <option>Fasting</option>
                        <option>Intensive Insulin</option>
                        <option>Maintenance</option>
                    </select>
                    <input
                        class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20"
                        type="date" />
                </div>
            </div>
            <!-- Table -->
            <x-patient-table ::patients="patients" ::loading="loading" />
            <!-- Pagination -->
            <div
                class="p-6 flex items-center justify-between bg-surface-container-lowest">
                <p class="text-sm text-on-surface-variant">Showing <span
                        class="font-bold">1-10</span> of <span
                        class="font-bold">1,284</span> patients</p>
                <div class="flex items-center gap-2">
                    <button
                        class="p-2 rounded-lg bg-surface-container-high text-on-surface-variant hover:bg-surface-variant transition-colors disabled:opacity-50"
                        disabled="">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button
                        class="px-4 py-2 rounded-lg bg-primary text-white font-bold text-sm">1</button>
                    <button
                        class="px-4 py-2 rounded-lg hover:bg-surface-container-high text-on-surface font-medium text-sm transition-colors">2</button>
                    <button
                        class="px-4 py-2 rounded-lg hover:bg-surface-container-high text-on-surface font-medium text-sm transition-colors">3</button>
                    <span class="text-on-surface-variant">...</span>
                    <button
                        class="px-4 py-2 rounded-lg hover:bg-surface-container-high text-on-surface font-medium text-sm transition-colors">129</button>
                    <button
                        class="p-2 rounded-lg bg-surface-container-high text-on-surface-variant hover:bg-surface-variant transition-colors">
                        <span
                            class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function patientManagementPage() {
            return {
                loading: false,
                patients: [],
                pagination: {},
                total_patients: 0,
                protocol_patients: 0,
                high_risk_patients: 0,

                async init() {
                    await this.loadPatients()
                },

                async loadPatients(page = 1) {
                    try {
                        this.loading = true

                        const response = await fetch(
                            `/patients/data?page=${page}`
                        )

                        const result = await response.json()

                        this.patients = result.patients.data;
                        this.pagination = result.pagination;
                        this.total_patients = result.stats.total_patients;
                        this.protocol_patients = result.stats.protocol_patients;
                        this.high_risk_patients = result.stats
                            .high_risk_patients;

                    } catch (error) {
                        console.error(error)
                    } finally {
                        this.loading = false
                    }
                }
            }
        }
    </script>
@endpush
