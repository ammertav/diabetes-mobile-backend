<div class="col-span-12 lg:col-span-8">
    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <template x-for="protocol in protocols" :key="protocol.id">
            <div
                x-show="(selectedType === 'all' || protocol.type === selectedType) && (!searchQuery || protocol.name.toLowerCase().includes(searchQuery.toLowerCase()) || (protocol.description && protocol.description.toLowerCase().includes(searchQuery.toLowerCase())))"
                @click="selectedProtocolId = protocol.id; $nextTick(() => { document.getElementById('preview-panel')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); })"
                :class="selectedProtocolId === protocol.id ? 'ring-2 ring-blue-600 shadow-md scale-[1.01]' : 'shadow-sm hover:shadow-md border border-slate-100'"
                class="bg-white p-6 rounded-2xl transition-all cursor-pointer flex flex-col justify-between min-h-[240px]">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <span
                            :class="protocol.type === 'sunnah' ? 'bg-amber-100 text-amber-900' : (protocol.type === 'intermittent' ? 'bg-blue-100 text-blue-900' : 'bg-purple-100 text-purple-900')"
                            class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider"
                            x-text="protocol.type">
                        </span>

                        <!-- Action Buttons: Edit & Delete -->
                        <div class="flex items-center gap-1">
                            <button type="button"
                                @click.stop="selectedProtocolId = protocol.id; openEdit('title'); $nextTick(() => { document.getElementById('preview-panel')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); })"
                                class="text-slate-400 hover:text-blue-600 transition-colors p-1"
                                title="Edit Protocol">
                                <span class="material-symbols-outlined text-xl">edit</span>
                            </button>
                            <button type="button"
                                @click.stop="deleteActionUrl = '/fasting-protocols/' + protocol.id; showDeleteModal = true"
                                class="text-slate-400 hover:text-rose-600 transition-colors p-1"
                                title="Delete Protocol">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </div>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2 font-headline" x-text="protocol.name"></h4>
                    <p class="text-slate-500 text-xs mb-4 font-body line-clamp-2 leading-relaxed" x-text="protocol.description || 'Tidak ada deskripsi'"></p>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-auto pt-4 border-t border-slate-100">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Duration</p>
                        <p class="text-sm font-bold text-blue-600 font-headline" x-text="protocol.duration_hours + ' Hours'"></p>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Days</p>
                        <p class="text-sm font-bold text-blue-600 font-headline" x-text="(protocol.days ? protocol.days.length : 0) + ' Days'"></p>
                    </div>
                </div>
            </div>
        </template>

        <!-- Empty Search Result State -->
        <div x-show="protocols.filter(p => (selectedType === 'all' || p.type === selectedType) && (!searchQuery || p.name.toLowerCase().includes(searchQuery.toLowerCase()) || (p.description && p.description.toLowerCase().includes(searchQuery.toLowerCase())))).length === 0"
             class="col-span-full py-16 flex flex-col items-center justify-center text-slate-400 bg-white rounded-2xl border border-slate-100 shadow-sm">
            <span class="material-symbols-outlined text-5xl mb-2">style</span>
            <p class="font-medium text-xs">Tidak ada protokol puasa yang sesuai kriteria pencarian.</p>
        </div>
    </div>
</div>
