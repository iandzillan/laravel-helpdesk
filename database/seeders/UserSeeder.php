<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $employees = Employee::where('isRequest', 2)->get();

        foreach ($employees as $employee) {
            switch ($employee->position) {
                case 'Manager':
                    User::create([
                        'employee_id'   => $employee->id,
                        'username'  => strtolower(str_replace(" ", "", $employee->name)),
                        'email'     => strtolower(str_replace(" ", "", $employee->name)) . "@example.com",
                        'password'  => Hash::make('password'),
                        'role'      => 'Approver1'
                    ]);
                    break;
                
                case 'Team Leader':
                    User::create([
                        'employee_id'   => $employee->id,
                        'username'  => strtolower(str_replace(" ", "", $employee->name)),
                        'email'     => strtolower(str_replace(" ", "", $employee->name)) . "@example.com",
                        'password'  => Hash::make('password'),
                        'role'      => 'Approver2'
                    ]);
                    break;

                case 'Team Member':
                    switch ($employee->sub_department_id) {
                        case 1:
                            User::create([
                                'employee_id'   => $employee->id,
                                'username'  => strtolower(str_replace(" ", "", $employee->name)),
                                'email'     => strtolower(str_replace(" ", "", $employee->name)) . "@example.com",
                                'password'  => Hash::make('password'),
                                'role'      => 'Admin'
                            ]);
                            break;
                        
                        case 3:
                            User::create([
                                'employee_id'   => $employee->id,
                                'username'  => strtolower(str_replace(" ", "", $employee->name)),
                                'email'     => strtolower(str_replace(" ", "", $employee->name)) . "@example.com",
                                'password'  => Hash::make('password'),
                                'role'      => 'Technician'
                            ]);
                            break;
                        
                        default:
                            User::create([
                                'employee_id'   => $employee->id,
                                'username'  => strtolower(str_replace(" ", "", $employee->name)),
                                'email'     => strtolower(str_replace(" ", "", $employee->name)) . "@example.com",
                                'password'  => Hash::make('password'),
                                'role'      => 'User'
                            ]);
                            break;
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
        }
    }
}
