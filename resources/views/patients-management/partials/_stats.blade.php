<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div
        class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
        <div class="flex items-center justify-between">
            <div class="p-3 bg-primary-fixed rounded-full">
                <span
                    class="material-symbols-outlined text-primary text-2xl"
                    style="font-variation-settings: 'FILL' 1;">group</span>
            </div>
        </div>
        <div>
            <p
                class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                Total Patients</p>
            <h3 class="font-headline font-bold text-4xl text-on-surface"
                x-text="total_patients">
            </h3>
        </div>
    </div>
    <div
        class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
        <div class="flex items-center justify-between">
            <div class="p-3 bg-secondary-container rounded-full">
                <span
                    class="material-symbols-outlined text-secondary text-2xl"
                    style="font-variation-settings: 'FILL' 1;">timer</span>
            </div>
        </div>
        <div>
            <p
                class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                Fasting Protocol</p>
            <h3 class="font-headline font-bold text-4xl text-on-surface"
                x-text="protocol_patients">
            </h3>
        </div>
    </div>
    <div
        class="bg-surface-container-lowest p-8 rounded-xl flex flex-col gap-4 shadow-sm border-none">
        <div class="flex items-center justify-between">
            <div class="p-3 bg-tertiary-container/10 rounded-full">
                <span
                    class="material-symbols-outlined text-tertiary text-2xl"
                    style="font-variation-settings: 'FILL' 1;">warning</span>
            </div>
        </div>
        <div>
            <p
                class="text-on-surface-variant font-semibold text-xs tracking-widest uppercase mb-1">
                High Risk Patients</p>
            <h3 class="font-headline font-bold text-4xl text-tertiary"
                x-text="high_risk_patients">
            </h3>
        </div>
    </div>
</div>
