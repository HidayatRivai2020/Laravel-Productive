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

        return view('admin.contents.index', compact('contents'));
    }

    public function create()
    {
        // Categories are selected via paginated selector (AJAX) in the form.
        return view('admin.contents.create');
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
        // Categories are selected via paginated selector (AJAX) in the form.
        return view('admin.contents.edit', compact('content'));
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
        $content->delete();

        return Redirect::route('contents.index')->with('success', 'Content deleted.');
    }
}
