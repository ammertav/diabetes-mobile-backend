<!-- Media Section in Live Preview -->
<div class="space-y-3">
    <!-- DISPLAY MODE (When not editing media) -->
    <div x-show="activeEditField !== 'media'" class="relative group">
        <!-- 1. JIKA ADA MEDIA AKTIF (Image / Video / YouTube) -->
        <template x-if="hasMedia()">
            <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-inner group">
                <!-- IMAGE -->
                <template x-if="selectedContent.media_type === 'image' || selectedContent.media_type?.value === 'image'">
                    <img :src="selectedContent.media_url?.startsWith('http') ? selectedContent.media_url : '/storage/' + selectedContent.media_url"
                         class="w-full h-full object-cover" alt="Banner Image" />
                </template>

                <!-- VIDEO -->
                <template x-if="selectedContent.media_type === 'video' || selectedContent.media_type?.value === 'video'">
                    <div class="w-full h-full relative bg-black flex items-center justify-center">
                        <video :key="selectedContent.id + '_' + selectedContent.media_url"
                            :src="selectedContent.media_url?.startsWith('http') ? selectedContent.media_url : '/storage/' + selectedContent.media_url"
                            :poster="selectedContent.thumbnail_url ? (selectedContent.thumbnail_url.startsWith('http') ? selectedContent.thumbnail_url : '/storage/' + selectedContent.thumbnail_url) : ''"
                            class="w-full h-full object-contain" controls preload="metadata"></video>
                    </div>
                </template>

                <!-- YOUTUBE -->
                <template x-if="selectedContent.media_type === 'youtube' || selectedContent.media_type?.value === 'youtube'">
                    <div class="w-full h-full relative bg-black flex items-center justify-center overflow-hidden">
                        <template x-if="!isPlayingYoutube">
                            <div @click="isPlayingYoutube = true" class="w-full h-full relative flex items-center justify-center cursor-pointer group/yt">
                                <img :src="selectedContent.thumbnail_url || ('https://img.youtube.com/vi/' + (selectedContent.youtube_id || '') + '/hqdefault.jpg')"
                                     class="w-full h-full object-cover brightness-75 group-hover/yt:brightness-90 transition-all" alt="YouTube Cover" />
                                <div class="absolute w-14 h-14 rounded-full bg-rose-600 text-white flex items-center justify-center shadow-xl group-hover/yt:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-3xl">smart_display</span>
                                </div>
                                <span class="absolute bottom-2 right-2 px-2 py-0.5 bg-rose-600 text-white text-[10px] font-mono rounded font-bold">KLIK UNTUK PUTAR</span>
                            </div>
                        </template>
                        <template x-if="isPlayingYoutube">
                            <iframe
                                :key="selectedContent.youtube_id"
                                :src="'https://www.youtube.com/embed/' + selectedContent.youtube_id + '?autoplay=1&rel=0'"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </template>
                    </div>
                </template>

                <!-- Edit Floating Button -->
                <button type="button" @click="openEdit('media')"
                    class="absolute top-3 right-3 z-20 px-3 py-1.5 bg-slate-900/80 hover:bg-slate-900 text-white text-xs font-semibold rounded-lg backdrop-blur-md shadow flex items-center gap-1.5 opacity-80 hover:opacity-100 transition-all">
                    <span class="material-symbols-outlined text-sm">edit</span>
                    <span>Ubah Media</span>
                </button>
            </div>
        </template>

        <!-- 2. JIKA BELUM ADA MEDIA (None) -->
        <template x-if="!hasMedia()">
            <div @click="openEdit('media')"
                class="border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-blue-500 hover:bg-blue-50/40 p-5 rounded-xl text-center cursor-pointer transition-all group">
                <div class="flex items-center justify-center gap-2 text-xs font-bold text-slate-500 group-hover:text-blue-600">
                    <span class="material-symbols-outlined text-lg">add_photo_alternate</span>
                    <span>+ Tambah Media (Foto, Video, YouTube)</span>
                </div>
            </div>
        </template>
    </div>

    <!-- FORM MODE (When editing media) -->
    <div x-show="activeEditField === 'media'" class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
        <div class="flex justify-between items-center text-xs font-bold text-blue-700 pb-1 border-b border-blue-100">
            <span>Ubah Media Lampiran</span>
            <button type="button" @click="closeEdit()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <!-- Media Type Selector Pills -->
        <div class="grid grid-cols-4 gap-2">
            @foreach (\App\Enums\CmsMediaType::cases() as $mediaCase)
                <label
                    class="flex flex-col items-center justify-center p-2 rounded-lg border text-center cursor-pointer text-[10px] font-bold transition-all"
                    :class="editForm.media_type === '{{ $mediaCase->value }}'
                        ? 'border-blue-600 bg-blue-600 text-white shadow-sm'
                        : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'">
                    <input type="radio" name="media_type" value="{{ $mediaCase->value }}"
                        x-model="editForm.media_type" class="hidden" />
                    <span class="material-symbols-outlined text-base mb-0.5">{{ $mediaCase->icon() }}</span>
                    <span>{{ $mediaCase->label() }}</span>
                </label>
            @endforeach
        </div>

        <!-- 1. IMAGE UPLOAD INLINE -->
        <div x-show="editForm.media_type === 'image'" class="space-y-2">
            <input type="file" name="image_file" id="preview_image_file" accept="image/*"
                @change="handleInlineImage" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" />
            <template x-if="previewImg">
                <div class="h-28 rounded-lg overflow-hidden border border-blue-200 relative bg-slate-100">
                    <img :src="previewImg" class="w-full h-full object-cover" alt="Preview Image" />
                </div>
            </template>
        </div>

        <!-- 2. VIDEO UPLOAD INLINE -->
        <div x-show="editForm.media_type === 'video'" class="space-y-2">
            <div>
                <label class="text-[10px] font-bold text-slate-500 block mb-1">Berkas Video (Maks 50MB)</label>
                <input type="file" name="video_file" id="preview_video_file" accept="video/mp4,video/webm,video/quicktime"
                    @change="handleInlineVideo" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" />
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 block mb-1">Cover / Thumbnail (Opsional)</label>
                <input type="file" name="thumbnail_file" id="preview_thumb_file" accept="image/*"
                    @change="handleInlineThumb" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer" />
            </div>
        </div>

        <!-- 3. YOUTUBE INLINE -->
        <div x-show="editForm.media_type === 'youtube'" class="space-y-2">
            <label class="text-[10px] font-bold text-slate-500 block">URL Video YouTube</label>
            <input type="url" name="video_url"
                x-model="editForm.video_url"
                @input="updateInlineYoutube($event.target.value)"
                class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white"
                placeholder="https://www.youtube.com/watch?v=..." />
            <template x-if="previewThumb">
                <div class="flex items-center gap-2 p-2 bg-white rounded-lg border border-blue-100">
                    <img :src="previewThumb" class="w-14 h-9 object-cover rounded" alt="Thumb" />
                    <span class="text-[10px] text-slate-500">Thumbnail terdeteksi</span>
                </div>
            </template>
        </div>

        <!-- 4. NONE INLINE -->
        <div x-show="editForm.media_type === 'none'" class="p-2.5 bg-white rounded-lg border text-[11px] text-slate-500">
            Media saat ini akan dihapus dan konten disimpan tanpa media lampiran.
        </div>

        <!-- Action buttons -->
        <div class="flex justify-end gap-2 pt-2 border-t border-blue-100">
            <button type="button" @click="closeEdit()"
                class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
            <button type="submit"
                class="px-4 py-1.5 text-xs bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-sm">Simpan Media</button>
        </div>
    </div>
</div>
