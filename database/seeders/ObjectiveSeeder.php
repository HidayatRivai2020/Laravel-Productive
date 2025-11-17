<?php

namespace Database\Seeders;

use App\Models\Content;
use App\Models\Objective;
use Illuminate\Database\Seeder;

class ObjectiveSeeder extends Seeder
{
    public function run(): void
    {
        $contents = Content::all();
        
        if ($contents->isEmpty()) {
            return;
        }

        $objectives = [
            ['name' => 'Setup development environment', 'description' => 'Install and configure all necessary tools'],
            ['name' => 'Complete tutorial chapters 1-3', 'description' => 'Read and practice the first three chapters'],
            ['name' => 'Build practice project', 'description' => 'Create a small project to apply learned concepts'],
            ['name' => 'Review and document', 'description' => 'Write notes and document key learnings'],
            ['name' => 'Share progress with team', 'description' => 'Present findings to the team'],
        ];

        foreach ($contents as $content) {
            foreach ($objectives as $index => $objectiveData) {
                if ($index >= 2) break; // Create 2 objectives per content
                
                Objective::create([
                    'name' => $objectiveData['name'],
                    'content_id' => $content->id,
                    'description' => $objectiveData['description'],
                    'image' => null,
                ]);
            }
        }
    }
}
