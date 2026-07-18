@extends('layouts.app')

@section('content')
    <x-toast />

    <div x-data="{ showDeleteModal: false, deleteActionUrl: '' }">
        @include('cms.partials._filters')

    <!-- Dynamic Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($contents as $content)
            <div
                class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-{{ str_replace('bg-', '', $content->content_type->color()) }} hover:translate-y-[-4px] transition-all flex flex-col justify-between min-h-64">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="bg-surface-container-highest text-on-surface-variant px-2 py-1 rounded text-[10px] font-mono">
                            #{{ substr($content->id, 0, 8) }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            @if ($content->is_published)
                                <span
                                    class="bg-secondary/10 text-secondary px-2 py-0.5 rounded text-[10px] font-bold uppercase">Published</span>
                            @else
                                <span
                                    class="bg-outline-variant/20 text-on-surface-variant px-2 py-0.5 rounded text-[10px] font-bold uppercase">Draft</span>
                            @endif
                            @if ($content->day_context)
                                <span
                                    class="text-xs text-on-surface-variant font-bold ml-1.5">{{ $content->day_context->label() }}</span>
                            @endif
                        </div>
                    </div>
                    <h4
                        class="font-bold text-on-surface text-base mb-2 group-hover:text-primary transition-colors">
                        {{ $content->title }}</h4>
                    <p
                        class="text-xs text-on-surface-variant line-clamp-3 mb-4 leading-relaxed">
                        {{ $content->body }}
                    </p>
                </div>
                <div
                    class="flex justify-between items-center pt-4 border-t border-surface-variant/20 mt-auto">
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $content->content_type->textColor() }} bg-surface-variant/50">
                        {{ $content->content_type->label() }}
                    </span>
                    <div class="flex gap-1">
                        <button
                            class="p-1.5 text-primary hover:bg-primary/10 rounded"
                            title="Mobile Preview">
                            <span
                                class="material-symbols-outlined text-lg">smartphone</span>
                        </button>
                        <a href="{{ route('cms-edit', $content->id) }}"
                            class="p-1.5 text-on-surface-variant hover:text-primary block"
                            title="Edit Content">
                            <span
                                class="material-symbols-outlined text-lg">edit</span>
                        </a>
                        <button type="button"
                            @click.prevent="deleteActionUrl = '{{ route('cms-destroy', $content->id) }}'; showDeleteModal = true"
                            class="p-1.5 text-on-surface-variant hover:text-tertiary block"
                            title="Delete Content">
                            <span class="material-symbols-outlined text-lg">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-full py-16 flex flex-col items-center justify-center text-on-surface-variant">
                <span
                    class="material-symbols-outlined text-5xl text-outline mb-2">library_books</span>
                <p class="font-medium text-sm">Tidak ada konten edukasi yang sesuai
                    kriteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($contents->hasPages())
        <div class="mt-12 flex flex-col items-center gap-4">
            {{ $contents->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Hidden Form for deletion -->
    <form id="delete-form" method="POST" class="hidden" x-ref="deleteForm" :action="deleteActionUrl">
        @csrf
        @method('DELETE')
    </form>

    <!-- Custom Beautiful Delete Confirmation Modal -->
    <div x-show="showDeleteModal"
         class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
         <!-- Modal Card -->
         <div @click.away="showDeleteModal = false"
              class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 dark:border-slate-800 flex flex-col items-center text-center"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 scale-95 translate-y-4"
              x-transition:enter-end="opacity-100 scale-100 translate-y-0"
              x-transition:leave="transition ease-in duration-200"
              x-transition:leave-start="opacity-100 scale-100 translate-y-0"
              x-transition:leave-end="opacity-0 scale-95 translate-y-4">
              
              <!-- Warning Icon -->
              <div class="w-12 h-12 rounded-full bg-tertiary/10 text-tertiary flex items-center justify-center mb-4">
                  <span class="material-symbols-outlined text-2xl" data-weight="fill">warning</span>
              </div>
              
              <!-- Title & Description -->
              <h3 class="text-lg font-bold text-on-surface mb-2 font-headline">Hapus Konten?</h3>
              <p class="text-xs text-on-surface-variant mb-6 leading-relaxed">
                  Apakah Anda yakin ingin menghapus konten edukasi ini? Tindakan ini tidak dapat dibatalkan.
              </p>
              
              <!-- Action Buttons -->
              <div class="flex gap-3 w-full">
                  <button type="button" @click="showDeleteModal = false"
                          class="flex-1 py-2.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors text-xs font-semibold text-on-surface-variant">
                      Batal
                  </button>
                  <button type="button" @click="$refs.deleteForm.submit()"
                          class="flex-1 py-2.5 rounded-lg bg-tertiary hover:bg-tertiary/90 transition-colors text-xs font-semibold text-white shadow-lg shadow-tertiary/20">
                      Ya, Hapus
                  </button>
              </div>
         </div>
    </div>

    <!-- Floating Action Button for Create CMS -->
    <a href="{{ route('cms-create') }}"
       class="fixed bottom-8 right-8 z-50 flex items-center justify-center gap-2 bg-primary text-white px-6 py-4 rounded-full font-bold shadow-2xl shadow-primary/30 hover:scale-105 active:scale-95 transition-all duration-200 group">
        <span class="material-symbols-outlined text-2xl group-hover:rotate-90 transition-transform duration-300">add</span>
        <span class="whitespace-nowrap pr-1">Buat CMS Baru</span>
    </a>
    </div>
@endsection
