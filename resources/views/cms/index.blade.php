@extends('layouts.app')

@section('content')
    <x-toast />
    <header class="flex justify-between items-center mb-10 w-full">
        <div>
            <h1
                class="text-3xl font-extrabold text-on-surface tracking-tight font-headline">
                Content Management System (CMS)</h1>
            <p class="text-on-surface-variant mt-1 text-lg">Kelola modul edukasi dan
                spiritual untuk aplikasi pasien.</p>
        </div>
        <div class="flex items-center gap-4">
            <button
                class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button
                class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition-colors">
                <span class="material-symbols-outlined">settings</span>
            </button>
            <button
                class="flex items-center bg-primary from-primary to-primary-container text-white px-8 py-3 rounded-lg font-semibold shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform active:scale-95">
                <a href="{{ route('cms-create') }}"
                    class="items-center flex gap-2">
                    <span class="material-symbols-outlined">add</span>
                    <span>Buat &amp; Publikasikan</span>
                </a>
            </button>
        </div>
    </header>
    <!-- Filters Section -->
    <section class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="md:col-span-2 relative">
            <div
                class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-on-surface-variant">
                <span class="material-symbols-outlined text-sm">search</span>
            </div>
            <input
                class="w-full pl-12 pr-4 py-3 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all placeholder:text-on-surface-variant/60"
                placeholder="Cari judul atau ID konten..." type="text" />
        </div>
        <div class="relative group">
            <select
                class="w-full pl-4 pr-10 py-3 bg-surface-container-highest border-none rounded-xl appearance-none focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all cursor-pointer">
                <option value="">Semua Tipe Konten</option>
                <option>Motivation</option>
                <option>Reflection</option>
                <option>Education</option>
                <option>Nutrition</option>
                <option>Safety Guide</option>
            </select>
            <div
                class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-on-surface-variant">
                <span class="material-symbols-outlined">expand_more</span>
            </div>
        </div>
        <div class="relative group">
            <select
                class="w-full pl-4 pr-10 py-3 bg-surface-container-highest border-none rounded-xl appearance-none focus:ring-2 focus:ring-primary/40 focus:bg-surface-container-lowest transition-all cursor-pointer">
                <option value="">Semua Konteks Hari</option>
                <option>Monday</option>
                <option>Thursday</option>
                <option>General</option>
            </select>
            <div
                class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-on-surface-variant">
                <span class="material-symbols-outlined">event</span>
            </div>
        </div>
    </section>
    <!-- Content Grid (Asymmetric Bento Style) -->
    <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Featured Content Card (Monday Education) -->
        <div
            class="xl:col-span-2 bg-surface-container-lowest p-8 rounded-xl shadow-sm relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-6">
                <span
                    class="bg-secondary/10 text-secondary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mr-2 border border-secondary/20">Published</span><span
                    class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Education</span>
            </div>
            <div class="flex flex-col h-full">
                <div class="mb-4">
                    <code
                        class="text-[10px] text-on-surface-variant/60 font-mono">UUID:
                        8f2b-4c91-9981-e2d1</code>
                    <h3
                        class="text-2xl font-bold mt-2 text-on-surface group-hover:text-primary transition-colors">
                        Panduan Gizi untuk Penderita Diabetes Tipe 2</h3>
                </div>
                <p
                    class="text-on-surface-variant mb-8 line-clamp-3 leading-relaxed">
                    Nutrisi yang tepat adalah kunci utama dalam pengelolaan kadar
                    gula darah. Dalam modul ini, kita akan membahas porsi piring
                    makan yang seimbang, memilih karbohidrat kompleks, dan
                    pentingnya serat dalam diet harian Anda.
                </p>
                <div class="mt-auto flex items-center justify-between">
                    <div
                        class="flex items-center gap-4 text-sm text-on-surface-variant font-medium">
                        <span class="flex items-center gap-1"><span
                                class="material-symbols-outlined text-sm">calendar_today</span>
                            Senin</span>
                        <span class="flex items-center gap-1"><span
                                class="material-symbols-outlined text-sm">visibility</span>
                            1.2k Views</span>
                    </div>
                    <div class="flex items-center gap-2"><button
                            class="p-2 hover:bg-primary/10 rounded-lg text-primary transition-colors flex items-center gap-2"
                            title="Preview on Mobile Device"><span
                                class="material-symbols-outlined">smartphone</span><span
                                class="text-xs font-bold">Preview
                                Mobile</span></button>
                        <button
                            class="p-2 hover:bg-surface-container-high rounded-lg text-primary transition-colors flex items-center gap-2"><button
                                class="p-2 hover:bg-primary/10 rounded-lg text-primary transition-colors flex items-center gap-2"
                                title="Preview on Mobile Device"><span
                                    class="material-symbols-outlined">smartphone</span><span
                                    class="text-xs font-bold">Preview
                                    Mobile</span></button>
                            <span class="material-symbols-outlined">edit</span>
                            <span class="text-xs font-bold">Edit</span>
                        </button>
                        <button
                            class="p-2 hover:bg-tertiary-container hover:text-white rounded-lg text-tertiary transition-colors">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Small Motivation Card -->
        <div
            class="bg-primary text-white p-8 rounded-xl relative flex flex-col justify-between group">
            <div
                class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <svg height="100%" preserveaspectratio="none" viewbox="0 0 100 100"
                    width="100%">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="currentColor">
                    </path>
                </svg>
            </div>
            <div>
                <span
                    class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">Motivation</span>
                <h3 class="text-xl font-bold mt-4 leading-snug">Setiap Langkah Kecil
                    Berarti untuk Kesehatan Anda</h3>
            </div>
            <div class="mt-8">
                <p class="text-primary-fixed text-sm line-clamp-2 italic mb-6">
                    "Kesehatan bukanlah tujuan, melainkan sebuah perjalanan..."</p>
                <div class="flex justify-end gap-2">
                    <button
                        class="p-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                        <span class="material-symbols-outlined">edit</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- List Style Cards -->
        <div class="xl:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Item 1 -->
            <div
                class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-secondary-fixed hover:translate-y-[-4px] transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span
                        class="bg-surface-container-highest text-on-surface-variant px-2 py-1 rounded text-[10px] font-mono">#992a-331</span>
                    <span
                        class="bg-secondary/10 text-secondary px-2 py-0.5 rounded text-[10px] font-bold uppercase mr-2">Published</span><span
                        class="text-xs text-on-surface-variant font-bold">General</span>
                </div>
                <h4 class="font-bold mb-2">Pentingnya Hidrasi</h4>
                <p class="text-xs text-on-surface-variant line-clamp-2 mb-4">Mengapa
                    air putih lebih baik daripada minuman manis?</p>
                <div
                    class="flex justify-between items-center pt-4 border-t border-surface-variant/20">
                    <span
                        class="bg-secondary-container text-on-secondary-container px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">Nutrition</span>
                    <div class="flex gap-1"><button
                            class="p-1.5 text-primary hover:bg-primary/10 rounded"
                            title="Mobile Preview"><span
                                class="material-symbols-outlined text-lg">smartphone</span></button>
                        <button
                            class="p-1.5 text-on-surface-variant hover:text-primary"><span
                                class="material-symbols-outlined text-lg">edit</span></button>
                        <button
                            class="p-1.5 text-on-surface-variant hover:text-tertiary"><span
                                class="material-symbols-outlined text-lg">delete</span></button>
                    </div>
                </div>
            </div>
            <!-- Item 2 -->
            <div
                class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-tertiary hover:translate-y-[-4px] transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span
                        class="bg-surface-container-highest text-on-surface-variant px-2 py-1 rounded text-[10px] font-mono">#c221-10d</span>
                    <span
                        class="bg-outline-variant/20 text-on-surface-variant px-2 py-0.5 rounded text-[10px] font-bold uppercase mr-2">Draft</span><span
                        class="text-xs text-on-surface-variant font-bold">Thursday</span>
                </div>
                <h4 class="font-bold mb-2">Pertolongan Pertama Hipoglikemia</h4>
                <p class="text-xs text-on-surface-variant line-clamp-2 mb-4">
                    Langkah darurat saat gula darah turun drastis di bawah normal.
                </p>
                <div
                    class="flex justify-between items-center pt-4 border-t border-surface-variant/20">
                    <span
                        class="bg-tertiary-container text-on-tertiary-container px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">Safety
                        Guide</span>
                    <div class="flex gap-1"><button
                            class="p-1.5 text-primary hover:bg-primary/10 rounded"
                            title="Mobile Preview"><span
                                class="material-symbols-outlined text-lg">smartphone</span></button>
                        <button
                            class="p-1.5 text-on-surface-variant hover:text-primary"><span
                                class="material-symbols-outlined text-lg">edit</span></button>
                        <button
                            class="p-1.5 text-on-surface-variant hover:text-tertiary"><span
                                class="material-symbols-outlined text-lg">delete</span></button>
                    </div>
                </div>
            </div>
            <!-- Item 3 -->
            <div
                class="bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-primary hover:translate-y-[-4px] transition-transform">
                <div class="flex justify-between items-start mb-4">
                    <span
                        class="bg-surface-container-highest text-on-surface-variant px-2 py-1 rounded text-[10px] font-mono">#d881-22e</span>
                    <span
                        class="bg-primary/10 text-primary px-2 py-0.5 rounded text-[10px] font-bold uppercase mr-2">Scheduled</span><span
                        class="text-xs text-on-surface-variant font-bold">Monday</span>
                </div>
                <h4 class="font-bold mb-2">Meditasi &amp; Ketenangan</h4>
                <p class="text-xs text-on-surface-variant line-clamp-2 mb-4">Sesi
                    refleksi diri untuk menjaga kesehatan mental pasien diabetes.
                </p>
                <div
                    class="flex justify-between items-center pt-4 border-t border-surface-variant/20">
                    <span
                        class="bg-primary-container text-on-primary-container px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">Reflection</span>
                    <div class="flex gap-1"><button
                            class="p-1.5 text-primary hover:bg-primary/10 rounded"
                            title="Mobile Preview"><span
                                class="material-symbols-outlined text-lg">smartphone</span></button>
                        <button
                            class="p-1.5 text-on-surface-variant hover:text-primary"><span
                                class="material-symbols-outlined text-lg">edit</span></button>
                        <button
                            class="p-1.5 text-on-surface-variant hover:text-tertiary"><span
                                class="material-symbols-outlined text-lg">delete</span></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pagination / Load More -->
    <div class="mt-12 flex flex-col items-center gap-4">
        <button
            class="px-8 py-3 bg-surface-container-high text-on-surface font-semibold rounded-xl hover:bg-surface-container-highest transition-all flex items-center gap-2">
            <span>Tampilkan Lebih Banyak</span>
            <span class="material-symbols-outlined">keyboard_arrow_down</span>
        </button>
        <p class="text-sm text-on-surface-variant">Menampilkan 5 dari 124 konten
            edukasi</p>
    </div>
@endsection
