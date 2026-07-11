<div
    class="p-6 bg-surface-container-lowest border-b border-surface-container flex flex-wrap items-center justify-between gap-4">
    <div class="flex items-center gap-4 flex-1 min-w-75">
        <div class="relative flex-1">
            <input
                x-model="search"
                @input.debounce.300ms="loadPatients(1)"
                class="w-full bg-surface-container-low border-none rounded-xl px-10 py-3 text-sm focus:ring-2 focus:ring-primary/20"
                placeholder="Search by name, email or protocol..."
                type="text" />
            <span
                class="material-symbols-outlined absolute left-3 top-3 text-outline">search</span>
        </div>
        <button
            @click="search = ''; risk = 'all'; protocol = 'all'; date = ''; loadPatients(1);"
            class="bg-surface-container-high p-3 rounded-xl text-on-surface-variant hover:bg-surface-variant transition-colors"
            title="Reset Filters">
            <span class="material-symbols-outlined">restart_alt</span>
        </button>
    </div>
    <div class="flex items-center gap-3">
        <select
            x-model="risk"
            @change="loadPatients(1)"
            class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20">
            <option value="all">All Risk Levels</option>
            <option value="high">High Risk</option>
            <option value="medium">Medium Risk</option>
            <option value="low">Low Risk</option>
        </select>
        <select
            x-model="protocol"
            @change="loadPatients(1)"
            class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20">
            <option value="all">All Protocols</option>
            @foreach($protocols as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
        <input
            x-model="date"
            @change="loadPatients(1)"
            class="bg-surface-container-low border-none rounded-xl px-4 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20"
            type="date" />
    </div>
</div>
