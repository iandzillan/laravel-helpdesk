<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
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
        $faker = Faker::create('id_ID');

        $department = [
            'IT Department',
            'HR Department',
            'Marketing Department'
        ];

        for ($i = 0; $i < count($department); $i++) {
            Department::create([
                'name' => $department[$i]
            ]);
        }
    }
}
