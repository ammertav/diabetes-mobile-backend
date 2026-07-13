<form method="GET" action="{{ route('cms') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="md:col-span-2 relative">
        <div
            class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-on-surface-variant">
            <span class="material-symbols-outlined text-xl">search</span>
        </div>
        <input
            name="search"
            value="{{ request('search') }}"
            class="w-full pl-12 pr-4 py-3 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all placeholder:text-on-surface-variant/60 text-sm text-on-surface font-medium"
            placeholder="Cari judul atau ID konten..." type="text"
            onchange="this.form.submit()" />
    </div>
    <div class="relative group">
        <select
            name="type"
            onchange="this.form.submit()"
            class="w-full pl-4 pr-10 py-3 bg-surface-container-highest border-none rounded-xl appearance-none bg-none focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all cursor-pointer text-sm text-on-surface-variant font-medium">
            <option value="">Semua Tipe Konten</option>
            @foreach(App\Enums\CmsContentType::cases() as $type)
                <option value="{{ $type->value }}" {{ request('type') == $type->value ? 'selected' : '' }}>
                    {{ $type->label() }}
                </option>
            @endforeach
        </select>
        <div
            class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-on-surface-variant">
            <span class="material-symbols-outlined">expand_more</span>
        </div>
    </div>
    <div class="relative group">
        <select
            name="day_context"
            onchange="this.form.submit()"
            class="w-full pl-4 pr-10 py-3 bg-surface-container-highest border-none rounded-xl appearance-none bg-none focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all cursor-pointer text-sm text-on-surface-variant font-medium">
            <option value="">Semua Konteks Hari</option>
            @foreach(App\Enums\CmsDayContext::cases() as $context)
                <option value="{{ $context->value }}" {{ request('day_context') == $context->value ? 'selected' : '' }}>
                    {{ $context->label() }}
                </option>
            @endforeach
        </select>
        <div
            class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-on-surface-variant">
            <span class="material-symbols-outlined">event</span>
        </div>
    </div>
</form>
