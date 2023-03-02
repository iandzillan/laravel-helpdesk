<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubDepartment>
 */
class SubDepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sub_department = [
            'Sub Deparment 01',
            'Sub Deparment 02',
            'Sub Deparment 03',
            'Sub Deparment 04',
            'Sub Deparment 05',
        ];

        $faker = Faker::create('id_ID');
        return [
            'name'          => $faker->unique()->randomElement($sub_department),
            'department_id' => $faker->numberBetween(1, 3),
        ];
    }
}
