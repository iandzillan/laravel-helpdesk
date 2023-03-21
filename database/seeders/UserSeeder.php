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

        $managers = Manager::all();

        foreach ($managers as $manager) {
            $user = new User;
            $user->username = strtolower(str_replace(" ", "", $manager->name));
            $user->email    = strtolower(str_replace(" ", "", $manager->name)) . "@example.com";
            $user->role     = 'Approver1';
            $user->password = Hash::make('password');
            $manager->user()->save($user);
        }

        $role_employee = [
            'Approver2',
            'Admin',
            'User',
            'Technician',
        ];

        $employees = Employee::all();

        foreach ($employees as $employee) {
            $user = new User;
            $user->username = strtolower(str_replace(" ", "", $employee->name));
            $user->email    = strtolower(str_replace(" ", "", $employee->name)) . "@example.com";
            $user->role     = $faker->randomElement($role_employee);
            $user->password = Hash::make('password');
            $employee->user()->save($user);
        }
    }
}
