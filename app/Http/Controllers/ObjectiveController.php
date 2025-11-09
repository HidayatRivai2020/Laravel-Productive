<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObjectiveRequest;
use App\Models\Objective;
use App\Models\Category;
use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObjectiveController extends Controller
{
    public function index(Request $request)
    {
        $query = Objective::query()->with('content');

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');
        $perPage = (int) $request->query('per_page', 10);

        $objectives = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        return view('admin.objectives.index', compact('objectives'));
    }

    public function create()
    {
        // load categories to select first, then load contents via AJAX based on selected category
        $categories = Category::orderBy('name')->get();
        $firstCategoryId = $categories->first()?->id;
        $contents = $firstCategoryId ? Content::where('category_id', $firstCategoryId)->get() : collect();

        return view('admin.objectives.create', compact('categories', 'contents', 'firstCategoryId'));
    }

    public function store(ObjectiveRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('objectives', 'public');
            $data['image'] = $path;
        }

        Objective::create($data);

        return redirect()->route('objectives.index')->with('success', 'Objective created.');
    }

    public function show(Objective $objective)
    {
        $objective->load('content');
        return view('admin.objectives.show', compact('objective'));
    }

    public function edit(Objective $objective)
    {
        $categories = Category::orderBy('name')->get();
        $selectedCategory = $objective->content?->category_id ?? $categories->first()?->id;
        $contents = $selectedCategory ? Content::where('category_id', $selectedCategory)->get() : collect();

        return view('admin.objectives.edit', compact('objective', 'categories', 'contents', 'selectedCategory'));
    }

    public function update(ObjectiveRequest $request, Objective $objective)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // delete previous image if exists
            if ($objective->image) {
                Storage::disk('public')->delete($objective->image);
            }
            $path = $request->file('image')->store('objectives', 'public');
            $data['image'] = $path;
        }

        $objective->update($data);

        return redirect()->route('objectives.index')->with('success', 'Objective updated.');
    }

    public function destroy(Objective $objective)
    {
        if ($objective->image) {
            Storage::disk('public')->delete($objective->image);
        }

        $objective->delete();

        return back()->with('success', 'Objective deleted.');
    }

    /**
     * Return contents for a given category (AJAX)
     */
    public function contentsByCategory($categoryId)
    {
        $contents = Content::where('category_id', $categoryId)->orderBy('name')->get(['id', 'name']);
        return response()->json($contents);
    }
}
