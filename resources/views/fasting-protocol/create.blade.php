@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto" x-data="{
        startTime: '{{ old('start_time', '18:00') }}',
        endTime: '{{ old('end_time', '10:00') }}',
        get durationHours() {
            if (!this.startTime || !this.endTime) return 0;
            const [sH, sM] = this.startTime.split(':').map(Number);
            const [eH, eM] = this.endTime.split(':').map(Number);
            if (isNaN(sH) || isNaN(eH)) return 0;
            let diff = (eH * 60 + (eM || 0)) - (sH * 60 + (sM || 0));
            if (diff <= 0) diff += 1440;
            return Math.round(diff / 60);
        }
    }">
        <!-- Header area -->
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('fasting-protocols') }}"
               class="w-10 h-10 rounded-xl bg-surface-container-lowest border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 hover:bg-primary hover:text-white transition-all shadow-sm">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-headline text-2xl font-extrabold text-on-surface tracking-tight">Create New Protocol</h2>
                <p class="font-body text-xs text-slate-500 mt-0.5">Define a new fasting regimen for clinical use.</p>
            </div>
        </div>

        <!-- Form Card Container -->
        <div class="bg-surface-container-lowest p-8 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
            <form method="POST" action="{{ route('fasting-protocols-store') }}" class="space-y-6">
                @csrf

                <!-- Protocol Name -->
                <div>
                    <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Protocol Name</label>
                    <input
                        class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="e.g., Intermittent 18:6"
                        type="text" />
                    @error('name')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Protocol Type -->
                    <div>
                        <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Protocol Type</label>
                        <select
                            class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none transition-all cursor-pointer"
                            name="type">
                            <option value="sunnah" {{ old('type') == 'sunnah' ? 'selected' : '' }}>Sunnah</option>
                            <option value="intermittent" {{ old('type') == 'intermittent' ? 'selected' : '' }}>Intermittent</option>
                            <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>

                    <!-- Fasting Window Hours -->
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Start Time</label>
                            <input
                                class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                name="start_time"
                                x-model="startTime"
                                placeholder="18:00"
                                type="text" />
                        </div>
                        <div>
                            <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">End Time</label>
                            <input
                                class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                                name="end_time"
                                x-model="endTime"
                                placeholder="10:00"
                                type="text" />
                        </div>
                        <div>
                            <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Duration (h)</label>
                            <input
                                class="w-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-3 text-sm font-bold text-primary outline-none cursor-not-allowed"
                                name="duration_hours"
                                :value="durationHours"
                                readonly
                                type="number" />
                            @error('duration_hours')
                                <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Active Days checkboxes -->
                <div>
                    <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Active Days</label>
                    <div class="flex flex-wrap gap-4 bg-surface-container-low border border-slate-200 dark:border-slate-700 p-4 rounded-xl">
                        @foreach([
                            1 => 'Monday',
                            2 => 'Tuesday',
                            3 => 'Wednesday',
                            4 => 'Thursday',
                            5 => 'Friday',
                            6 => 'Saturday',
                            7 => 'Sunday'
                        ] as $value => $label)
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="days[]" value="{{ $value }}" {{ in_array($value, old('days', [])) ? 'checked' : '' }} class="rounded border-slate-300 text-primary focus:ring-primary/40 transition-all" />
                                <span class="text-xs font-semibold text-slate-600 group-hover:text-primary transition-colors">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('days')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Goal / Description</label>
                    <textarea
                        class="w-full bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-on-surface font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none resize-none transition-all"
                        name="description"
                        placeholder="Primary health objective..." rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('fasting-protocols') }}"
                        class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition-all block">
                        Batal
                    </a>
                    <button
                        class="px-8 py-2.5 bg-primary hover:bg-primary/90 text-white font-headline font-bold text-xs rounded-xl shadow-md transition-all active:scale-95"
                        type="submit">
                        Simpan Protokol
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
