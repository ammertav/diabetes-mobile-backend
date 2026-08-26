@extends('layouts.app')

@section('content')
    <x-toast />

    <div x-data="{
        selectedContentId: '{{ $contents->first()->id ?? '' }}',
        contents: {{ json_encode($contents->items()) }},
        showDeleteModal: false,
        deleteActionUrl: ''
    }">

        <!-- Full Width Filter Bar -->
        @include('cms.partials._filters')

        <!-- Side-by-Side Aligned List and Preview -->
        <div class="grid grid-cols-12 gap-8">
            @include('cms.partials._list')
            @include('cms.partials._preview')
        </div>

        <!-- Hidden Form for deletion -->
        <form id="delete-form" method="POST" class="hidden" x-ref="deleteForm" :action="deleteActionUrl">
            @csrf
            @method('DELETE')
        </form>

        <!-- Custom Delete Confirmation Modal -->
        <div x-show="showDeleteModal"
             class="fixed inset-0 bg-slate-950/40 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

             <div @click.away="showDeleteModal = false"
                  class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 flex flex-col items-center text-center"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                  x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                  x-transition:leave="transition ease-in duration-200"
                  x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                  x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                  <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                      <span class="material-symbols-outlined text-2xl">warning</span>
                  </div>

                  <h3 class="text-lg font-bold text-slate-900 mb-2 font-headline">Hapus Konten?</h3>
                  <p class="text-xs text-slate-500 mb-6 leading-relaxed">
                      Apakah Anda yakin ingin menghapus konten edukasi ini? Tindakan ini tidak dapat dibatalkan.
                  </p>

                  <div class="flex gap-3 w-full">
                      <button type="button" @click="showDeleteModal = false"
                              class="flex-1 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors text-xs font-bold text-slate-600">
                          Batal
                      </button>
                      <button type="button" @click="$refs.deleteForm.submit()"
                              class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 transition-colors text-xs font-bold text-white shadow-md shadow-rose-600/20">
                          Ya, Hapus
                      </button>
                  </div>
             </div>
        </div>
    </div>
@endsection
