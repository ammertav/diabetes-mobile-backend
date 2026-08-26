@extends('layouts.app')

@section('content')
    <div x-data="fastingLogPage()" x-init="init()">
        @include('fasting-log.partials._stats')

        <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden mt-8">
            @include('fasting-log.partials._filters')
            @include('fasting-log.partials._table')
            @include('fasting-log.partials._pagination')
        </div>

        @include('fasting-log.partials._modal-detail')
    </div>
@endsection

@push('scripts')
    <script>
        function fastingLogPage() {
            return {
                loading: false,
                logs: [],
                pagination: {},
                stats: {
                    total_logs: 0,
                    completed_logs: 0,
                    skipped_logs: 0,
                    missed_logs: 0,
                    adherence_rate: 0
                },
                search: '',
                status: 'all',
                date: '',
                selectedLog: null,
                showDetailModal: false,

                async init() {
                    await this.loadLogs()
                },

                async loadLogs(page = 1) {
                    try {
                        this.loading = true
                        const queryParams = new URLSearchParams({
                            page: page,
                            search: this.search,
                            status: this.status,
                            date: this.date
                        });

                        const response = await fetch(`/fasting-logs/data?${queryParams.toString()}`)
                        const result = await response.json()

                        this.logs = result.logs.data;
                        this.pagination = result.pagination;
                        this.stats = result.stats;
                    } catch (error) {
                        console.error(error)
                    } finally {
                        this.loading = false
                    }
                },

                openDetail(log) {
                    this.selectedLog = log;
                    this.showDetailModal = true;
                }
            }
        }
    </script>
@endpush
