<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Employee;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_category = [
            'Sub Category 01',
            'Sub Category 02',
            'Sub Category 03',
            'Sub Category 04',
            'Sub Category 05',
            'Sub Category 06',
            'Sub Category 07',
            'Sub Category 08',
            'Sub Category 09',
            'Sub Category 10',
        ];

        $faker = Faker::create('id_ID');

        $technicians = Employee::whereHas('user', function ($query) {
            $query->where('role', 'Technician');
        })->pluck('id')->toArray();

        for ($i = 0; $i < count($sub_category); $i++) {
            SubCategory::create([
                'category_id' => $faker->numberBetween(1, Category::count()),
                'technician_id' => $faker->randomElement($technicians),
                'name' => $sub_category[$i],
            ]);
        }
    }
}
