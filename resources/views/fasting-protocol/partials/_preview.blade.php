<div class="col-span-12 lg:col-span-4" x-data="{
    activeEditField: null,
    editForm: { name: '', type: 'sunnah', start_time: '18:00', end_time: '10:00', duration_hours: 12, description: '', days: [] },
    get selectedProtocol() {
        return this.protocols.find(p => p.id === this.selectedProtocolId);
    },
    getDayLabel(dayNum) {
        const labels = { 1: 'Monday', 2: 'Tuesday', 3: 'Wednesday', 4: 'Thursday', 5: 'Friday', 6: 'Saturday', 7: 'Sunday' };
        return labels[dayNum] || dayNum;
    },
    getDayShortLabel(dayNum) {
        const labels = { 1: 'Mon', 2: 'Tue', 3: 'Wed', 4: 'Thu', 5: 'Fri', 6: 'Sat', 7: 'Sun' };
        return labels[dayNum] || dayNum;
    },
    calcDuration(startStr, endStr) {
        if (!startStr || !endStr) return 12;
        const [sH, sM] = startStr.split(':').map(Number);
        const [eH, eM] = endStr.split(':').map(Number);
        if (isNaN(sH) || isNaN(eH)) return 12;
        let diff = (eH * 60 + (eM || 0)) - (sH * 60 + (sM || 0));
        if (diff <= 0) diff += 1440;
        return Math.round(diff / 60);
    },
    openEdit(field) {
        if (!this.selectedProtocol) return;
        this.editForm = {
            name: this.selectedProtocol.name,
            type: this.selectedProtocol.type,
            start_time: this.selectedProtocol.start_time || '18:00',
            end_time: this.selectedProtocol.end_time || '10:00',
            duration_hours: this.selectedProtocol.duration_hours || this.calcDuration(this.selectedProtocol.start_time, this.selectedProtocol.end_time),
            description: this.selectedProtocol.description || '',
            days: this.selectedProtocol.days ? this.selectedProtocol.days.map(d => d.day) : [1, 4]
        };
        this.activeEditField = field;
    },
    closeEdit() {
        this.activeEditField = null;
    },
    toggleDay(dayNum) {
        if (this.editForm.days.includes(dayNum)) {
            if (this.editForm.days.length > 1) {
                this.editForm.days = this.editForm.days.filter(d => d !== dayNum);
            }
        } else {
            this.editForm.days.push(dayNum);
            this.editForm.days.sort((a, b) => a - b);
        }
    }
}">
    <div id="preview-panel" x-show="selectedProtocol" class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm sticky top-20">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6 pb-3 border-b border-slate-100">
            <h5 class="text-base font-bold font-headline text-slate-900">Protocol Preview</h5>
            <span class="bg-emerald-100 text-emerald-800 text-[10px] px-2.5 py-1 rounded-md font-bold uppercase tracking-wider">LIVE PREVIEW</span>
        </div>

        <form x-show="selectedProtocol" :action="'/fasting-protocols/' + selectedProtocol?.id" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Hidden fields -->
            <input type="hidden" name="name" :value="editForm.name">
            <input type="hidden" name="type" :value="editForm.type">
            <input type="hidden" name="start_time" :value="editForm.start_time">
            <input type="hidden" name="end_time" :value="editForm.end_time">
            <input type="hidden" name="duration_hours" :value="editForm.duration_hours">
            <input type="hidden" name="description" :value="editForm.description">
            <template x-for="d in editForm.days" :key="'hday-' + d">
                <input type="hidden" name="days[]" :value="d">
            </template>

            <!-- 1. Title & Type Section -->
            <div>
                <!-- Display Mode -->
                <div x-show="activeEditField !== 'title'" @click="openEdit('title')"
                     class="group p-3 -m-3 rounded-xl border border-transparent hover:border-dashed hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <span :class="selectedProtocol?.type === 'sunnah' ? 'bg-amber-100 text-amber-900' : (selectedProtocol?.type === 'intermittent' ? 'bg-blue-100 text-blue-900' : 'bg-purple-100 text-purple-900')"
                              class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"
                              x-text="selectedProtocol?.type"></span>
                        <span class="material-symbols-outlined text-xs text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">edit</span>
                    </div>
                    <h4 class="text-2xl font-extrabold text-blue-600 font-headline tracking-tight" x-text="selectedProtocol?.name"></h4>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="material-symbols-outlined text-sm text-emerald-600">verified</span>
                        <span class="text-xs font-semibold text-emerald-700 font-body">Clinically Validated</span>
                    </div>
                </div>

                <!-- Form Mode -->
                <div x-show="activeEditField === 'title'" class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div class="flex justify-between items-center text-xs font-bold text-blue-700">
                        <span>Edit Title & Type</span>
                        <button type="button" @click="closeEdit()" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 block mb-1">Judul Protokol</label>
                        <input type="text" x-model="editForm.name" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 block mb-1">Tipe</label>
                        <select x-model="editForm.type" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="sunnah">Sunnah</option>
                            <option value="intermittent">Intermittent</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" @click="closeEdit()" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-1.5 text-xs bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </div>

            <!-- 2. Fasting Window Section -->
            <section>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Fasting Window</label>
                </div>

                <!-- Display Mode -->
                <div x-show="activeEditField !== 'window'" @click="openEdit('window')"
                     class="group flex items-center justify-between bg-slate-50 p-4 rounded-xl border border-slate-100 hover:border-dashed hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                    <div class="text-center"><p class="text-[11px] text-slate-400 font-semibold mb-0.5">Start</p><p class="font-bold text-slate-900 text-sm font-headline" x-text="selectedProtocol?.start_time || '18:00'"></p></div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div class="text-center"><p class="text-[11px] text-slate-400 font-semibold mb-0.5">End</p><p class="font-bold text-slate-900 text-sm font-headline" x-text="selectedProtocol?.end_time || '10:00'"></p></div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div class="text-center flex flex-col justify-center items-center">
                        <p class="text-[11px] text-slate-400 font-semibold mb-0.5">Duration</p>
                        <p class="font-bold text-blue-600 text-sm font-headline" x-text="selectedProtocol?.duration_hours + 'h'"></p>
                    </div>
                    <span class="material-symbols-outlined text-xs text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity ml-1">edit</span>
                </div>

                <!-- Form Mode -->
                <div x-show="activeEditField === 'window'" class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div class="flex justify-between items-center text-xs font-bold text-blue-700">
                        <span>Edit Fasting Window</span>
                        <button type="button" @click="closeEdit()" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 block mb-1">Start Hour</label>
                            <input type="text" x-model="editForm.start_time" @input="editForm.duration_hours = calcDuration(editForm.start_time, editForm.end_time)" placeholder="18:00" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 block mb-1">End Hour</label>
                            <input type="text" x-model="editForm.end_time" @input="editForm.duration_hours = calcDuration(editForm.start_time, editForm.end_time)" placeholder="10:00" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 block mb-1">Duration (Auto Calculated)</label>
                        <input type="number" min="1" max="168" x-model.number="editForm.duration_hours" readonly class="w-full px-3 py-2 text-xs border rounded-lg bg-slate-100 text-slate-700 font-bold outline-none cursor-not-allowed">
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" @click="closeEdit()" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-1.5 text-xs bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </section>

            <!-- 3. Active Days Section -->
            <section>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Active Days</label>
                </div>

                <!-- Display Mode -->
                <div x-show="activeEditField !== 'days'" @click="openEdit('days')"
                     class="group p-3 rounded-xl border border-transparent hover:border-dashed hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                    <div class="flex items-center justify-between flex-wrap gap-1.5">
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="day in selectedProtocol?.days" :key="'pday-' + day.day">
                                <span class="bg-blue-600 text-white text-[11px] px-3 py-1 rounded-lg font-bold shadow-sm" x-text="getDayLabel(day.day)"></span>
                            </template>
                        </div>
                        <span class="material-symbols-outlined text-xs text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">edit</span>
                    </div>
                </div>

                <!-- Form Mode -->
                <div x-show="activeEditField === 'days'" class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div class="flex justify-between items-center text-xs font-bold text-blue-700">
                        <span>Edit Hari Aktif</span>
                        <button type="button" @click="closeEdit()" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div class="grid grid-cols-7 gap-1">
                        <template x-for="d in [1,2,3,4,5,6,7]" :key="'btn-d-' + d">
                            <button type="button" @click="toggleDay(d)"
                                    :class="editForm.days.includes(d) ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-600 border'"
                                    class="py-2 text-xs rounded-lg transition-colors text-center"
                                    x-text="getDayShortLabel(d)">
                            </button>
                        </template>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" @click="closeEdit()" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-1.5 text-xs bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </section>

            <!-- 4. Clinical Description Section -->
            <section>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Clinical Description</label>
                </div>

                <!-- Display Mode -->
                <div x-show="activeEditField !== 'description'" @click="openEdit('description')"
                     class="group bg-slate-50 p-4 rounded-xl border-l-4 border-blue-600 border-transparent hover:border-dashed hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer relative">
                    <div class="flex justify-between items-start">
                        <p class="text-xs text-slate-700 leading-relaxed font-body italic pr-4" x-text="selectedProtocol?.description || 'Tidak ada deskripsi klinis'"></p>
                        <span class="material-symbols-outlined text-xs text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">edit</span>
                    </div>
                </div>

                <!-- Form Mode -->
                <div x-show="activeEditField === 'description'" class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div class="flex justify-between items-center text-xs font-bold text-blue-700">
                        <span>Edit Deskripsi Klinis</span>
                        <button type="button" @click="closeEdit()" class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div>
                        <textarea rows="3" x-model="editForm.description" class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan deskripsi..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" @click="closeEdit()" class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-1.5 text-xs bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <div x-show="!selectedProtocol" class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm text-center text-slate-400 font-medium py-16 sticky top-20">
        <span class="material-symbols-outlined text-4xl mb-2">info</span>
        <p class="text-xs">Silakan pilih protokol di sebelah kiri untuk melihat pratinjau.</p>
    </div>
</div>
