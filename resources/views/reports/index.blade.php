@extends('layouts.app')

@section('content')
    <div x-data="{ toast: { show: false, message: '', type: 'error' } }" class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 font-headline">Laporan Kesehatan Pasien</h1>
            <p class="text-sm text-slate-500">Pilih pasien dan rentang tanggal untuk mencetak laporan klinis komparatif.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <form action="#" method="GET" id="report-form" class="space-y-6" @submit.prevent="generateReport($data)">
                <!-- Patient Selector -->
                <div>
                    <label for="patient_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih Pasien</label>
                    
                    <div x-data="{
                        open: false,
                        search: '',
                        options: [],
                        selected: { id: '', text: '' },
                        get filteredOptions() {
                            if (!this.search) return this.options;
                            return this.options.filter(o => o.text.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        select(opt) {
                            this.selected = opt;
                            this.open = false;
                            this.search = '';
                        }
                    }" x-init="options = {{ \Illuminate\Support\Js::from($patients->map(fn($p) => ['id' => $p->id, 'text' => ($p->mobileProfile->name ?? $p->email) . ' (' . $p->email . ')'])) }}" class="relative">
                        <input type="hidden" name="patient_id" id="patient_id" :value="selected.id">
                        <button type="button" @click="open = !open" 
                            class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-left flex justify-between items-center transition-all">
                            <span x-text="selected.text || '-- Pilih Pasien --'" :class="selected.id ? 'text-slate-800 dark:text-slate-200 font-medium' : 'text-slate-400'"></span>
                            <span class="material-symbols-outlined text-slate-400">arrow_drop_down</span>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute z-50 w-full mt-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-lg max-h-60 overflow-y-auto p-2 space-y-2"
                            style="display: none;">
                            <input type="text" placeholder="Cari nama atau email..." x-model="search" 
                                class="w-full px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg text-xs bg-slate-50 dark:bg-slate-850 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <div class="max-h-40 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/50 mt-1">
                                <template x-for="opt in filteredOptions" :key="opt.id">
                                    <button type="button" @click="select(opt)" 
                                        class="w-full text-left px-3 py-2 text-xs rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-blue-700 dark:hover:text-blue-400 transition-all font-medium block text-slate-700 dark:text-slate-300"
                                        x-text="opt.text"></button>
                                </template>
                                <div x-show="filteredOptions.length === 0" class="text-center py-4 text-xs text-slate-400">Pasien tidak ditemukan.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Ranges -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" required 
                            value="{{ now()->subDays(30)->toDateString() }}"
                            class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" required 
                            value="{{ now()->toDateString() }}"
                            class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50 dark:bg-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4">
                    <button type="submit" 
                        class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">print</span>
                        Buka & Cetak Laporan
                    </button>
                </div>
            </form>
        </div>

        <!-- Custom Toast Alert -->
        <div x-show="toast.show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-sm font-semibold transition-all bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-950 dark:border-rose-900 dark:text-rose-300"
            style="display: none;">
            <span class="material-symbols-outlined text-xl">error</span>
            <span x-text="toast.message"></span>
        </div>
    </div>

    <script>
        function generateReport(alpineScope) {
            const patientId = document.getElementById('patient_id').value;
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            if (!patientId || !startDate || !endDate) {
                // Show Custom Toast
                alpineScope.toast.message = 'Mohon lengkapi semua isian formulir.';
                alpineScope.toast.show = true;
                setTimeout(() => {
                    alpineScope.toast.show = false;
                }, 4000);
                return;
            }

            const url = `/reports/patient/${patientId}?start_date=${startDate}&end_date=${endDate}`;
            window.open(url, '_blank');
        }
    </script>
@endsection
