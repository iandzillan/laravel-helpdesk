<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = [
            'IT Department',
            'HR Department', 
            'Marketing Department'
        ];

        for ($i=0; $i < count($department); $i++) { 
            Department::create([
                'name' => $department[$i]
            ]);
        }
    }
}
