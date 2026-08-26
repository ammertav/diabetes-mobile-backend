<div class="col-span-12 lg:col-span-8">
    <!-- Dynamic Content Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($contents as $content)
            <div @click="selectedContentId = '{{ $content->id }}'; $nextTick(() => { document.getElementById('preview-panel')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); })"
                 :class="selectedContentId === '{{ $content->id }}' ? 'ring-2 ring-blue-600 shadow-md scale-[1.01]' : 'shadow-sm hover:shadow-md border border-slate-100'"
                 class="bg-white p-6 rounded-2xl transition-all cursor-pointer flex flex-col justify-between min-h-64">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-slate-100 text-slate-500 px-2.5 py-1 rounded-md text-[10px] font-mono font-bold">
                            #{{ substr($content->id, 0, 8) }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            @if ($content->is_published)
                                <span class="bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">Published</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider">Draft</span>
                            @endif
                            @if ($content->day_context)
                                <span class="text-xs text-slate-500 font-bold ml-1.5">{{ $content->day_context->label() }}</span>
                            @endif
                        </div>
                    </div>
                    <h4 class="font-bold text-slate-900 text-base mb-2 font-headline group-hover:text-blue-600 transition-colors">
                        {{ $content->title }}
                    </h4>
                    <p class="text-xs text-slate-500 line-clamp-3 mb-4 leading-relaxed font-body">
                        {{ $content->body }}
                    </p>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-slate-100 mt-auto">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $content->content_type->textColor() }} bg-slate-100">
                        {{ $content->content_type->label() }}
                    </span>
                    <div class="flex items-center gap-1">
                        <!-- Delete Button -->
                        <button type="button"
                                @click.stop="deleteActionUrl = '{{ route('cms-destroy', $content->id) }}'; showDeleteModal = true"
                                class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition-colors"
                                title="Delete Content">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-slate-400 bg-white rounded-2xl border border-slate-100 shadow-sm">
                <span class="material-symbols-outlined text-5xl mb-2">library_books</span>
                <p class="font-medium text-xs">Tidak ada konten edukasi yang sesuai kriteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($contents->hasPages())
        <div class="mt-10 flex flex-col items-center gap-4">
            {{ $contents->appends(request()->query())->links() }}
        </div>
    @endif
</div>
