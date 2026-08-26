@extends('layouts.app')

@section('content')
    <div x-data="auditTrailPage()" x-init="init()">
        @include('audit-trail.partials._stats')

        <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden mt-8">
            @include('audit-trail.partials._filters')
            @include('audit-trail.partials._table')
            @include('audit-trail.partials._pagination')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function auditTrailPage() {
            return {
                loading: false,
                logs: [],
                pagination: {},
                stats: {
                    total_events: 0,
                    admin_actions: 0,
                    patient_actions: 0,
                    security_alerts: 0
                },
                search: '',
                action: 'all',

                async init() {
                    await this.loadLogs()
                },

                async loadLogs(page = 1) {
                    try {
                        this.loading = true
                        const queryParams = new URLSearchParams({
                            page: page,
                            search: this.search,
                            action: this.action
                        });

                        const response = await fetch(`/audit-trail/data?${queryParams.toString()}`)
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
