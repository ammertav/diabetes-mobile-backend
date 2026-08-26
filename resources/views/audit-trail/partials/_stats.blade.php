<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Total Events -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Aktivitas System</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">history</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-slate-900 font-headline tracking-tight" x-text="stats.total_events || 0">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Log tercatat dalam sistem</p>
        </div>
    </div>

    <!-- Card 2: Admin Actions -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Aksi Admin / Klinisi</span>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">admin_panel_settings</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-blue-600 font-headline tracking-tight" x-text="stats.admin_actions || 0">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Perubahan protokol & CMS</p>
        </div>
    </div>

    <!-- Card 3: Patient Actions -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Aksi Pasien</span>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">person_pin</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-emerald-600 font-headline tracking-tight" x-text="stats.patient_actions || 0">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Check-in & log puasa</p>
        </div>
    </div>

    <!-- Card 4: Security Alerts -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between h-44 transition-all hover:shadow-md">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Peringatan Keamanan</span>
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">verified_user</span>
            </div>
        </div>
        <div>
            <h3 class="text-4xl font-extrabold text-slate-900 font-headline tracking-tight" x-text="stats.security_alerts || 0">0</h3>
            <p class="text-xs font-medium text-slate-500 mt-1">Insiden keamanan</p>
        </div>
    </div>
</div>
