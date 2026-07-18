@extends('layouts.app')

<!-- Hero Metrics: Bento Style -->
@section('content')
    <div class="grid grid-cols-12 gap-6">
        <!-- Total Patients Card -->
        <div
            class="col-span-12 md:col-span-4 bg-surface-container-lowest p-8 rounded-xl shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <span
                    class="text-sm font-label font-semibold text-on-surface-variant tracking-wider uppercase">Total Patients</span>
                <div class="mt-4 flex items-end gap-2">
                    <span
                        class="text-4xl font-headline font-extrabold text-on-surface">{{ number_format($stats['total_patients']) }}</span>
                    <span
                        class="text-secondary font-bold text-sm mb-1 flex items-center">
                        <span
                            class="material-symbols-outlined scale-75">arrow_upward</span>
                        {{ $stats['patients_diff'] }}%
                    </span>
                </div>
                <p class="mt-2 text-xs text-on-surface-variant">Active
                    monitoring cases</p>
            </div>
            <div
                class="absolute -right-4 -bottom-4 opacity-5 text-blue-700">
                <span
                    class="material-symbols-outlined text-9xl">group</span>
            </div>
        </div>
        <!-- At-Risk Patients Card -->
        <div
            class="col-span-12 md:col-span-4 bg-surface-container-lowest p-8 rounded-xl shadow-sm relative overflow-hidden group border-l-4 border-tertiary">
            <div class="relative z-10">
                <span
                    class="text-sm font-label font-semibold text-on-surface-variant tracking-wider uppercase">At-Risk Patients</span>
                <div class="mt-4 flex items-end gap-2">
                    <span
                        class="text-4xl font-headline font-extrabold text-tertiary">{{ $stats['at_risk_patients'] }}</span>
                    <span
                        class="text-tertiary font-bold text-sm mb-1 flex items-center">
                        <span
                            class="material-symbols-outlined scale-75">warning</span>
                        {{ $stats['at_risk_ratio'] }}%
                    </span>
                </div>
                <p class="mt-2 text-xs text-on-surface-variant">Unacknowledged safety alerts</p>
            </div>
            <div
                class="absolute -right-4 -bottom-4 opacity-5 text-tertiary">
                <span
                    class="material-symbols-outlined text-9xl">priority_high</span>
            </div>
        </div>
        <!-- Compliance Rate -->
        <div
            class="col-span-12 md:col-span-4 primary-gradient p-8 rounded-xl shadow-lg relative overflow-hidden text-white">
            <div class="relative z-10">
                <span
                    class="text-sm font-label font-semibold opacity-80 tracking-wider uppercase">Avg. Fasting Compliance</span>
                <div class="mt-4 flex items-end gap-2">
                    <span
                        class="text-4xl font-headline font-extrabold">{{ $stats['compliance_rate'] }}%</span>
                    <span
                        class="bg-white/20 px-2 py-0.5 rounded text-xs font-bold mb-1">Target
                        90%</span>
                </div>
                <div
                    class="mt-4 w-full bg-white/20 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-white h-full"
                        style="width: {{ $stats['compliance_rate'] }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trends & Critical Alerts Grid -->
    <div class="grid grid-cols-12 gap-8">
        @include('dashboard.partials._chart')

        <!-- Critical Alerts Panel -->
        <div
            class="col-span-12 xl:col-span-4 bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-tertiary/10 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-tertiary"
                        data-weight="fill">error</span>
                    <h2
                        class="text-xl font-headline font-bold text-on-surface">
                        Critical Alerts</h2>
                </div>
                <div class="space-y-4">
                    @forelse($alerts as $alert)
                        <div
                            onclick="window.location.href='{{ route('fgb-monitoring') }}'"
                            class="p-4 rounded-xl bg-tertiary-container/5 flex items-center justify-between group cursor-pointer hover:bg-tertiary-container/10 transition-colors border border-tertiary/5">
                            <div class="flex items-center gap-3">
                                <img alt="Patient Avatar"
                                    class="w-10 h-10 rounded-full object-cover border border-surface-variant/30"
                                    src="{{ $alert['photo'] }}" />
                                <div>
                                    <p
                                        class="text-sm font-bold text-on-surface">
                                        {{ $alert['patient_name'] }}</p>
                                    <p
                                        class="text-xs text-tertiary font-bold mt-0.5">
                                        {{ $alert['value'] }} mg/dL ({{ $alert['status_text'] }})</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-on-surface-variant font-medium">{{ $alert['time_diff'] }}</span>
                                <span
                                    class="material-symbols-outlined text-on-surface-variant group-hover:translate-x-1 transition-transform">chevron_right</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl text-secondary mb-2">check_circle</span>
                            <p class="font-medium text-sm">No critical alerts currently active.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            @if($stats['at_risk_patients'] > 0)
                <button
                    onclick="window.location.href='{{ route('fgb-monitoring') }}'"
                    class="w-full mt-6 py-3 text-sm font-bold text-tertiary bg-tertiary-container/10 rounded-lg hover:bg-tertiary-container/20 transition-colors">
                    View All Alerts ({{ $stats['at_risk_patients'] }})
                </button>
            @endif
        </div>
    </div>

    <!-- Bottom Row: Quick Actions & Recent Activity -->
    <div class="grid grid-cols-12 gap-8">
        <!-- Quick Actions -->
        <div
            class="col-span-12 md:col-span-5 bg-surface-container-low p-8 rounded-xl shadow-sm">
            <h2
                class="text-lg font-headline font-bold text-on-surface mb-6">
                Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <button
                    onclick="window.location.href='{{ route('patients-management') }}'"
                    class="flex flex-col items-center justify-center p-6 bg-surface-container-lowest rounded-xl hover:shadow-md transition-all group scale-95 duration-150 ease-in-out">
                    <span
                        class="material-symbols-outlined text-primary mb-2 scale-125"
                        data-icon="person_add">person_add</span>
                    <span class="text-xs font-bold text-on-surface">Add
                        Patient</span>
                </button>
                <button
                    onclick="window.location.href='{{ route('cms-create') }}'"
                    class="flex flex-col items-center justify-center p-6 bg-surface-container-lowest rounded-xl hover:shadow-md transition-all group scale-95 duration-150 ease-in-out">
                    <span
                        class="material-symbols-outlined text-primary mb-2 scale-125"
                        data-icon="post_add">post_add</span>
                    <span class="text-xs font-bold text-on-surface">New
                        Bulletin</span>
                </button>
                <button
                    onclick="window.location.href='{{ route('patients-management') }}'"
                    class="flex flex-col items-center justify-center p-6 bg-surface-container-lowest rounded-xl hover:shadow-md transition-all group scale-95 duration-150 ease-in-out">
                    <span
                        class="material-symbols-outlined text-primary mb-2 scale-125"
                        data-icon="lab_profile">lab_profile</span>
                    <span
                        class="text-xs font-bold text-on-surface">Bulk
                        Import</span>
                </button>
                <button
                    onclick="window.location.href='{{ route('patients-management') }}'"
                    class="flex flex-col items-center justify-center p-6 bg-surface-container-lowest rounded-xl hover:shadow-md transition-all group scale-95 duration-150 ease-in-out">
                    <span
                        class="material-symbols-outlined text-primary mb-2 scale-125"
                        data-icon="mail">mail</span>
                    <span
                        class="text-xs font-bold text-on-surface">Message
                        All</span>
                </button>
            </div>
        </div>

        <!-- Recent Activity Logs -->
        <div
            class="col-span-12 md:col-span-7 bg-surface-container-lowest p-8 rounded-xl shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <h2
                    class="text-lg font-headline font-bold text-on-surface">
                    Recent System Logs</h2>
                <a class="text-primary text-xs font-bold hover:underline"
                    href="{{ route('fgb-monitoring') }}">View All Logs</a>
            </div>
            <div class="space-y-0 relative">
                @forelse($logs as $log)
                    <!-- Dynamic Timeline Item -->
                    <div class="flex gap-4 pb-6 last:pb-0 relative">
                        <div class="flex flex-col items-center">
                            @php
                                $colorClass = 'primary';
                                if ($log['type'] === 'secondary') $colorClass = 'secondary';
                                if ($log['type'] === 'tertiary') $colorClass = 'tertiary';
                            @endphp
                            <div
                                class="w-3 h-3 rounded-full bg-{{ $colorClass }} ring-4 ring-{{ $colorClass }}/20">
                            </div>
                            @if(!$loop->last)
                                <div
                                    class="w-0.5 flex-1 bg-surface-container-high my-1">
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 pb-2">
                            <div class="flex justify-between">
                                <p
                                    class="text-sm font-bold text-on-surface">
                                    {{ $log['title'] }}</p>
                                <span
                                    class="text-[10px] text-on-surface-variant font-medium">{{ $log['time_diff'] }}</span>
                            </div>
                            <p
                                class="text-xs text-on-surface-variant mt-1">
                                {{ $log['description'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-on-surface-variant">
                        <p class="text-sm font-medium">No recent activities recorded.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
