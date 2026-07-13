@extends('layouts.app')

@section('content')
    <x-toast />
    <!-- Header with Create Button -->
    <div
        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <p class="text-on-surface-variant text-sm font-medium">Kelola modul
                edukasi dan spiritual untuk aplikasi pasien.</p>
        </div>
        <button
            class="flex items-center bg-primary from-primary to-primary-container text-white px-8 py-3 rounded-lg font-semibold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform active:scale-95">
            <a href="{{ route('cms-create') }}"
                class="items-center flex gap-2">
                <span class="material-symbols-outlined">add</span>
                <span>Buat &amp; Publikasikan</span>
            </a>
        </button>
    </div>

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
                        <form action="{{ route('cms-destroy', $content->id) }}"
                            method="POST" class="inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="p-1.5 text-on-surface-variant hover:text-tertiary block"
                                title="Delete Content">
                                <span
                                    class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </form>
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
@endsection
