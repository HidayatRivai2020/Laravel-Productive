<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\Objective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.tasks.index', compact('categories'));
    }

    /**
     * Display a listing of the resource with pre-selected filters.
     */
    public function indexWithSelection(Request $request)
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        
        $selectedCategory = null;
        $selectedContent = null;
        $selectedObjective = null;
        $contents = collect();
        $objectives = collect();
        $tasks = collect();
        
        // Get selected values from request
        $categoryId = $request->get('category_id');
        $contentId = $request->get('content_id');
        $objectiveId = $request->get('objective_id');
        
        if ($categoryId) {
            $selectedCategory = \App\Models\Category::find($categoryId);
            if ($selectedCategory) {
                $contents = \App\Models\Content::where('category_id', $categoryId)->orderBy('name')->get();
                
                if ($contentId) {
                    $selectedContent = \App\Models\Content::find($contentId);
                    if ($selectedContent) {
                        $objectives = \App\Models\Objective::where('content_id', $contentId)->orderBy('name')->get();
                        
                        if ($objectiveId) {
                            $selectedObjective = \App\Models\Objective::find($objectiveId);
                            if ($selectedObjective) {
                                $tasks = \App\Models\Task::with('objective:id,name')->where('objective_id', $objectiveId)->orderBy('id')->get();
                            }
                        }
                    }
                }
            }
        }
        
        return view('admin.tasks.index-with-selection', compact(
            'categories', 
            'selectedCategory', 
            'selectedContent', 
            'selectedObjective',
            'contents', 
            'objectives', 
            'tasks'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.tasks.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        
        // Map selected_objective_id to objective_id
        if (isset($data['selected_objective_id'])) {
            $data['objective_id'] = $data['selected_objective_id'];
            unset($data['selected_objective_id']);
        }
        
        // Get the next ID for this objective
        $data['id'] = Task::getNextId($data['objective_id']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('tasks', 'public');
        }

        Task::create($data);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $objectiveId, string $id)
    {
        $task = Task::where('objective_id', $objectiveId)
                   ->where('id', $id)
                   ->with('objective')
                   ->firstOrFail();
        
        return view('admin.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $objectiveId, string $id)
    {
        $task = Task::where('objective_id', $objectiveId)
                   ->where('id', $id)
                   ->firstOrFail();
        
        $objectives = Objective::orderBy('name')->get();
        
        return view('admin.tasks.edit', compact('task', 'objectives'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $objectiveId, string $id)
    {
        $task = Task::where('objective_id', $objectiveId)
                   ->where('id', $id)
                   ->firstOrFail();
        
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($task->image) {
                Storage::disk('public')->delete($task->image);
            }
            $data['image'] = $request->file('image')->store('tasks', 'public');
        }

        $task->update($data);

        // Get related models for redirect parameters
        $task->load('objective.content.category');
        $objective = $task->objective;
        $content = $objective ? $objective->content : null;
        $category = $content ? $content->category : null;

        return redirect()->route('tasks.index.with-selection', [
            'category_id' => $category ? $category->id : null,
            'content_id' => $content ? $content->id : null,
            'objective_id' => $objective ? $objective->id : null
        ])->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $objectiveId, string $id)
    {
        $task = Task::where('objective_id', $objectiveId)
                   ->where('id', $id)
                   ->firstOrFail();
        
        // Get related models for redirect parameters before deletion
        $task->load('objective.content.category');
        $objective = $task->objective;
        $content = $objective ? $objective->content : null;
        $category = $content ? $content->category : null;
        
        // Delete image if exists
        if ($task->image) {
            Storage::disk('public')->delete($task->image);
        }
        
        $task->delete();

        return redirect()->route('tasks.index.with-selection', [
            'category_id' => $category ? $category->id : null,
            'content_id' => $content ? $content->id : null,
            'objective_id' => $objective ? $objective->id : null
        ])->with('success', 'Task deleted successfully.');
    }
}
