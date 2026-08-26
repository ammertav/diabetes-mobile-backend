@extends('layouts.app')

@section('content')
    <div x-data="safetyAlertsPage()" x-init="init()" class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 font-headline">Pusat Kontrol Alert Darurat</h1>
                <p class="text-sm text-slate-500">Monitor hipoglikemia & hiperglikemia kritis, serta kirim instruksi darurat langsung via FCM.</p>
            </div>
        </div>

        @include('safety-alerts.partials._stats')

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
            <!-- Filters & Search -->
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex gap-2">
                    <button class="px-4 py-2 text-sm font-semibold rounded-lg transition-all"
                        :class="status === 'all' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                        @click="setStatus('all')">All Alerts</button>
                    <button class="px-4 py-2 text-sm font-semibold rounded-lg transition-all"
                        :class="status === 'unresolved' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                        @click="setStatus('unresolved')">Unresolved</button>
                    <button class="px-4 py-2 text-sm font-semibold rounded-lg transition-all"
                        :class="status === 'resolved' ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800'"
                        @click="setStatus('resolved')">Resolved</button>
                </div>

                <div class="relative w-full md:w-72">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-xl">search</span>
                    <input type="text" placeholder="Search by patient name..." 
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        x-model="search" @input.debounce.300ms="loadAlerts()">
                </div>
            </div>

            <!-- Table -->
            @include('safety-alerts.partials._table')

            <!-- Pagination -->
            <div class="p-6 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center" x-show="pagination.last_page > 1">
                <span class="text-xs text-slate-500" x-text="`Page ${pagination.current_page} of ${pagination.last_page}`"></span>
                <div class="flex gap-2">
                    <button class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 disabled:opacity-50"
                        :disabled="pagination.current_page === 1"
                        @click="loadAlerts(pagination.current_page - 1)">Previous</button>
                    <button class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 disabled:opacity-50"
                        :disabled="pagination.current_page === pagination.last_page"
                        @click="loadAlerts(pagination.current_page + 1)">Next</button>
                </div>
            </div>
        </div>

        <!-- Custom Confirm Modal -->
        <div x-show="confirmModal.show" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm" style="display: none;" @keydown.escape.window="confirmModal.show = false">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-xl max-w-sm w-full border border-slate-100 dark:border-slate-800 text-center space-y-4" @click.away="confirmModal.show = false">
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-2xl font-bold">help</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 font-headline" x-text="confirmModal.title">Confirm Action</h3>
                    <p class="text-sm text-slate-500 mt-1" x-text="confirmModal.message">Are you sure you want to proceed?</p>
                </div>
                <div class="flex gap-3 justify-center pt-2">
                    <button class="px-4 py-2 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 transition-all"
                        @click="confirmModal.show = false">Batal</button>
                    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md"
                        @click="confirmModal.onConfirm()">Kirim</button>
                </div>
            </div>
        </div>

        <!-- Custom Toast Alert -->
        <div x-show="toast.show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold transition-all"
            :class="{
                'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-950 dark:border-emerald-900 dark:text-emerald-300': toast.type === 'success',
                'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-950 dark:border-rose-900 dark:text-rose-300': toast.type === 'error',
                'bg-slate-50 border-slate-200 text-slate-800 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-300': toast.type === 'info'
            }"
            style="display: none;">
            <span class="material-symbols-outlined text-xl" x-text="toast.type === 'success' ? 'check_circle' : (toast.type === 'error' ? 'error' : 'info')"></span>
            <span x-text="toast.message"></span>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function safetyAlertsPage() {
            return {
                loading: false,
                alerts: [],
                pagination: {},
                stats: {
                    total: 0,
                    unresolved: 0,
                    severe: 0,
                    resolved: 0
                },
                search: '',
                status: 'unresolved',

                // Custom Feedback UI State
                toast: {
                    show: false,
                    message: '',
                    type: 'success'
                },
                confirmModal: {
                    show: false,
                    title: '',
                    message: '',
                    onConfirm: () => {}
                },

                showToast(message, type = 'success') {
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.show = true;
                    setTimeout(() => {
                        this.toast.show = false;
                    }, 4000);
                },

                async init() {
                    await this.loadAlerts();
                },

                async loadAlerts(page = 1) {
                    try {
                        this.loading = true;
                        const params = new URLSearchParams({
                            page: page,
                            search: this.search,
                            status: this.status
                        });
                        const response = await fetch(`/safety-alerts/data?${params.toString()}`);
                        const result = await response.json();

                        this.alerts = result.alerts;
                        this.pagination = result.pagination;
                        this.stats = result.stats;
                    } catch (error) {
                        console.error('Failed to load safety alerts:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                setStatus(newStatus) {
                    this.status = newStatus;
                    this.loadAlerts();
                },

                notifyPatient(alertId) {
                    this.confirmModal.title = 'Kirim Notifikasi FCM';
                    this.confirmModal.message = 'Apakah Anda yakin ingin mengirim notifikasi push FCM darurat ke pasien ini?';
                    this.confirmModal.show = true;
                    this.confirmModal.onConfirm = async () => {
                        this.confirmModal.show = false;
                        await this.executeNotifyPatient(alertId);
                    };
                },

                async executeNotifyPatient(alertId) {
                    try {
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const response = await fetch(`/safety-alerts/${alertId}/notify`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        });
                        const result = await response.json();
                        if (result.success) {
                            this.showToast(result.message, 'success');
                            await this.loadAlerts(this.pagination.current_page);
                        } else {
                            this.showToast('Gagal mengirim notifikasi.', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        this.showToast('Terjadi kesalahan koneksi.', 'error');
                    }
                }
            }
        }
    </script>
@endpush
