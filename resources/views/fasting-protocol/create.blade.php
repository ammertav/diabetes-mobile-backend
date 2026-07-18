@extends('layouts.app')

@section('content')
    <!-- Content Canvas -->
    <div class="px-10">
        <!-- Form Area -->
        <div class="w-full" x-data="{
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
            <div class="mb-10 flex items-center gap-4">
                <button
                    class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-primary/10 hover:text-primary transition-all">
                    <a href="{{ route('fasting-protocols') }}">
                        <span class="material-symbols-outlined">arrow_back</span>
                    </a>
                </button>
                <div>
                    <h2 class="font-headline text-3xl font-extrabold text-on-surface tracking-tight">Create New Protocol</h2>
                    <p class="font-body text-on-surface-variant mt-1">Define a new fasting regimen for clinical use.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('fasting-protocols-store') }}" class="space-y-8">
                @csrf

                <!-- Protocol Name -->
                <div class="group">
                    <label class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Protocol Name</label>
                    <input
                        class="w-full bg-surface-container-highest border-none rounded-xl px-5 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none"
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
                        <label class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Protocol Type</label>
                        <select
                            class="w-full appearance-none bg-surface-container-highest border-none rounded-xl px-5 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none cursor-pointer"
                            name="type">
                            <option value="sunnah" {{ old('type') == 'sunnah' ? 'selected' : '' }}>Sunnah</option>
                            <option value="intermittent" {{ old('type') == 'intermittent' ? 'selected' : '' }}>Intermittent</option>
                            <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                    </div>

                    <!-- Fasting Hours & Time Window -->
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Start Time</label>
                            <input
                                class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none"
                                name="start_time"
                                x-model="startTime"
                                placeholder="18:00"
                                type="text" />
                        </div>
                        <div>
                            <label class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">End Time</label>
                            <input
                                class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none"
                                name="end_time"
                                x-model="endTime"
                                placeholder="10:00"
                                type="text" />
                        </div>
                        <div>
                            <label class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Duration (Hours)</label>
                            <input
                                class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-4 text-on-surface font-body font-bold text-primary outline-none cursor-not-allowed"
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
                    <label class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Active Days</label>
                    <div class="flex flex-wrap gap-4 bg-surface-container-highest p-5 rounded-xl">
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
                                <input type="checkbox" name="days[]" value="{{ $value }}" {{ in_array($value, old('days', [])) ? 'checked' : '' }} class="rounded border-outline-variant text-primary focus:ring-primary/40 transition-all" />
                                <span class="text-sm font-medium text-on-surface group-hover:text-primary transition-colors">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('days')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-headline text-sm font-bold text-on-surface-variant mb-2 tracking-wide uppercase text-[10px]">Goal / Description</label>
                    <textarea
                        class="w-full bg-surface-container-highest border-none rounded-xl px-5 py-4 text-on-surface font-body focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/40 transition-all outline-none resize-none"
                        name="description"
                        placeholder="Primary health objective..." rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="pt-6 flex items-center gap-4">
                    <a href="{{ route('fasting-protocols') }}"
                        class="px-8 py-3.5 bg-surface-container-high text-on-surface font-headline font-bold rounded-xl hover:bg-surface-container-highest transition-all block">
                        Cancel
                    </a>
                    <button
                        class="px-10 py-3.5 bg-primary text-white font-headline font-extrabold rounded-xl shadow-lg hover:shadow-primary/20 hover:-translate-y-0.5 transition-all active:scale-95"
                        type="submit">
                        Save Protocol
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
