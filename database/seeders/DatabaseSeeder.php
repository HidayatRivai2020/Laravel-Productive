<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create test users for local/dev testing
        $users = [
            [
                'name' => 'test user',
                'email' => 'test@zone.com',
                'password' => bcrypt('qweqweqwe'),
            ],
            [
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('12345678'),
            ],
        ];

        foreach ($users as $userData) {
            User::factory()->create($userData);
        }

        // Seed all modules with dummy data
        $this->call([
            CategorySeeder::class,
            ContentSeeder::class,
            ObjectiveSeeder::class,
            TodoSeeder::class,
            TaskSeeder::class,
        ]);
    }
}
