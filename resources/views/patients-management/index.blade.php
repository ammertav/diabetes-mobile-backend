@extends('layouts.app')
@section('content')
    <div
        x-data="patientManagementPage()"
        x-init="init()">

        @include('patients-management.partials._stats')

        <!-- Content Area: Registry Table -->
        <div
            class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">

            @include('patients-management.partials._filters')

            <!-- Table -->
            <x-patient-table ::patients="patients" ::loading="loading" />

            @include('patients-management.partials._pagination')

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
                search: '',
                risk: 'all',
                protocol: 'all',
                date: '',

                async init() {
                    await this.loadPatients()
                },

                async loadPatients(page = 1) {
                    try {
                        this.loading = true

                        const queryParams = new URLSearchParams({
                            page: page,
                            search: this.search,
                            risk: this.risk,
                            protocol: this.protocol,
                            date: this.date
                        });

                        const response = await fetch(
                            `/patients/data?${queryParams.toString()}`
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
