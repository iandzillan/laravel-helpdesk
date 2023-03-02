<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategory>
 */
class SubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sub_categories = [
            'Sub Category 01',
            'Sub Category 02',
            'Sub Category 03',
            'Sub Category 04',
            'Sub Category 05',
            'Sub Category 06',
            'Sub Category 07',
            'Sub Category 08',
            'Sub Category 09',
            'Sub Category 10',
        ];

        $faker = Faker::create('id_ID');

        return [
            'category_id'   => $faker->numberBetween(1, 4),
            'name'          => $faker->unique()->randomElement($sub_categories)
        ];
    }
}
