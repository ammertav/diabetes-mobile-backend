<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Patients</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">group</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-slate-900 font-headline tracking-tight" x-text="total_patients">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Terdaftar dalam sistem</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Fasting Protocol</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">timer</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-emerald-600 font-headline tracking-tight" x-text="protocol_patients">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Pasien aktif puasa</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">High Risk Patients</span>
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">warning</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-rose-600 font-headline tracking-tight" x-text="high_risk_patients">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Perlu pemantauan klinis</p>
        </div>
    </div>
</div>
