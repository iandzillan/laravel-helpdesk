<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use App\Models\SubDepartment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $position = [
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
            'Position 11',
            'Position 12',
            'Position 13',
            'Position 14',
            'Position 15',
            'Position 16',
            'Position 17',
            'Position 18',
            'Position 19',
            'Position 20',
        ];

        $faker = Faker::create('id_ID');

        for ($i = 0; $i < count($position); $i++) {

            Position::create([
                'name' => $position[$i],
                'sub_department_id' => $faker->numberBetween(1, SubDepartment::count()),
            ]);
        }
    }
}
