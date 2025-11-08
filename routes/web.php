<?php

use App\Http\Controllers\ProfileController;
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
