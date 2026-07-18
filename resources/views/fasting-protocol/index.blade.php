@extends('layouts.app')
 
 @section('content')
     <x-toast />
     
     <div x-data="{
         selectedType: 'all',
         selectedProtocolId: '{{ $protocols->first()->id ?? '' }}',
         protocols: {{ json_encode($protocols) }},
         showDeleteModal: false,
         deleteActionUrl: ''
     }">
         
         <div class="grid grid-cols-12 gap-8">
             @include('fasting-protocol.partials._list')
             @include('fasting-protocol.partials._preview')
         </div>
 
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
                   <h3 class="text-lg font-bold text-on-surface mb-2 font-headline">Hapus Protokol?</h3>
                   <p class="text-xs text-on-surface-variant mb-6 leading-relaxed">
                       Apakah Anda yakin ingin menghapus protokol puasa ini? Tindakan ini tidak dapat dibatalkan.
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
     </div>
 
     <!-- Floating Action Button for Create Fasting Protocol -->
     <a href="{{ route('fasting-protocols-create') }}"
        class="fixed bottom-8 right-8 z-50 flex items-center justify-center gap-2 bg-primary text-white px-6 py-4 rounded-full font-bold shadow-2xl shadow-primary/30 hover:scale-105 active:scale-95 transition-all duration-200 group">
         <span class="material-symbols-outlined text-2xl group-hover:rotate-90 transition-transform duration-300">add</span>
         <span class="whitespace-nowrap pr-1">Create New Protocol</span>
     </a>
 @endsection
