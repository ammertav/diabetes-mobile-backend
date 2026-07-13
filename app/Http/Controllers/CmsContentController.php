<?php

namespace App\Http\Controllers;

use App\Models\CmsContent;
use Illuminate\Http\Request;

class CmsContentController extends Controller
{
    public function index(Request $request)
    {
        $query = CmsContent::query();

        if ($request->filled('search')) {
            $search = '%' . $request->input('search') . '%';
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

    public function create()
    {
        return view('cms.store', [
            'content' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateContent($request);

        CmsContent::create([
            'content_type' => $validated['type'],
            'day_context' => $validated['day_context'] ?? null,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_published' => (bool)$validated['is_published'],
            'published_at' => (bool)$validated['is_published'] ? now() : null,
        ]);

        return redirect()
            ->route('cms')
            ->with('success', 'Konten berhasil dibuat.');
    }

    public function edit(string $id)
    {
        $content = CmsContent::findOrFail($id);

        return view('cms.store', compact('content'));
    }

    public function update(string $id, Request $request)
    {
        $content = CmsContent::findOrFail($id);
        $validated = $this->validateContent($request);

        $content->update([
            'content_type' => $validated['type'],
            'day_context' => $validated['day_context'] ?? null,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_published' => (bool)$validated['is_published'],
            'published_at' => (bool)$validated['is_published'] ? ($content->published_at ?? now()) : null,
        ]);

        return redirect()
            ->route('cms')
            ->with('success', 'Konten berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $content = CmsContent::findOrFail($id);
        $content->delete();

        return redirect()
            ->route('cms')
            ->with('success', 'Konten berhasil dihapus.');
    }

    private function validateContent(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'day_context' => ['nullable', 'string', 'max:100'],
            'body' => ['required', 'string'],
            'is_published' => ['required', 'in:0,1'],
        ]);
    }
}
