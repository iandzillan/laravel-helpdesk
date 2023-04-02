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
        $sub_department_it = [
            'IT Service Desk',
            'IT Administration',
            'Infrastructure',
            'Information System',
            'Operation'
        ];

        $sub_department_hr = [
            'Training and Development', 
            'Management Support',
            'Recruitment',
            'Organizational Development',
            'Payroll', 
            'Legal'
        ];

        $sub_department_marketing = [
            'Market Research',
            'Digital Marketing',
            'Data Analyst',
            'Visual Designer',
            'Public Relation'
        ];

        $faker = Faker::create('id_ID');

        $departments = Department::all();

        foreach ($departments as $department) {
            switch ($department->name) {
                case 'IT Department':
                    for ($i=0; $i < count($sub_department_it); $i++) { 
                        SubDepartment::create([
                            'name'          => $sub_department_it[$i],
                            'department_id' => $department->id
                        ]);
                    }
                    break;

                case 'HR Department':
                    for ($i=0; $i < count($sub_department_hr); $i++) { 
                        SubDepartment::create([
                            'name'          => $sub_department_hr[$i],
                            'department_id' => $department->id 
                        ]);
                    }
                    break;

                case 'Marketing Department':
                    for ($i=0; $i < count($sub_department_marketing); $i++) { 
                        SubDepartment::create([
                            'name'          => $sub_department_marketing[$i],
                            'department_id' => $department->id 
                        ]);
                    }
                    break;
                
                default:
                    
                    break;
            }
        }
    }
}
