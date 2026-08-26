<form method="GET" action="{{ route('cms') }}" class="p-6 bg-white rounded-2xl shadow-sm border border-slate-100 flex flex-wrap items-center justify-between gap-4 mb-8">
    <!-- Search Input -->
    <div class="relative flex-1 min-w-[280px]">
        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-outline">
            <span class="material-symbols-outlined">search</span>
        </div>
        <input
            name="search"
            value="{{ request('search') }}"
            class="w-full bg-surface-container-low border-none rounded-xl pl-10 pr-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary/20 transition-all outline-none"
            placeholder="Cari judul atau ID konten..." type="text"
            onchange="this.form.submit()" />
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <!-- Content Type Dropdown -->
        <select
            name="type"
            onchange="this.form.submit()"
            class="bg-surface-container-low border-none rounded-xl pl-4 pr-10 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
            <option value="">Semua Tipe Konten</option>
            @foreach(App\Enums\CmsContentType::cases() as $type)
                <option value="{{ $type->value }}" {{ request('type') == $type->value ? 'selected' : '' }}>
                    {{ $type->label() }}
                </option>
            @endforeach
        </select>

        <!-- Day Context Dropdown -->
        <select
            name="day_context"
            onchange="this.form.submit()"
            class="bg-surface-container-low border-none rounded-xl pl-4 pr-10 py-3 text-sm font-medium text-on-surface-variant focus:ring-2 focus:ring-primary/20 outline-none cursor-pointer">
            <option value="">Semua Konteks Hari</option>
            @foreach(App\Enums\CmsDayContext::cases() as $context)
                <option value="{{ $context->value }}" {{ request('day_context') == $context->value ? 'selected' : '' }}>
                    {{ $context->label() }}
                </option>
            @endforeach
        </select>

        @if(request('search') || request('type') || request('day_context'))
            <a href="{{ route('cms') }}" class="bg-surface-container-high p-3 rounded-xl text-on-surface-variant hover:bg-surface-variant transition-colors" title="Reset Filters">
                <span class="material-symbols-outlined">restart_alt</span>
            </a>
        @endif

        <!-- Create CMS Button in Filter Section -->
        <a href="{{ route('cms-create') }}"
           class="bg-blue-600 text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-xl shadow-md shadow-blue-600/20 hover:bg-blue-700 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-sm">add</span>
            Buat CMS Baru
        </a>
    </div>
</form>
