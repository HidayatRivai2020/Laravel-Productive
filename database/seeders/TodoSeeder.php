<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Todo;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        $contents = Content::all();
        
        $todos = [
            ['title' => 'Research best practices', 'deadline' => now()->addDays(7)],
            ['title' => 'Create wireframes', 'deadline' => now()->addDays(5)],
            ['title' => 'Write unit tests', 'deadline' => now()->addDays(10)],
            ['title' => 'Update documentation', 'deadline' => now()->addDays(3)],
            ['title' => 'Code review session', 'deadline' => now()->addDays(14)],
            ['title' => 'Performance optimization', 'deadline' => null],
            ['title' => 'Security audit', 'deadline' => now()->addDays(21)],
            ['title' => 'Deploy to staging', 'deadline' => now()->addDays(2)],
        ];

        foreach ($todos as $todoData) {
            Todo::create([
                'title' => $todoData['title'],
                'deadline' => $todoData['deadline'],
            ]);
        }
    }
}
