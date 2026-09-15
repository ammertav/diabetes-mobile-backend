<!-- Media Type Selection & Inputs -->
<div class="space-y-4 pt-2 border-t border-slate-100 dark:border-slate-800">
    <div>
        <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
            Tipe Media Lampiran
        </label>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach (\App\Enums\CmsMediaType::cases() as $mediaCase)
                <label
                    class="flex items-center gap-2.5 p-3 rounded-xl border cursor-pointer transition-all text-xs font-semibold"
                    :class="mediaType === '{{ $mediaCase->value }}'
                        ? 'border-primary bg-primary/5 text-primary ring-2 ring-primary/20'
                        : 'border-slate-200 dark:border-slate-700 bg-surface-container-low text-slate-600 hover:border-slate-300'">
                    <input type="radio" name="media_type" value="{{ $mediaCase->value }}"
                        x-model="mediaType" class="hidden" />
                    <span class="material-symbols-outlined text-lg">{{ $mediaCase->icon() }}</span>
                    <span>{{ $mediaCase->label() }}</span>
                </label>
            @endforeach
        </div>
        @error('media_type')
            <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- 1. IMAGE INPUT SECTION -->
    <div x-show="mediaType === 'image'" x-cloak class="space-y-3">
        <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            Berkas Gambar (Maks 3MB)
        </label>
        <div class="flex flex-col sm:flex-row gap-4 items-start">
            <div class="flex-1 w-full border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-primary/50 rounded-2xl p-4 text-center transition-colors bg-surface-container-low">
                <input type="file" name="image_file" id="image_file" accept="image/*"
                    @change="handleImageChange" class="hidden" />
                <label for="image_file" class="cursor-pointer flex flex-col items-center justify-center py-4">
                    <span class="material-symbols-outlined text-3xl text-primary mb-2">add_photo_alternate</span>
                    <span class="text-xs font-bold text-slate-700">Pilih Berkas Foto</span>
                    <span class="text-[11px] text-slate-400 mt-1">Format: JPG, PNG, WEBP (Maksimal 3MB)</span>
                </label>
            </div>
            <template x-if="imagePreview">
                <div class="w-full sm:w-44 h-32 rounded-xl overflow-hidden border border-slate-200 relative group bg-slate-100 flex-shrink-0">
                    <img :src="imagePreview" class="w-full h-full object-cover" alt="Preview Image" />
                    <button type="button" @click="imagePreview = ''; $el.form.querySelector('#image_file').value = ''"
                        class="absolute top-2 right-2 w-6 h-6 bg-rose-600 text-white rounded-full flex items-center justify-center text-xs opacity-90 hover:opacity-100 shadow">
                        ✕
                    </button>
                </div>
            </template>
        </div>
        @error('image_file')
            <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- 2. VIDEO INPUT SECTION -->
    <div x-show="mediaType === 'video'" x-cloak class="space-y-4">
        <div>
            <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                Berkas Video MP4 (Maks 50MB)
            </label>
            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <div class="flex-1 w-full border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-primary/50 rounded-2xl p-4 text-center transition-colors bg-surface-container-low">
                    <input type="file" name="video_file" id="video_file" accept="video/mp4,video/webm,video/quicktime"
                        @change="handleVideoChange" class="hidden" />
                    <label for="video_file" class="cursor-pointer flex flex-col items-center justify-center py-4">
                        <span class="material-symbols-outlined text-3xl text-primary mb-2">video_file</span>
                        <span class="text-xs font-bold text-slate-700">Pilih Berkas Video</span>
                        <span class="text-[11px] text-slate-400 mt-1">Format: MP4, WEBM (Maksimal 50MB)</span>
                    </label>
                </div>
                <template x-if="videoPreview">
                    <div class="w-full sm:w-48 h-32 rounded-xl overflow-hidden border border-slate-800 bg-black relative flex-shrink-0">
                        <video :src="videoPreview" class="w-full h-full object-cover" controls></video>
                    </div>
                </template>
            </div>
            @error('video_file')
                <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
            @enderror
        </div>

        <!-- Custom Thumbnail for Video -->
        <div>
            <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">
                Thumbnail / Cover Video (Opsional, Maks 2MB)
            </label>
            <div class="flex items-center gap-3">
                <input type="file" name="thumbnail_file" id="thumbnail_file" accept="image/*"
                    @change="handleThumbChange" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" />
                <template x-if="thumbnailPreview">
                    <img :src="thumbnailPreview" class="w-12 h-12 object-cover rounded-lg border border-slate-200" alt="Thumb" />
                </template>
            </div>
            @error('thumbnail_file')
                <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <!-- 3. YOUTUBE INPUT SECTION -->
    <div x-show="mediaType === 'youtube'" x-cloak class="space-y-3">
        <label class="block font-headline text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            Link Video YouTube
        </label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-rose-600">
                <span class="material-symbols-outlined text-lg">smart_display</span>
            </span>
            <input
                type="url" name="video_url"
                x-model="youtubeUrl"
                @input="updateYoutube($event.target.value)"
                class="w-full pl-10 pr-4 py-3 bg-surface-container-low border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-body focus:bg-white focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                placeholder="https://www.youtube.com/watch?v=..." />
        </div>
        <template x-if="youtubeId">
            <div class="p-3 bg-rose-50/50 border border-rose-100 rounded-xl flex items-center gap-4">
                <img :src="'https://img.youtube.com/vi/' + youtubeId + '/hqdefault.jpg'"
                    class="w-20 h-12 object-cover rounded-lg border border-rose-200 shadow-sm" alt="YT Thumbnail" />
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 bg-rose-100 px-2 py-0.5 rounded">ID: <span x-text="youtubeId"></span></span>
                    <p class="text-[11px] text-slate-600 mt-1">Thumbnail otomatis dari YouTube berhasil dideteksi.</p>
                </div>
            </div>
        </template>
        @error('video_url')
            <span class="text-xs text-red-500 font-medium mt-1 block">{{ $message }}</span>
        @enderror
    </div>

    <!-- 4. NONE (NO MEDIA) INFO -->
    <div x-show="mediaType === 'none'" x-cloak class="p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-500 text-xs flex items-center gap-2">
        <span class="material-symbols-outlined text-base">info</span>
        <span>Konten ini hanya akan memuat judul dan teks tanpa lampiran gambar maupun video.</span>
    </div>
</div>
