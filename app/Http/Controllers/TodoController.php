<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        $todos = Todo::orderBy('created_at', 'desc')->paginate($perPage)->appends($request->query());

        return view('admin.todos.index', compact('todos'));
    }

    public function create()
    {
        // modal-based create UI lives on index
        return redirect()->route('todos.index', ['create' => 1]);
    }

    public function store(TodoRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Todo::create($data);

        return Redirect::route('todos.index')->with('success', 'Todo_List created.');
    }

    public function show($id)
    {
        $todo = Todo::findOrFail($id);

        return view('admin.todos.show', compact('todo'));
    }

    public function edit($id)
    {
        $todo = Todo::findOrFail($id);

        return view('admin.todos.edit', compact('todo'));
    }

    public function update(TodoRequest $request, $id): RedirectResponse
    {
        $todo = Todo::findOrFail($id);
        $todo->update($request->validated());

        return Redirect::route('todos.index')->with('success', 'Todo_List updated.');
    }

    public function destroy($id): RedirectResponse
    {
        $todo = Todo::findOrFail($id);
        try {
            $todo->delete();
            return Redirect::route('todos.index')->with('success', 'Todo_List deleted.');
        } catch (\Exception $e) {
            return Redirect::route('todos.index')->with('error', 'Unable to delete todo_list.');
        }
    }
}
