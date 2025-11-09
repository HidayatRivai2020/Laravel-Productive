<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'created_at');
        $sortDir = $request->query('sort_dir', 'desc');
        $perPage = (int) $request->query('per_page', 10);

        $query = Category::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('desc', 'like', "%{$search}%");
            });
        }

        // Allow ordering only on specific fields
        if (!in_array($sortBy, ['name', 'created_at'])) {
            $sortBy = 'created_at';
        }
        $sortDir = strtolower($sortDir) === 'asc' ? 'asc' : 'desc';

        $categories = $query->orderBy($sortBy, $sortDir)->paginate($perPage)->appends($request->query());

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Return paginated categories as JSON for AJAX selectors.
     */
    public function paginated(Request $request)
    {
        $search = $request->query('search');
        $perPage = (int) $request->query('per_page', 5);

        $query = Category::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $p = $query->orderBy('name')->paginate($perPage)->appends($request->query());

        return response()->json([
            'data' => $p->items(),
            'meta' => [
                'current_page' => $p->currentPage(),
                'last_page' => $p->lastPage(),
                'per_page' => $p->perPage(),
                'total' => $p->total(),
            ],
        ]);
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        // If an ID was provided in the form use it; otherwise generate a UUID.
        if (empty($data['id'])) {
            $data['id'] = (string) Str::uuid();
        }

        Category::create($data);

        return Redirect::route('categories.index')->with('success', 'Category created.');
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, $id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        // Prevent changing the primary key on update even if id was present in the request.
        $data = $request->validated();
        if (isset($data['id'])) {
            unset($data['id']);
        }

        $category->update($data);

        return Redirect::route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy($id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return Redirect::route('categories.index')->with('success', 'Category deleted.');
    }
}
