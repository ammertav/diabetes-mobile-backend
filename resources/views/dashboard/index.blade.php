@extends('layouts.app')

@section('content')
    <!-- Hero Metrics: Fasting Log / Audit Trail Token Standard -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Patients Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Patients</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">group</span>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-slate-900 font-headline tracking-tight">{{ number_format($stats['total_patients']) }}</h3>
                    <span class="text-emerald-600 font-bold text-xs flex items-center">
                        <span class="material-symbols-outlined text-xs">arrow_upward</span>
                        {{ $stats['patients_diff'] }}%
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-500 mt-1">Active monitoring cases</p>
            </div>
        </div>

        <!-- At-Risk Patients Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">At-Risk Patients</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">warning</span>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-rose-600 font-headline tracking-tight">{{ $stats['at_risk_patients'] }}</h3>
                    <span class="text-rose-600 font-bold text-xs">
                        {{ $stats['at_risk_ratio'] }}%
                    </span>
                </div>
                <p class="text-xs font-medium text-slate-500 mt-1">Unacknowledged safety alerts</p>
            </div>
        </div>

        <!-- Fasting Compliance -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Avg. Fasting Compliance</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">analytics</span>
                </div>
            </div>
            <div>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-extrabold text-emerald-600 font-headline tracking-tight">{{ $stats['compliance_rate'] }}%</h3>
                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[10px] font-bold">Target 90%</span>
                </div>
                <div class="w-full bg-slate-100 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-emerald-500 h-full" style="width: {{ $stats['compliance_rate'] }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trends & Critical Alerts Grid -->
    <div class="grid grid-cols-12 gap-8 mb-8">
        @include('dashboard.partials._chart')

        <!-- Critical Alerts Panel -->
        <div class="col-span-12 xl:col-span-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <h2 class="text-base font-headline font-bold text-slate-900">Critical Alerts</h2>
                </div>
                <div class="space-y-3">
                    @forelse($alerts as $alert)
                        <div onclick="window.location.href='{{ route('fgb-monitoring') }}'"
                             class="p-3.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors flex items-center justify-between group cursor-pointer border border-slate-100">
                            <div class="flex items-center gap-3">
                                <img alt="Patient Avatar"
                                     class="w-9 h-9 rounded-full object-cover border border-slate-200"
                                     src="{{ $alert['photo'] }}" />
                                <div>
                                    <p class="text-xs font-bold text-slate-900 font-headline">{{ $alert['patient_name'] }}</p>
                                    <p class="text-[11px] text-rose-600 font-bold mt-0.5">{{ $alert['value'] }} mg/dL ({{ $alert['status_text'] }})</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] text-slate-400 font-medium">{{ $alert['time_diff'] }}</span>
                                <span class="material-symbols-outlined text-slate-400 group-hover:translate-x-1 transition-transform text-sm">chevron_right</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl text-emerald-500 mb-2">check_circle</span>
                            <p class="font-medium text-xs">No critical alerts currently active.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($stats['at_risk_patients'] > 0)
                <button onclick="window.location.href='{{ route('fgb-monitoring') }}'"
                        class="w-full mt-6 py-2.5 text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors">
                    View All Alerts ({{ $stats['at_risk_patients'] }})
                </button>
            @endif
        </div>
    </div>

    <!-- Bottom Row: Quick Actions & Recent Activity -->
    <div class="grid grid-cols-12 gap-8">
        <!-- Quick Actions -->
        <div class="col-span-12 md:col-span-5 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h2 class="text-base font-headline font-bold text-slate-900 mb-6 pb-3 border-b border-slate-100">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <button onclick="window.location.href='{{ route('patients-management') }}'"
                        class="flex flex-col items-center justify-center p-5 bg-slate-50 hover:bg-blue-50 border border-slate-100 rounded-xl transition-all group">
                    <span class="material-symbols-outlined text-blue-600 mb-2 text-2xl group-hover:scale-110 transition-transform">person_add</span>
                    <span class="text-xs font-bold text-slate-700">Add Patient</span>
                </button>
                <button onclick="window.location.href='{{ route('cms-create') }}'"
                        class="flex flex-col items-center justify-center p-5 bg-slate-50 hover:bg-blue-50 border border-slate-100 rounded-xl transition-all group">
                    <span class="material-symbols-outlined text-blue-600 mb-2 text-2xl group-hover:scale-110 transition-transform">post_add</span>
                    <span class="text-xs font-bold text-slate-700">New Bulletin</span>
                </button>
                <button onclick="window.location.href='{{ route('fasting-protocols-create') }}'"
                        class="flex flex-col items-center justify-center p-5 bg-slate-50 hover:bg-blue-50 border border-slate-100 rounded-xl transition-all group">
                    <span class="material-symbols-outlined text-blue-600 mb-2 text-2xl group-hover:scale-110 transition-transform">style</span>
                    <span class="text-xs font-bold text-slate-700">New Protocol</span>
                </button>
                <button onclick="window.location.href='{{ route('audit-trail') }}'"
                        class="flex flex-col items-center justify-center p-5 bg-slate-50 hover:bg-blue-50 border border-slate-100 rounded-xl transition-all group">
                    <span class="material-symbols-outlined text-blue-600 mb-2 text-2xl group-hover:scale-110 transition-transform">history</span>
                    <span class="text-xs font-bold text-slate-700">Audit Logs</span>
                </button>
            </div>
        </div>

        <!-- Recent Activity Logs -->
        <div class="col-span-12 md:col-span-7 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100">
                <h2 class="text-base font-headline font-bold text-slate-900">Recent System Logs</h2>
                <a class="text-blue-600 text-xs font-bold hover:underline" href="{{ route('audit-trail') }}">View All Audit Logs</a>
            </div>
            <div class="space-y-4">
                @forelse($logs as $log)
                    <div class="flex gap-4 pb-4 last:pb-0 border-b border-slate-100 last:border-none">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-sm">history</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <p class="text-xs font-bold text-slate-900 font-headline">{{ $log['title'] }}</p>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $log['time_diff'] }}</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $log['description'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-slate-400">
                        <p class="text-xs font-medium">No recent activities recorded.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
