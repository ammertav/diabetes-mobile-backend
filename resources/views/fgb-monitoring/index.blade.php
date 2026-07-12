@extends('layouts.app')
@section('content')
    <div x-data="fgbMonitoringPage()" x-init="init()">
        @include('fgb-monitoring.partials._stats')

        @include('fgb-monitoring.partials._chart')

        @include('fgb-monitoring.partials._table')

        @include('fgb-monitoring.partials._modal-detail')

        @include('fgb-monitoring.partials._modal-chart-detail')
    </div>
@endsection

@push('scripts')
    <script>
        function fgbMonitoringPage() {
            return {
                loading: false,
                logs: [],
                pagination: {},
                stats: {
                    avg_fgb: 104,
                    target_range_percent: 78.4,
                    abnormal_alerts: 0
                },
                search: '',
                status: 'all',
                chartPeriod: 'daily',
                chartGroup: 'all',
                chartData: [],
                hoveredChartLabel: null,
                showChartDetailModal: false,
                chartDetailLoading: false,
                chartDetailRecords: [],
                selectedChartTitle: '',
                selectedChartAvg: 0,
                selectedChartCount: 0,
                showDetailModal: false,
                detailLoading: false,
                detailPatient: {},
                detailChartData: [],
                detailRecords: [],

                async init() {
                    await this.loadLogs()
                    await this.loadChartData()
                },

                async openPatientDetail(userId) {
                    try {
                        this.showDetailModal = true
                        this.detailLoading = true

                        const response = await fetch(
                            `/fgb-monitoring/patients/${userId}`)
                        const result = await response.json()

                        this.detailPatient = result.patient
                        this.detailChartData = result.chart_data
                        this.detailRecords = result.records
                    } catch (error) {
                        console.error(error)
                    } finally {
                        this.detailLoading = false
                    }
                },

                closePatientDetail() {
                    this.showDetailModal = false
                },

                async openChartDetail(index, label) {
                    try {
                        this.showChartDetailModal = true
                        this.chartDetailLoading = true
                        this.selectedChartTitle = label
                        
                        const queryParams = new URLSearchParams({
                            period: this.chartPeriod,
                            group: this.chartGroup,
                            index: index
                        });

                        const response = await fetch(`/fgb-monitoring/chart-details?${queryParams.toString()}`)
                        const result = await response.json()

                        this.selectedChartTitle = result.title
                        this.selectedChartAvg = result.avg_fgb
                        this.selectedChartCount = result.count
                        this.chartDetailRecords = result.records.data || result.records
                    } catch (error) {
                        console.error(error)
                    } finally {
                        this.chartDetailLoading = false
                    }
                },

                closeChartDetail() {
                    this.showChartDetailModal = false
                },

                async loadChartData() {
                    try {
                        const queryParams = new URLSearchParams({
                            period: this.chartPeriod,
                            group: this.chartGroup
                        });

                        const response = await fetch(
                            `/fgb-monitoring/chart?${queryParams.toString()}`
                        )

                        this.chartData = await response.json()
                    } catch (error) {
                        console.error(error)
                    }
                },

                async loadLogs(page = 1) {
                    try {
                        this.loading = true

                        const queryParams = new URLSearchParams({
                            page: page,
                            search: this.search,
                            status: this.status
                        });

                        const response = await fetch(
                            `/fgb-monitoring/data?${queryParams.toString()}`
                        )

                        const result = await response.json()

                        this.logs = result.logs.data;
                        this.pagination = result.pagination;
                        this.stats = result.stats;

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
