<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Programming',
            'Web Development',
            'Mobile App Development',
            'Data Science',
            'Artificial Intelligence',
            'Machine Learning',
            'Cyber Security',
            'UI / UX Design',
            'Graphic Design',
            'Digital Marketing',
            'Business & Entrepreneurship',
            'Finance & Accounting',
            'Personal Development',
            'Photography & Video',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name'   => $category,
                'status' => 1,
            ]);
        }
    }
}
