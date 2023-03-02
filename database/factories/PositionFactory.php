<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $positions = [
            'Position 01',
            'Position 02',
            'Position 03',
            'Position 04',
            'Position 05',
            'Position 06',
            'Position 07',
            'Position 08',
            'Position 09',
            'Position 10',
        ];

        $faker = Faker::create('id_ID');

        return [
            'name'              => $faker->unique()->randomElement($positions),
            'sub_department_id' => $faker->numberBetween(1, 5)
        ];
    }
}
