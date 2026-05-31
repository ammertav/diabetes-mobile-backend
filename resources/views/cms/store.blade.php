@extends('layouts.app')

@section('content')
    <!-- Content Canvas -->
    <div class="px-10">
        <!-- Form Area -->
        <div class="w-full">
            <div class="mb-10 flex items-center gap-4">
                <button
                    class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-primary/10 hover:text-primary transition-all">
                    <a href="{{ route('cms-create') }}">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                </button>
                <div>
                    <h2
                        class="font-headline text-3xl font-extrabold text-on-surface tracking-tight">
                        Buat Konten Baru</h2>
                    <p class="font-body text-on-surface-variant mt-1">Kelola
                        edukasi dan motivasi harian untuk pasien diabetes.</p>
                </div>
            </div>
            <form
                method="POST"
                action="{{ route('cms-store') }}"
                class="space-y-8">
                <!-- Title Input -->
                <div class="group">
                    <label
                        class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Judul
                        Konten</label>
                    <input
                        class="w-full bg-surface-container-highest border-none rounded-xl px-5 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none"
                        name="title" id="input_judul"
                        placeholder="Masukkan judul menarik di sini..."
                        type="text" />
                </div>
                <div class="grid grid-cols-1 gap-6">
                    <!-- Content Type Dropdown -->
                    <div>
                        <label
                            class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Tipe
                            Konten</label>
                        <div class="relative">
                            <select
                                class="w-full appearance-none bg-surface-container-highest border-none rounded-xl px-5 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none"
                                name="type" id="input_tipe">
                                @foreach (\App\Enums\CmsContentType::cases() as $type)
                                    <option value="{{ $type->value }}">
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Day Context Dropdown -->
                    <div>
                        <label
                            class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Konteks
                            Hari</label>
                        <div class="relative">
                            <select
                                name="day_context"
                                class="w-full appearance-none bg-surface-container-highest border-none rounded-xl px-5 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none">
                                @foreach (\App\Enums\CmsDayContext::cases() as $type)
                                    <option value="{{ $type->value }}">
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Content Body Area -->
                <div>
                    <label
                        class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Isi
                        Konten</label>
                    <textarea
                        class="w-full bg-surface-container-highest border-none rounded-xl px-5 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none resize-none"
                        name="body" id="input_isi"
                        placeholder="Tuliskan pesan motivasi atau edukasi Anda..." rows="6"></textarea>
                </div>
                <!-- Publishing Status -->
                <div class="flex items-center gap-12">
                    <div>
                        <label
                            class="block font-headline text-sm font-bold text-on-surface-variant mb-3 tracking-wide uppercase text-[10px]">Status
                            Publikasi</label>
                        <div class="flex items-center gap-6">

                            <!-- Draft -->
                            <label
                                class="flex items-center gap-3 cursor-pointer group">
                                <div
                                    class="relative flex items-center justify-center">

                                    <input
                                        type="radio"
                                        name="is_published"
                                        value="0"
                                        checked
                                        class="peer sr-only" />

                                    <!-- Outer -->
                                    <div
                                        class="w-5 h-5 rounded-full border-2 border-outline-variant peer-checked:border-primary transition-colors duration-200">
                                    </div>

                                    <!-- Inner -->
                                    <div
                                        class="absolute w-2.5 h-2.5 rounded-full bg-primary scale-0 peer-checked:scale-100 transition-transform duration-200">
                                    </div>
                                </div>

                                <span
                                    class="text-sm font-medium text-on-surface group-hover:text-primary transition-colors">
                                    Draft
                                </span>
                            </label>

                            <!-- Published -->
                            <label
                                class="flex items-center gap-3 cursor-pointer group">
                                <div
                                    class="relative flex items-center justify-center">

                                    <input
                                        type="radio"
                                        name="is_published"
                                        value="1"
                                        class="peer sr-only" />

                                    <!-- Outer -->
                                    <div
                                        class="w-5 h-5 rounded-full border-2 border-outline-variant peer-checked:border-primary transition-colors duration-200">
                                    </div>

                                    <!-- Inner -->
                                    <div
                                        class="absolute w-2.5 h-2.5 rounded-full bg-primary scale-0 peer-checked:scale-100 transition-transform duration-200">
                                    </div>
                                </div>

                                <span
                                    class="text-sm font-medium text-on-surface group-hover:text-primary transition-colors">
                                    Published
                                </span>
                            </label>

                        </div>
                    </div>
                </div>
                <!-- Actions -->
                <div class="pt-6 flex items-center gap-4">
                    <button
                        class="px-8 py-3.5 bg-surface-container-high text-on-surface font-headline font-bold rounded-xl hover:bg-surface-container-highest transition-all"
                        type="button">
                        Batal
                    </button>
                    <button
                        class="px-10 py-3.5 bg-primary text-white font-headline font-extrabold rounded-xl shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 transition-all active:scale-95"
                        type="submit">
                        Publikasikan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
