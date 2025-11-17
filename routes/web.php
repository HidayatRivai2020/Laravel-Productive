<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ObjectiveController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

// Group admin pages that share the same middleware and prefix
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // Admin dashboard (now at /admin/dashboard)
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('dashboard');

    // Speedrun / stopwatch page (now at /admin/speedrun)
    Route::get('/speedrun', function () {
        return view('admin.speedrun');
    })->name('admin.speedrun');

    // Export endpoint to save the exported txt file in storage/app/exports/{title}/
    Route::post('/speedrun/export', function (Request $request) {
        $data = $request->validate([
            'title' => ['nullable','string'],
            'filename' => ['required','string'],
            'content' => ['required','string'],
        ]);

        $title = $data['title'] ?? 'untitled';
        $safeTitle = preg_replace('/[^A-Za-z0-9-_]/', '_', $title);
        $filename = $data['filename'];

    $path = "exports/{$safeTitle}/{$filename}";

    // store under storage/app/public/exports/{safeTitle}/{filename} (public disk)
    Storage::disk('public')->put($path, $data['content']);

    // return public URL (requires `php artisan storage:link` to be run)
    $url = Storage::disk('public')->url($path);

    return response()->json(['ok' => true, 'path' => $path, 'url' => $url]);
    })->name('admin.speedrun.export');

    // Category CRUD (admin/categories/*)
    // Paginated JSON endpoint for AJAX selectors (define before resource to avoid parameter conflicts)
    Route::resource('categories', CategoryController::class);

    // Content CRUD (admin/contents/*)
    // Provide an endpoint to fetch contents by category for AJAX selectors
    Route::resource('contents', ContentController::class);

    // Objectives CRUD
    Route::resource('objectives', ObjectiveController::class);
    
    // Todos CRUD
    Route::resource('todos', \App\Http\Controllers\TodoController::class);

    // Tasks CRUD with composite primary key
    Route::get('tasks/with-selection', [\App\Http\Controllers\TaskController::class, 'indexWithSelection'])->name('tasks.index.with-selection');
    Route::get('tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/create', [\App\Http\Controllers\TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{objectiveId}/{id}', [\App\Http\Controllers\TaskController::class, 'show'])->name('tasks.show');
    Route::get('tasks/{objectiveId}/{id}/edit', [\App\Http\Controllers\TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{objectiveId}/{id}', [\App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{objectiveId}/{id}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');

    // API endpoints for AJAX cascading dropdowns
    Route::get('categories/{categoryId}/contents', function($categoryId) {
        return \App\Models\Content::where('category_id', $categoryId)->orderBy('name')->get(['id', 'name']);
    });
    
    Route::get('contents/{contentId}/objectives', function($contentId) {
        return \App\Models\Objective::where('content_id', $contentId)->orderBy('name')->get(['id', 'name']);
    });
    
    Route::get('objectives/{objectiveId}/tasks', function($objectiveId) {
        return \App\Models\Task::with('objective:id,name')->where('objective_id', $objectiveId)->orderBy('id')->get();
    });
    
    Route::get('tasks/all', function() {
        return \App\Models\Task::with('objective:id,name')->orderBy('objective_id')->orderBy('id')->get();
    });
});

// Keep the legacy /dashboard route working by redirecting to the new grouped URL.
// This preserves existing links while centralizing the admin routes.
Route::redirect('/dashboard', '/admin/dashboard')->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
