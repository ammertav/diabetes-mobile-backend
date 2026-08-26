<div class="col-span-12 lg:col-span-4" x-data="{
    activeEditField: null,
    editForm: { title: '', type: 'education', day_context: '', body: '', is_published: 1 },
    get selectedContent() {
        return this.contents.find(c => c.id === this.selectedContentId);
    },
    openEdit(field) {
        if (!this.selectedContent) return;
        this.editForm = {
            title: this.selectedContent.title,
            type: this.selectedContent.content_type?.value || this.selectedContent.content_type || 'education',
            day_context: this.selectedContent.day_context?.value || this.selectedContent.day_context || '',
            body: this.selectedContent.body || '',
            is_published: this.selectedContent.is_published ? 1 : 0
        };
        this.activeEditField = field;
    },
    closeEdit() {
        this.activeEditField = null;
    }
}">
    <div id="preview-panel" x-show="selectedContent"
        class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm sticky top-20 space-y-6">
        <!-- Header -->
        <div
            class="flex items-center justify-between pb-3 border-b border-slate-100">
            <h5 class="text-base font-bold font-headline text-slate-900">CMS Live
                Preview</h5>
            <span
                class="bg-emerald-100 text-emerald-800 text-[10px] px-2.5 py-1 rounded-md font-bold uppercase tracking-wider">LIVE
                PREVIEW</span>
        </div>

        <form x-show="selectedContent" :action="'/cms/' + selectedContent?.id"
            method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Hidden preserving inputs -->
            <input type="hidden" name="title" :value="editForm.title">
            <input type="hidden" name="type" :value="editForm.type">
            <input type="hidden" name="day_context"
                :value="editForm.day_context">
            <input type="hidden" name="body" :value="editForm.body">
            <input type="hidden" name="is_published"
                :value="editForm.is_published">

            <!-- 1. Title, Type & Status Section -->
            <div>
                <!-- Display Mode -->
                <div x-show="activeEditField !== 'title'"
                    @click="openEdit('title')"
                    class="group p-3 -m-3 rounded-xl border border-transparent hover:border-dashed hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                    <div
                        class="flex items-center justify-between mb-2 flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <span
                                :class="editForm.is_published ?
                                    'bg-emerald-100 text-emerald-800' :
                                    'bg-slate-100 text-slate-600'"
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                x-text="editForm.is_published ? 'Published' : 'Draft'"></span>
                            <span
                                class="bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"
                                x-text="editForm.type"></span>
                        </div>
                        <span
                            class="material-symbols-outlined text-xs text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">edit</span>
                    </div>
                    <h4 class="text-xl font-extrabold text-slate-900 font-headline tracking-tight"
                        x-text="selectedContent?.title"></h4>
                </div>

                <!-- Form Mode -->
                <div x-show="activeEditField === 'title'"
                    class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div
                        class="flex justify-between items-center text-xs font-bold text-blue-700">
                        <span>Edit Judul & Status</span>
                        <button type="button" @click="closeEdit()"
                            class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div>
                        <label
                            class="text-[10px] font-bold text-slate-500 block mb-1">Judul
                            Konten</label>
                        <input type="text" x-model="editForm.title"
                            class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label
                                class="text-[10px] font-bold text-slate-500 block mb-1">Tipe
                                Konten</label>
                            <select x-model="editForm.type"
                                class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                @foreach (App\Enums\CmsContentType::cases() as $t)
                                    <option value="{{ $t->value }}">
                                        {{ $t->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label
                                class="text-[10px] font-bold text-slate-500 block mb-1">Status
                                Publikasi</label>
                            <select x-model.number="editForm.is_published"
                                class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="1">Published</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" @click="closeEdit()"
                            class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-1.5 text-xs bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </div>

            <!-- 2. Day Context Section -->
            <section>
                <div class="flex justify-between items-center mb-2">
                    <label
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Konteks
                        Hari</label>
                </div>

                <!-- Display Mode -->
                <div x-show="activeEditField !== 'context'"
                    @click="openEdit('context')"
                    class="group p-3 rounded-xl border border-transparent hover:border-dashed hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer">
                    <div class="flex items-center justify-between">
                        <span
                            class="bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-lg font-bold"
                            x-text="selectedContent?.day_context?.label || selectedContent?.day_context || 'General'"></span>
                        <span
                            class="material-symbols-outlined text-xs text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">edit</span>
                    </div>
                </div>

                <!-- Form Mode -->
                <div x-show="activeEditField === 'context'"
                    class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div
                        class="flex justify-between items-center text-xs font-bold text-blue-700">
                        <span>Edit Konteks Hari</span>
                        <button type="button" @click="closeEdit()"
                            class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div>
                        <select x-model="editForm.day_context"
                            class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">Umum (General)</option>
                            @foreach (App\Enums\CmsDayContext::cases() as $ctx)
                                <option value="{{ $ctx->value }}">
                                    {{ $ctx->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" @click="closeEdit()"
                            class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-1.5 text-xs bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </section>

            <!-- 3. Content Body Section -->
            <section>
                <div class="flex justify-between items-center mb-2">
                    <label
                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Isi
                        Konten Edukasi</label>
                </div>

                <!-- Display Mode -->
                <div x-show="activeEditField !== 'body'"
                    @click="openEdit('body')"
                    class="group bg-slate-50 p-4 rounded-xl border-l-4 border-blue-600 border-transparent hover:border-dashed hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer relative">
                    <div class="flex justify-between items-start">
                        <p class="text-xs text-slate-700 leading-relaxed font-body whitespace-pre-line pr-4"
                            x-text="selectedContent?.body || 'Tidak ada isi konten'">
                        </p>
                        <span
                            class="material-symbols-outlined text-xs text-slate-400 opacity-0 group-hover:opacity-100 transition-opacity">edit</span>
                    </div>
                </div>

                <!-- Form Mode -->
                <div x-show="activeEditField === 'body'"
                    class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div
                        class="flex justify-between items-center text-xs font-bold text-blue-700">
                        <span>Edit Isi Konten</span>
                        <button type="button" @click="closeEdit()"
                            class="text-slate-400 hover:text-slate-600">✕</button>
                    </div>
                    <div>
                        <textarea rows="5" x-model="editForm.body"
                            class="w-full px-3 py-2 text-xs border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="Masukkan isi konten edukasi..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" @click="closeEdit()"
                            class="px-3 py-1.5 text-xs text-slate-600 hover:bg-slate-200 rounded-lg">Batal</button>
                        <button type="submit"
                            class="px-4 py-1.5 text-xs bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <div x-show="!selectedContent"
        class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm text-center text-slate-400 font-medium py-16 sticky top-20">
        <span
            class="material-symbols-outlined text-4xl mb-2">library_books</span>
        <p class="text-xs">Silakan pilih konten CMS di sebelah kiri untuk
            melihat pratinjau.</p>
    </div>
</div>
