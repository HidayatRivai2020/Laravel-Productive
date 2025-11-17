<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Content;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            return;
        }

        $contents = [
            ['name' => 'Laravel Basics', 'desc' => 'Introduction to Laravel framework', 'category' => 'Development'],
            ['name' => 'Advanced PHP', 'desc' => 'Deep dive into PHP features', 'category' => 'Development'],
            ['name' => 'Vue.js Tutorial', 'desc' => 'Frontend development with Vue', 'category' => 'Development'],
            ['name' => 'Figma Essentials', 'desc' => 'UI design with Figma', 'category' => 'Design'],
            ['name' => 'Color Theory', 'desc' => 'Understanding colors in design', 'category' => 'Design'],
            ['name' => 'SEO Fundamentals', 'desc' => 'Search engine optimization basics', 'category' => 'Marketing'],
            ['name' => 'Social Media Strategy', 'desc' => 'Building social media presence', 'category' => 'Marketing'],
            ['name' => 'Startup Planning', 'desc' => 'Planning your business startup', 'category' => 'Business'],
            ['name' => 'Time Management', 'desc' => 'Managing your time effectively', 'category' => 'Personal'],
            ['name' => 'Mindfulness Practice', 'desc' => 'Daily mindfulness exercises', 'category' => 'Personal'],
        ];

        foreach ($contents as $contentData) {
            $category = $categories->firstWhere('name', $contentData['category']);
            if ($category) {
                Content::create([
                    'id' => Str::uuid(),
                    'name' => $contentData['name'],
                    'desc' => $contentData['desc'],
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
