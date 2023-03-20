<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Manager;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $departments = Department::all();

        foreach ($departments as $department) {
            Manager::create([
                'nik'           => $faker->unique()->numerify('10####'),
                'name'          => $faker->firstName . " " . $faker->lastName,
                'position'      => "Head of $department->name",
                'image'         => 'avtar_1.png',
                'isRequest'     => 2,
                'department_id' => $department->id
            ]);
        }
    }
}
