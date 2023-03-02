<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\SubDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SubDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_department = [
            'Sub Department 01',
            'Sub Department 02',
            'Sub Department 03',
            'Sub Department 04',
            'Sub Department 05',
            'Sub Department 06',
            'Sub Department 07',
            'Sub Department 08',
            'Sub Department 09',
            'Sub Department 10',
        ];

        $faker = Faker::create('id_ID');

        for ($i=0; $i < count($sub_department); $i++) { 
            SubDepartment::create([
                'name' => $sub_department[$i],
                'department_id' => $faker->numberBetween(1, Department::count())
            ]);
        }
    }
}
