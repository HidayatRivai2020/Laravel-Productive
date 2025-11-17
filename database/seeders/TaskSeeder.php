<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Objective;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $objectives = Objective::all();

        if ($objectives->isEmpty()) {
            $this->command->info('No objectives found. Please seed objectives first.');
            return;
        }

        // Create sample tasks for each objective
        foreach ($objectives as $objective) {
            $tasksData = [
                ['detail' => 'Initial setup and configuration', 'status' => 1],
                ['detail' => 'Research and planning phase', 'status' => 0],
                ['detail' => 'Implementation and testing', 'status' => 2],
                ['detail' => 'Documentation and review', 'status' => 0],
            ];

            foreach ($tasksData as $index => $taskData) {
                Task::create([
                    'objective_id' => $objective->id,
                    'id' => $index + 1,
                    'detail' => $taskData['detail'],
                    'status' => $taskData['status'],
                ]);
            }
        }
    }
}
