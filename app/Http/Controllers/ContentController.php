<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');
        $perPage = (int) $request->query('per_page', 10);

        $query = Content::with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%");
            });
        }

        if (!in_array($sortBy, ['name', 'created_at'])) {
            $sortBy = 'created_at';
        }
        $sortDir = strtolower($sortDir) === 'asc' ? 'asc' : 'desc';

        $contents = $query->orderBy($sortBy, $sortDir)->paginate($perPage)->appends($request->query());

        // Also provide categories for the modal-based create UI
        $categories = Category::orderBy('name')->get();

        return view('admin.contents.index', compact('contents', 'categories'));
    }

    public function create()
    {
        // Redirect to index which now contains the modal-based create UI
        return redirect()->route('contents.index', ['create' => 1]);
    }

    public function store(ContentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['id'])) {
            $data['id'] = (string) Str::uuid();
        }

        Content::create($data);

        return Redirect::route('contents.index')->with('success', 'Content created.');
    }

    public function show($id)
    {
        $content = Content::with('category')->findOrFail($id);
        return view('admin.contents.show', compact('content'));
    }

    public function edit($id)
    {
        $content = Content::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('admin.contents.edit', compact('content', 'categories'));
    }

    public function update(ContentRequest $request, $id): RedirectResponse
    {
        $content = Content::findOrFail($id);
        $data = $request->validated();
        if (isset($data['id'])) {
            unset($data['id']);
        }
        $content->update($data);

        return Redirect::route('contents.index')->with('success', 'Content updated.');
    }

    public function destroy($id): RedirectResponse
    {
        $content = Content::findOrFail($id);
        // Prevent deletion if there are child objectives
        $hasChildren = \App\Models\Objective::where('content_id', $content->id)->exists();
        if ($hasChildren) {
            return Redirect::route('contents.index')->with('error', 'Cannot delete content because it has related objectives. Please delete or reassign its objectives first.');
        }

        try {
            $content->delete();
            return Redirect::route('contents.index')->with('success', 'Content deleted.');
        } catch (\Exception $e) {
            return Redirect::route('contents.index')->with('error', 'Unable to delete content.');
        }
    }
}
