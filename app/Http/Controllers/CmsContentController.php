<?php

namespace App\Http\Controllers;

use App\Models\CmsContent;
use Illuminate\Http\Request;

class CmsContentController extends Controller
{
    public function index(Request $request)
    {
        return view('cms.index');
    }

    public function create(Request $request)
    {
        return view('cms.store');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:100'],
            'day_context' => ['nullable', 'string', 'max:100'],
            'body' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $content = CmsContent::create([
            'content_type' => $validated['type'],
            'day_context' => $validated['day_context'] ?? null,

            'title' => $validated['title'],
            'body' => $validated['body'],

            'is_published' => $validated['is_published'] ?? false,

            'published_at' => ($validated['is_published'] ?? false)
                ? now()
                : null,
        ]);

        return redirect()
            ->route('cms')
            ->with('success', 'Konten berhasil dibuat.');
    }
}
