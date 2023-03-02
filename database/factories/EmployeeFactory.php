<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = Faker::create('id_ID');
        return [
            'nik'           => $faker->unique()->numerify('10####'),
            'name'          => $faker->firstName . " " . $faker->lastName,
            'position_id'   => $faker->numberBetween(1, 10),
        ];
    }
}
