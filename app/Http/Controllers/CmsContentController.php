<?php

namespace App\Http\Controllers;

use App\Actions\Content\CreateCmsContentAction;
use App\Actions\Content\DeleteCmsContentAction;
use App\Actions\Content\UpdateCmsContentAction;
use App\Http\Requests\StoreCmsContentRequest;
use App\Http\Requests\UpdateCmsContentRequest;
use App\Models\CmsContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsContentController extends Controller
{
    public function index(Request $request): View
    {
        $query = CmsContent::query();

        if ($request->filled('search')) {
            $search = '%'.$request->input('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                    ->orWhere('id', 'like', $search);
            });
        }

        if ($request->filled('type')) {
            $query->where('content_type', $request->input('type'));
        }

        if ($request->filled('day_context')) {
            $query->where('day_context', $request->input('day_context'));
        }

        $contents = $query->latest()->paginate(12);

        return view('cms.index', compact('contents'));
    }

    public function create(): View
    {
        return view('cms.store', ['content' => null]);
    }

    public function store(StoreCmsContentRequest $request, CreateCmsContentAction $action): RedirectResponse
    {
        $action->execute(
            $request->validated(),
            $request->file('image_file'),
            $request->file('video_file'),
            $request->file('thumbnail_file')
        );

        return redirect()->route('cms')->with('success', 'Konten berhasil dibuat.');
    }

    public function edit(string $id): View
    {
        $content = CmsContent::findOrFail($id);

        return view('cms.store', compact('content'));
    }

    public function update(
        string $id,
        UpdateCmsContentRequest $request,
        UpdateCmsContentAction $action
    ): RedirectResponse {
        $content = CmsContent::findOrFail($id);

        $action->execute(
            $content,
            $request->validated(),
            $request->file('image_file'),
            $request->file('video_file'),
            $request->file('thumbnail_file')
        );

        return redirect()->route('cms')->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(string $id, DeleteCmsContentAction $action): RedirectResponse
    {
        $content = CmsContent::findOrFail($id);
        $action->execute($content);

        return redirect()->route('cms')->with('success', 'Konten berhasil dihapus.');
    }
}
