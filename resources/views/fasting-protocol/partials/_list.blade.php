<div class="col-span-12 lg:col-span-8 space-y-6">
    <!-- Filter Bar -->
    <div class="flex items-center justify-between bg-surface-container-lowest p-4 rounded-2xl shadow-sm">
        <div class="flex gap-2">
            <button
                type="button"
                @click="selectedType = 'all'"
                :class="selectedType === 'all' ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high'"
                class="px-4 py-2 rounded-full text-sm font-bold transition-all">
                All
            </button>
            <button
                type="button"
                @click="selectedType = 'sunnah'"
                :class="selectedType === 'sunnah' ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high'"
                class="px-4 py-2 rounded-full text-sm font-bold transition-all">
                Sunnah
            </button>
            <button
                type="button"
                @click="selectedType = 'intermittent'"
                :class="selectedType === 'intermittent' ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high'"
                class="px-4 py-2 rounded-full text-sm font-bold transition-all">
                Intermittent
            </button>
            <button
                type="button"
                @click="selectedType = 'custom'"
                :class="selectedType === 'custom' ? 'bg-primary text-white' : 'text-on-surface-variant hover:bg-surface-container-high'"
                class="px-4 py-2 rounded-full text-sm font-bold transition-all">
                Custom
            </button>
        </div>
    </div>
    
    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <template x-for="protocol in protocols" :key="protocol.id">
            <div
                x-show="selectedType === 'all' || protocol.type === selectedType"
                @click="selectedProtocolId = protocol.id"
                :class="selectedProtocolId === protocol.id ? 'border-primary shadow-lg scale-[1.01]' : 'border-transparent hover:border-primary/10 hover:shadow-md'"
                class="bg-surface-container-lowest p-6 rounded-2xl border transition-all cursor-pointer flex flex-col justify-between min-h-[220px]">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <span
                            :class="protocol.type === 'sunnah' ? 'bg-amber-100 text-amber-800' : (protocol.type === 'intermittent' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800')"
                            class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                            x-text="protocol.type">
                        </span>
                        
                        <!-- Delete Button -->
                        <button type="button" 
                            @click.stop="deleteActionUrl = '/fasting-protocols/' + protocol.id; showDeleteModal = true"
                            class="text-slate-300 hover:text-error transition-colors">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                    <h4 class="text-lg font-bold text-on-surface mb-2 font-headline" x-text="protocol.name"></h4>
                    <p class="text-on-surface-variant text-xs mb-4 font-body line-clamp-2" x-text="protocol.description || 'Tidak ada deskripsi'"></p>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-auto">
                    <div class="bg-surface-container-low p-3 rounded-xl">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Duration</p>
                        <p class="text-sm font-bold text-blue-800" x-text="protocol.duration_hours + ' Hours'"></p>
                    </div>
                    <div class="bg-surface-container-low p-3 rounded-xl">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Days</p>
                        <p class="text-sm font-bold text-blue-800" x-text="protocol.days.length + ' Days'"></p>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
