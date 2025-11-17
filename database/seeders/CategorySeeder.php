<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['id' => Str::uuid(), 'name' => 'Development', 'desc' => 'Software development and programming'],
            ['id' => Str::uuid(), 'name' => 'Design', 'desc' => 'UI/UX and graphic design'],
            ['id' => Str::uuid(), 'name' => 'Marketing', 'desc' => 'Digital marketing and campaigns'],
            ['id' => Str::uuid(), 'name' => 'Business', 'desc' => 'Business strategy and operations'],
            ['id' => Str::uuid(), 'name' => 'Personal', 'desc' => 'Personal development and hobbies'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
