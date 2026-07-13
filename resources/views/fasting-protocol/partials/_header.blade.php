<!-- Header & Action Row -->
<div class="flex justify-between items-center mb-8">
    <div>
        <p class="text-on-surface-variant font-inter text-xs mb-1">Clinical Administration</p>
        <h3 class="text-3xl font-extrabold text-blue-900 tracking-tight font-headline">Management Console</h3>
    </div>
    <a href="{{ route('fasting-protocols-create') }}"
        class="bg-primary from-primary to-primary-container text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 shadow-lg shadow-blue-200/40 hover:scale-[1.02] transition-transform active:scale-95">
        <span class="material-symbols-outlined">add_circle</span>
        <span>Create New Protocol</span>
    </a>
</div>
