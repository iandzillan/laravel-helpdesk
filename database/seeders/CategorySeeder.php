<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = [
            'Hardware',
            'Software',
            'Service',
            'Request',
        ];

        for ($i=0; $i < count($category); $i++) { 
            Category::create([
                'name' => $category[$i]
            ]);
        }
    }
}
