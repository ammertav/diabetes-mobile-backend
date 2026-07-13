<div class="col-span-12 lg:col-span-4" x-data="{
    get selectedProtocol() {
        return this.protocols.find(p => p.id === this.selectedProtocolId);
    },
    getDayLabel(dayNum) {
        const labels = {
            1: 'Monday',
            2: 'Tuesday',
            3: 'Wednesday',
            4: 'Thursday',
            5: 'Friday',
            6: 'Saturday',
            7: 'Sunday'
        };
        return labels[dayNum] || dayNum;
    }
}">
    <div
        x-show="selectedProtocol"
        class="bg-surface-container-lowest rounded-2xl p-8 shadow-2xl shadow-blue-900/5 sticky top-28">
        <div class="flex items-center justify-between mb-8">
            <h5 class="text-lg font-bold font-headline">Protocol Preview</h5>
            <span class="bg-secondary-fixed text-on-secondary-fixed-variant text-[10px] px-2 py-1 rounded font-bold">LIVE PREVIEW</span>
        </div>
        <div class="mb-8">
            <h4 class="text-2xl font-extrabold text-primary font-headline" x-text="selectedProtocol?.name"></h4>
            <div class="flex items-center gap-2 mt-2">
                <span class="material-symbols-outlined text-sm text-secondary">verified</span>
                <span class="text-xs font-semibold text-secondary font-body">Clinically Validated</span>
            </div>
        </div>
        <div class="space-y-6">
            <section>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Fasting Window</label>
                <div class="flex items-center justify-between bg-surface-container-low p-4 rounded-xl">
                    <div class="text-center">
                        <p class="text-xs text-slate-500 mb-1">Start</p>
                        <p class="font-bold text-on-surface">18:00</p>
                    </div>
                    <div class="h-8 w-px bg-slate-300"></div>
                    <div class="text-center">
                        <p class="text-xs text-slate-500 mb-1">End</p>
                        <p class="font-bold text-on-surface">10:00</p>
                    </div>
                    <div class="h-8 w-px bg-slate-300"></div>
                    <div class="text-center flex flex-col justify-center items-center">
                        <p class="text-xs text-slate-500 mb-1">Duration</p>
                        <p class="font-bold text-primary" x-text="selectedProtocol?.duration_hours + 'h'"></p>
                    </div>
                </div>
            </section>
            
            <!-- Days of Week context -->
            <section>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Active Days</label>
                <div class="flex flex-wrap gap-1.5">
                    <template x-for="day in selectedProtocol?.days" :key="day.day">
                        <span class="bg-primary-container text-on-primary-container text-[11px] px-2.5 py-1 rounded-lg font-bold" x-text="getDayLabel(day.day)"></span>
                    </template>
                </div>
            </section>
            
            <section>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Clinical Description</label>
                <div class="bg-blue-50/50 p-4 rounded-xl border-l-4 border-primary">
                    <p class="text-xs text-blue-900 leading-relaxed font-body italic" x-text="selectedProtocol?.description || 'Tidak ada deskripsi klinis'"></p>
                </div>
            </section>
        </div>
    </div>
    
    <div x-show="!selectedProtocol" class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm text-center text-on-surface-variant font-medium py-16">
        <span class="material-symbols-outlined text-4xl mb-2 text-outline">info</span>
        <p class="text-sm">Silakan pilih protokol di sebelah kiri untuk melihat pratinjau.</p>
    </div>
</div>
