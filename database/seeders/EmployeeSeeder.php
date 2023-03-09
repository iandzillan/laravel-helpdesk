<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
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

        for ($i = 0; $i < 30; $i++) {
            Employee::create([
                'nik'           => $faker->unique()->numerify('10####'),
                'name'          => $faker->firstName . " " . $faker->lastName,
                'position_id'   => $faker->numberBetween(1, Position::count()),
                'image'         => 'avtar_1.png'
            ]);
        }
    }
}
