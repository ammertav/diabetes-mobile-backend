@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header area -->
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('cms') }}"
               class="w-10 h-10 rounded-xl bg-surface-container-lowest border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 hover:bg-primary hover:text-white transition-all shadow-sm">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-headline text-2xl font-extrabold text-on-surface tracking-tight">
                    {{ $content ? 'Edit Konten' : 'Buat Konten Baru' }}
                </h2>
                <p class="font-body text-xs text-slate-500 mt-0.5">Kelola edukasi dan motivasi harian untuk pasien diabetes.</p>
            </div>
        </div>

        <!-- Form Card Container -->
        <div class="bg-surface-container-lowest p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <form
                method="POST"
                action="{{ $content ? route('cms-update', $content->id) : route('cms-store') }}"
                class="space-y-6">
                @csrf
                @if($content)
                    @method('PUT')
                @endif

                <!-- Title Input -->
                <div>
                    <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Judul Konten</label>
                    <input
                        class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                        name="title" id="input_judul"
                        value="{{ old('title', $content->title ?? '') }}"
                        placeholder="Masukkan judul menarik di sini..."
                        type="text" />
                    @error('title')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Content Type Dropdown -->
                    <div>
                        <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Tipe Konten</label>
                        <select
                            class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none transition-all cursor-pointer"
                            name="type" id="input_tipe">
                            @foreach (\App\Enums\CmsContentType::cases() as $type)
                                <option value="{{ $type->value }}" {{ old('type', $content->content_type->value ?? '') == $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Day Context Dropdown -->
                    <div>
                        <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Konteks Hari</label>
                        <select
                            name="day_context"
                            class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none transition-all cursor-pointer">
                            <option value="">Tanpa Konteks Hari</option>
                            @foreach (\App\Enums\CmsDayContext::cases() as $context)
                                <option value="{{ $context->value }}" {{ old('day_context', $content->day_context->value ?? '') == $context->value ? 'selected' : '' }}>
                                    {{ $context->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Content Body Area -->
                <div>
                    <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Isi Konten</label>
                    <textarea
                        class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none resize-none transition-all"
                        name="body" id="input_isi"
                        placeholder="Tuliskan pesan motivasi atau edukasi Anda..." rows="6">{{ old('body', $content->body ?? '') }}</textarea>
                    @error('body')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Publishing Status -->
                <div>
                    <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Status Publikasi</label>
                    <div class="flex items-center gap-6 bg-surface-container-low p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                        <!-- Draft -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input
                                type="radio"
                                name="is_published"
                                value="0"
                                {{ old('is_published', isset($content) ? ($content->is_published ? '1' : '0') : '0') == '0' ? 'checked' : '' }}
                                class="text-primary focus:ring-primary/30" />
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-primary transition-colors">
                                Draft
                            </span>
                        </label>

                        <!-- Published -->
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input
                                type="radio"
                                name="is_published"
                                value="1"
                                {{ old('is_published', isset($content) ? ($content->is_published ? '1' : '0') : '0') == '1' ? 'checked' : '' }}
                                class="text-primary focus:ring-primary/30" />
                            <span class="text-xs font-semibold text-slate-700 group-hover:text-primary transition-colors">
                                Published
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('cms') }}"
                        class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all block">
                        Batal
                    </a>
                    <button
                        class="px-8 py-2.5 bg-primary hover:bg-primary/90 text-white font-headline font-bold text-xs rounded-xl shadow-md transition-all active:scale-95"
                        type="submit">
                        {{ $content ? 'Simpan Perubahan' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
