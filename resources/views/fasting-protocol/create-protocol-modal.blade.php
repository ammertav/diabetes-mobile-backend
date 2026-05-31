<div class="hidden fixed inset-0 z-100 items-center justify-center bg-black/50 p-6"
    id="create-protocol-modal">
    <div
        class="bg-white dark:bg-slate-900 w-full max-w-4xl max-h-[90vh] rounded-3xl shadow-2xl overflow-hidden flex flex-col">
        <!-- Modal Header -->
        <div
            class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <div>
                <h3
                    class="text-2xl font-bold font-manrope text-slate-900 dark:text-white">
                    Create New Protocol</h3>
                <p class="text-sm text-slate-500 font-inter">Define a new fasting
                    regimen for clinical use</p>
            </div>
            <button
                class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition-colors text-slate-400 hover:text-slate-600"
                onclick="closeModal('create-protocol-modal')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <!-- Modal Body -->
        <div class="flex-1 p-8 space-y-6 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Protocol Name -->
                <div class="col-span-2 md:col-span-1">
                    <label
                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Protocol
                        Name</label>
                    <input
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-primary/40 text-sm font-medium transition-all"
                        placeholder="e.g., Intermittent 18:6" type="text" />
                </div>
                <!-- Duration Selection -->
                <div class="col-span-2 md:col-span-1">
                    <label
                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Fasting
                        Window (Hours)</label>
                    <div class="relative">
                        <input
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-primary/40 text-sm font-medium transition-all"
                            max="168" min="1" placeholder="18"
                            type="number" />
                        <span
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">hours</span>
                    </div>
                </div>
                <!-- Description -->
                <div class="col-span-2">
                    <label
                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Goal
                        / Description</label>
                    <textarea
                        class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border-none rounded-xl focus:ring-2 focus:ring-primary/40 text-sm font-medium transition-all"
                        placeholder="Primary health objective..." rows="2"></textarea>
                </div>
                <!-- Intake Guidelines -->
                <div class="col-span-2 space-y-4">
                    <label
                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest px-1">Intake
                        Guidelines</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            class="p-4 rounded-2xl bg-secondary/5 border border-secondary/20">
                            <p
                                class="text-xs font-bold text-secondary mb-3 flex items-center gap-2">
                                <span
                                    class="material-symbols-outlined text-sm">check_circle</span>
                                ALLOWED
                            </p>
                            <textarea
                                class="w-full bg-transparent border-none p-0 text-xs text-slate-600 focus:ring-0 italic"
                                placeholder="List allowed liquids/supplements..." rows="3"></textarea>
                        </div>
                        <div
                            class="p-4 rounded-2xl bg-error/5 border border-error/20">
                            <p
                                class="text-xs font-bold text-error mb-3 flex items-center gap-2">
                                <span
                                    class="material-symbols-outlined text-sm">cancel</span>
                                PROHIBITED
                            </p>
                            <textarea
                                class="w-full bg-transparent border-none p-0 text-xs text-slate-600 focus:ring-0 italic"
                                placeholder="List restricted foods/beverages..." rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <!-- Clinical Notes -->
                <div class="col-span-2">
                    <label
                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Clinical
                        Rationale</label>
                    <textarea
                        class="w-full px-4 py-3 bg-blue-50/30 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-xl focus:ring-2 focus:ring-primary/40 text-sm font-medium transition-all italic text-blue-900/70 dark:text-blue-200/70"
                        placeholder="Add specialized medical instructions or notes..."
                        rows="3"></textarea>
                </div>
                <!-- Status Toggle -->
                <div
                    class="col-span-2 flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl">
                    <div>
                        <p
                            class="text-sm font-bold text-slate-900 dark:text-white">
                            Initial Status</p>
                        <p class="text-xs text-slate-500">Set visibility for
                            clinicians</p>
                    </div>
                    <div
                        class="flex bg-white dark:bg-slate-900 p-1 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">

                        <button
                            type="button"
                            id="btn-draft"
                            onclick="selectStatus('draft')"
                            class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold">
                            Draft
                        </button>

                        <button
                            type="button"
                            id="btn-active"
                            onclick="selectStatus('active')"
                            class="px-4 py-2 text-slate-500 rounded-lg text-xs font-bold">
                            Active
                        </button>

                    </div>

                    <input
                        type="hidden"
                        name="status"
                        id="protocol-status"
                        value="draft">
                </div>
            </div>
        </div>
        <!-- Modal Footer -->
        <div
            class="sticky bottom-0 px-8 py-6 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 flex gap-3">
            <button
                class="flex-1 py-4 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-white transition-colors"
                onclick="closeModal('create-protocol-modal')">Cancel</button>
            <button
                class="flex-1 py-4 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200/50 hover:bg-blue-700 transition-all">Create
                Protocol</button>
        </div>
    </div>
</div>
<script>
    function selectStatus(status) {
        document.getElementById('protocol-status').value = status;

        const draftBtn = document.getElementById('btn-draft');
        const activeBtn = document.getElementById('btn-active');

        draftBtn.classList.remove(
            'bg-primary',
            'text-white'
        );

        activeBtn.classList.remove(
            'bg-primary',
            'text-white'
        );

        draftBtn.classList.add(
            'text-slate-500'
        );

        activeBtn.classList.add(
            'text-slate-500'
        );

        if (status === 'draft') {
            draftBtn.classList.add(
                'bg-primary',
                'text-white'
            );
            draftBtn.classList.remove(
                'text-slate-500'
            );
        } else {
            activeBtn.classList.add(
                'bg-primary',
                'text-white'
            );
            activeBtn.classList.remove(
                'text-slate-500'
            );
        }
    }
</script>
