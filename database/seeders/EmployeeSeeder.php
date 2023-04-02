<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SubDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $positions = [
            'Manager',
            'Team Leader',
            'Team Member'
        ];

        for ($i=0; $i < count($positions); $i++) { 
            switch ($positions[$i]) {
                case 'Manager':
                    for ($j=0; $j < Department::count(); $j++) { 
                        Employee::create([
                            'nik'               => $faker->unique()->numerify('1#####'),
                            'name'              => $faker->firstName . ' ' . $faker->lastName,
                            'position'          => 'Manager',
                            'sub_department_id' => null,
                            'department_id'     => $j+1,
                            'image'             => 'avtar_1.png',
                            'isRequest'         => 2
                        ]);
                    }
                    break;
                
                case 'Team Leader':
                    for ($j=0; $j < SubDepartment::count(); $j++) { 
                        $department = SubDepartment::where('id', $j+1)->first();
                        Employee::create([
                            'nik'               => $faker->unique()->numerify('1#####'),
                            'name'              => $faker->firstName . ' ' . $faker->lastName,
                            'position'          => 'Team Leader',
                            'sub_department_id' => $j+1,
                            'department_id'     => $department->department_id,
                            'image'             => 'avtar_1.png',
                            'isRequest'         => 2
                        ]);
                    }
                    break;
                
                case 'Team Member':
                    $department_id      = $faker->numberBetween(2, Department::count());
                    $sub_department_id  = SubDepartment::where('department_id', $department_id)->pluck('id');

                    for ($j=0; $j < 30; $j++) { 
                        Employee::create([
                            'nik'               => $faker->unique()->numerify('1#####'),
                            'name'              => $faker->firstName . ' ' . $faker->lastName,
                            'position'          => 'Team Member',
                            'sub_department_id' => $faker->randomElement($sub_department_id),
                            'department_id'     => $department_id,
                            'image'             => 'avtar_1.png',
                            'isRequest'         => $faker->numberBetween(0, 2)
                        ]);
                    }

                    $department_it      = Department::where('name', 'IT Department')->first();
                    $sub_department_it  = SubDepartment::where('department_id', $department_it->id)->pluck('id');

                    for ($j=0; $j < 10; $j++) { 
                        Employee::create([
                            'nik'               => $faker->unique()->numerify('1#####'),
                            'name'              => $faker->firstName . ' ' . $faker->lastName,
                            'position'          => 'Team Member',
                            'sub_department_id' => $faker->randomElement($sub_department_it),
                            'department_id'     => $department_it->id,
                            'image'             => 'avtar_1.png',
                            'isRequest'         => 2
                        ]);
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }
}
