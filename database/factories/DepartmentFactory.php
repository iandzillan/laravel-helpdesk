<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $department = [
            'IT Department',
            'Marketing Department',
            'HR Department',
        ];

        $faker = Faker::create('id_ID');

        return [
            'name' => $faker->unique()->randomElement($department)
        ];
    }
}
