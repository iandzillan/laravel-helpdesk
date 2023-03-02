<?php

namespace Database\Seeders;

use App\Models\Employee;
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

        $role = [
            'Admin',
            'Approver',
            'User',
            'Technician',
        ];

        $employees = Employee::all();

        foreach ($employees as $employee) {
            User::create([
                'employee_id'   => $employee->id,
                'username'      => strtolower(str_replace(" ", "", $employee->name)),
                'email'         => strtolower(str_replace(" ", "", $employee->name)) . "@example.com",
                'role'          => $faker->randomElement($role),
                'password'      => Hash::make('password')
            ]);
        }
    }
}
